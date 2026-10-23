<?php
namespace App\Policies\Pos;
use App\Models\User;
class CustomerPolicy {
    public function viewAny(User $user): bool { return $user->hasPermission('pos.customers.view'); }
    public function view(User $user): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasPermission('pos.customers.manage'); }
    public function update(User $user): bool { return $user->hasPermission('pos.customers.manage'); }
    public function delete(User $user): bool { return $user->hasPermission('pos.customers.manage'); }
}
