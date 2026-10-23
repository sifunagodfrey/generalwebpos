<?php
namespace App\Http\Controllers\Manage\Settings\Licensing;

use App\Http\Controllers\Controller;
use App\Services\Licensing\PosLicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosLicenseController extends Controller
{
    public function show(Request $request, PosLicenseService $license)
    {
        return view('manage.settings.licensing.activate', [
            'active' => $license->isActive(),
        ]);
    }

    public function activate(Request $request, PosLicenseService $license)
    {
        abort_unless($request->user()?->hasPermission('pos.settings.manage'), 403,
            'Only an authorized POS administrator can activate this installation.');

        $data = $request->validate([
            'license_key' => ['required', 'string', 'min:8', 'max:150'],
        ]);

        $user = $request->user();
        $name = trim(implode(' ', array_filter([$user->first_name ?? '', $user->last_name ?? ''])));
        $email = trim((string) $user->email);
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['license_key' =>
                'Your signed-in account needs a name and a valid email before activation.']);
        }

        // The licensing API requires registrant name/email. The web UI only asks for
        // the key because these fields are sourced from the signed-in Laravel user.
        $license->activate([
            'license_key' => trim($data['license_key']),
            'name' => $name,
            'email' => $email,
        ]);

        // License activation must not grant privileges. Only link a user who already
        // has sales permission. Existing records/roles/passwords stay unchanged.
        if ($user->hasPermission('pos.sales.create') &&
            !DB::table('su_staff')->where('user_id', $user->id)->exists()) {
            DB::table('su_staff')->insert([
                'first_name' => $user->first_name ?: 'Staff',
                'last_name' => $user->last_name ?: '',
                'role' => 'cashier',
                'pin_hash' => Hash::make(bin2hex(random_bytes(24))),
                'is_active' => 1,
                'user_id' => $user->id,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('manage.license.show')
            ->with('status', 'License activated successfully. Operations remain subject to staff permissions and shift requirements.');
    }
}
