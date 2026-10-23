<?php
namespace App\Policies\Pos;
use App\Models\User;
class SupplierPolicy {
    public function viewAny(User $user): bool { return $user->hasPermission('pos.suppliers.view'); }
    public function view(User $user): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasPermission('pos.suppliers.manage'); }
    public function update(User $user): bool { return $user->hasPermission('pos.suppliers.manage'); }
    public function delete(User $user): bool { return $user->hasPermission('pos.suppliers.manage'); }
}
