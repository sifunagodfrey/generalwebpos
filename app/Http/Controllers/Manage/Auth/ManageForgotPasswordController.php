<?php

namespace App\Http\Controllers\Manage\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ManageForgotPasswordController extends Controller
{
    // ---------------
    // Step 1 - Show the email request form
    // ---------------
    public function showRequest()
    {
        return view('manage.auth.forgot-password');
    }

    // ---------------
    // Step 1 - Generate OTP and send to the admin's email.
    // Only processes admin/super_admin accounts; silently passes through
    // for unrecognised emails to prevent account enumeration.
    // ---------------
    public function sendCode(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        // ---------------
        // Rate-limit: 5 attempts per IP per minute
        // ---------------
        $throttleKey = 'manage-pwd-reset:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['email' => "Too many attempts. Try again in {$seconds} seconds."]);
        }
        RateLimiter::hit($throttleKey, 60);

        $user = User::with('role')
            ->where('email', $request->email)
            ->where('status', 'active')
            ->first();

        if ($user && in_array($user->role?->slug, ['super_admin', 'supervisor', 'agent'], true)) {
            $otp       = random_int(100000, 999999);
            $expiresAt = now()->addMinutes(15)->format('Y-m-d H:i:s');

            DB::table('su_users')
                ->where('id', $user->id)
                ->update([
                    'password_reset_code'       => (string) $otp,
                    'password_reset_expires_at' => $expiresAt,
                ]);

            try {
                app(AdminEmailService::class)->sendPasswordResetOtp($user, $otp);
            } catch (\Throwable $e) {
                Log::warning('ManageForgotPasswordController: email send failed', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
                return back()->withErrors(['email' => 'Failed to send reset code: ' . $e->getMessage()]);
            }

            $request->session()->put('manage_pwd_reset_user_id', $user->id);
        }

        $request->session()->put('manage_pwd_reset_email', $request->email);

        return redirect()->route('manage.auth.forgot-password.verify');
    }

    // ---------------
    // Step 2 - Show the code entry form
    // ---------------
    public function showVerify(Request $request)
    {
        if (! $request->session()->has('manage_pwd_reset_email')) {
            return redirect()->route('manage.auth.forgot-password');
        }

        return view('manage.auth.forgot-password-verify', [
            'email' => $request->session()->get('manage_pwd_reset_email'),
        ]);
    }

    // ---------------
    // Step 2 - Validate the OTP code
    // ---------------
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $userId = $request->session()->get('manage_pwd_reset_user_id');

        if (! $userId) {
            return redirect()->route('manage.auth.forgot-password')
                ->withErrors(['code' => 'Session expired. Please request a new reset code.']);
        }

        $record = DB::table('su_users')
            ->where('id', $userId)
            ->select('password_reset_code', 'password_reset_expires_at')
            ->first();

        $codeMatch  = $record && (string) $record->password_reset_code === $request->code;
        $notExpired = $record?->password_reset_expires_at
            && now()->lte($record->password_reset_expires_at);

        if (! $codeMatch || ! $notExpired) {
            return back()->withErrors(['code' => 'The code is invalid or has expired.']);
        }

        // ---------------
        // Mark as verified - grants access to the reset form
        // ---------------
        $request->session()->put('manage_pwd_reset_verified', $userId);
        $request->session()->forget('manage_pwd_reset_user_id');

        return redirect()->route('manage.auth.forgot-password.reset');
    }

    // ---------------
    // Step 3 - Show the new password form
    // ---------------
    public function showReset(Request $request)
    {
        if (! $request->session()->has('manage_pwd_reset_verified')) {
            return redirect()->route('manage.auth.forgot-password');
        }

        return view('manage.auth.forgot-password-reset');
    }

    // ---------------
    // Step 3 - Save the new password and clear the reset token
    // ---------------
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $userId = $request->session()->get('manage_pwd_reset_verified');

        if (! $userId) {
            return redirect()->route('manage.auth.forgot-password')
                ->withErrors(['password' => 'Session expired. Please start over.']);
        }

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('manage.auth.forgot-password');
        }

        DB::table('su_users')
            ->where('id', $userId)
            ->update([
                'password'                  => Hash::make($request->password),
                'password_reset_code'       => null,
                'password_reset_expires_at' => null,
            ]);

        $request->session()->forget(['manage_pwd_reset_verified', 'manage_pwd_reset_email']);

        return redirect()->route('manage.auth.login')
            ->with('status', 'Your password has been reset. You can now log in.');
    }
}
