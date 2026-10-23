<?php
namespace App\Services\Pos;
use Illuminate\Support\Facades\Auth;
class PosAuthorization {
    public function require(string $permission): void {
        $user = Auth::user();
        abort_unless($user && $user->hasPermission($permission), 403, 'POS permission denied.');
    }
}
