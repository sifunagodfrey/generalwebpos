<?php
namespace App\Policies\Pos;
use App\Models\User;
class CategoryPolicy {
    public function viewAny(User $user): bool { return $user->hasPermission('pos.categories.view'); }
    public function view(User $user): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasPermission('pos.categories.manage'); }
    public function update(User $user): bool { return $user->hasPermission('pos.categories.manage'); }
    public function delete(User $user): bool { return $user->hasPermission('pos.categories.manage'); }
}
