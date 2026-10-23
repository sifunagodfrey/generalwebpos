<?php
namespace App\Policies\Pos;
use App\Models\User;
class BrandPolicy {
    public function viewAny(User $user): bool { return $user->hasPermission('pos.brands.view'); }
    public function view(User $user): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasPermission('pos.brands.manage'); }
    public function update(User $user): bool { return $user->hasPermission('pos.brands.manage'); }
    public function delete(User $user): bool { return $user->hasPermission('pos.brands.manage'); }
}
