<?php
namespace App\Policies\Pos;
use App\Models\User;
class UnitOfMeasurePolicy {
    public function viewAny(User $user): bool { return $user->hasPermission('pos.inventory.view'); }
    public function view(User $user): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasPermission('pos.inventory.create'); }
    public function update(User $user): bool { return $user->hasPermission('pos.inventory.update'); }
    public function delete(User $user): bool { return $user->hasPermission('pos.inventory.delete'); }
}
