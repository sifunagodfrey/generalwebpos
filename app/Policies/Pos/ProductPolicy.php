<?php
namespace App\Policies\Pos;
use App\Models\User;
class ProductPolicy {
    public function viewAny(User $user): bool { return $user->hasPermission('pos.products.view'); }
    public function view(User $user): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasPermission('pos.products.create'); }
    public function update(User $user): bool { return $user->hasPermission('pos.products.update'); }
    public function delete(User $user): bool { return $user->hasPermission('pos.products.delete'); }
}
