<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Http\Requests\Auth\AdminVerifyTwoFaRequest;
use App\Models\Core\Setting;
use App\Models\User;
use App\Services\AdminEmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    // ---------------
    // Show the admin login form.
    // Redirect to manage dashboard if user is already authenticated as admin.
    // ---------------
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $user->loadMissing('role');
            $slug = $user->role?->slug;

            if (in_array($slug, ['super_admin', 'supervisor', 'agent'], true)) {
                return redirect()->route('manage.dashboard.index');
            }
        }

        return view('manage.auth.login');
    }

    // ---------------
    // Handle admin login form submission.
    // Verifies credentials + role, generates 2FA code, sends email via SMTP.
    // ---------------
    public function login(AdminLoginRequest $request): RedirectResponse
    {
        // ---------------
        // Find user by email and eagerly load role for slug check
        // ---------------
        $user = User::where('email', $request->email)->with('role')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput(['email' => $request->email]);
        }

        // ---------------
        // Check account is active
        // ---------------
        if ($user->status !== 'active') {
            return back()
                ->withErrors(['email' => 'Your account is not active.'])
                ->withInput(['email' => $request->email]);
        }

        // ---------------
        // Restrict to admin roles only - return a generic error to avoid role enumeration
        // ---------------
        $slug = $user->role?->slug;

        if (!in_array($slug, ['super_admin', 'supervisor', 'agent'], true)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput(['email' => $request->email]);
        }

        // ---------------
        // Login verification method - configured under Settings > Login
        // Security. With none (the default) the admin signs straight in;
        // the code flow below reactivates once a method is selected.
        // ---------------
        if ((Setting::get('login_2fa_method', 'none') ?? 'none') === 'none') {
            return $this->completeLogin($request, $user, $request->boolean('remember'));
        }

        // ---------------
        // Generate a 6-digit 2FA code and persist it
        // ---------------
        $otp       = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10)->format('Y-m-d H:i:s');

        DB::table('su_users')
            ->where('id', $user->id)
            ->update(['twofa_code' => $otp, 'twofa_expires_at' => $expiresAt]);

        // ---------------
        // Send the code via SMTP.
        // On failure: clear the saved code so the admin can retry cleanly.
        // ---------------
        try {
            (new AdminEmailService())->sendLoginOtp($user, $otp);
        } catch (\Throwable $e) {
            DB::table('su_users')
                ->where('id', $user->id)
                ->update(['twofa_code' => null, 'twofa_expires_at' => null]);

            return back()
                ->withErrors(['email' => 'Failed to send verification code: ' . $e->getMessage()])
                ->withInput(['email' => $request->email]);
        }

        // ---------------
        // Store partial auth state (including remember preference) and redirect to 2FA page
        // ---------------
        $request->session()->put('admin_twofa_user_id', $user->id);
        $request->session()->put('admin_twofa_remember', $request->boolean('remember'));

        return redirect()->route('manage.auth.verify2fa')
            ->with('success', 'Verification code sent to ' . $user->email . '. Check your inbox.');
    }

    // ---------------
    // Finish authentication: log in, regenerate the session, apply the
    // 24-hour sliding remember window and open the dashboard. Shared by
    // the no-2FA path and kept consistent with verify().
    // ---------------
    private function completeLogin(Request $request, User $user, bool $remember): RedirectResponse
    {
        Auth::login($user, $remember);
        $request->session()->regenerate();

        $user->forceFill(['last_login_at' => now()])->save();

        if ($remember) {
            $request->session()->put('session_expires_at', now()->addHours(24)->toDateTimeString());
            $request->session()->put('session_remember_minutes', 1440);
        }

        return redirect()->route('manage.dashboard.index');
    }

    // ---------------
    // Show the 2FA verification form.
    // Requires the partial auth session key set during login.
    // ---------------
    public function showVerify(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('admin_twofa_user_id')) {
            return redirect()->route('manage.auth.login');
        }

        return view('manage.auth.verify-2fa');
    }

    // ---------------
    // Verify the 2FA code and complete admin authentication.
    // ---------------
    public function verify(AdminVerifyTwoFaRequest $request): RedirectResponse
    {
        $userId = $request->session()->get('admin_twofa_user_id');

        if (!$userId) {
            return redirect()->route('manage.auth.login');
        }

        $user = User::with('role')->find($userId);

        if (!$user) {
            $request->session()->forget('admin_twofa_user_id');
            return redirect()->route('manage.auth.login')
                ->withErrors(['code' => 'Session expired. Please log in again.']);
        }

        // ---------------
        // Fetch the stored code and verify it has not expired
        // ---------------
        $record = DB::table('su_users')
            ->where('id', $userId)
            ->select('twofa_code', 'twofa_expires_at')
            ->first();

        $codeMatch = $record && (string) $record->twofa_code === $request->code;
        $notExpired = $record?->twofa_expires_at && now()->lte($record->twofa_expires_at);

        if (!$codeMatch || !$notExpired) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // ---------------
        // Consume the code - clear it so it cannot be reused
        // ---------------
        DB::table('su_users')
            ->where('id', $userId)
            ->update(['twofa_code' => null, 'twofa_expires_at' => null]);

        $remember = (bool) $request->session()->pull('admin_twofa_remember', false);
        $request->session()->forget('admin_twofa_user_id');

        // ---------------
        // Fully authenticate the admin and regenerate session
        // ---------------
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // ---------------
        // If remembered, enforce a 24-hour sliding session for admins
        // ---------------
        if ($remember) {
            $request->session()->put('session_expires_at', now()->addHours(24)->toDateTimeString());
            $request->session()->put('session_remember_minutes', 1440);
        }

        return redirect()->route('manage.dashboard.index');
    }

    // ---------------
    // Sign the admin out and invalidate the session.
    // ---------------
    public function logout(Request $request): RedirectResponse
    {
        // Logging out is a definite, unambiguous sign-off, the softphone
        // toggle stays green forever otherwise, since nothing else in
        // the flow ever writes an offline row for someone who simply
        // ends their session instead of clicking Offline first
        if ($user = Auth::user()) {

        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('manage.auth.login');
    }
}
