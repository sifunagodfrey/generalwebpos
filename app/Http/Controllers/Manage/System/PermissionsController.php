<?php

/*
 * ---------------------------------------------------------------------------
 * Permission matrix.
 *
 * Roles down the side, permissions across, saved in one submit. The super
 * administrator column is fixed because that role must always hold everything.
 * ---------------------------------------------------------------------------
 */

namespace App\Http\Controllers\Manage\System;

use App\Http\Controllers\Manage\ManageController;
use App\Models\Users\Permission;
use App\Models\Users\UserRole;
use Illuminate\Http\Request;

class PermissionsController extends ManageController
{
    public function index()
    {
        return view('manage.system.permissions.index', [
            'roles'       => UserRole::with('permissions')->where('slug', '!=', 'customer')->orderBy('id')->get(),
            'permissions' => Permission::grouped(),
        ]);
    }

    public function update(Request $request)
    {
        foreach ((array) $request->input('roles', []) as $roleId => $permissionIds) {

            $role = UserRole::find($roleId);

            if (! $role || $role->slug === 'super_admin') {
                continue;
            }

            $role->permissions()->sync(array_map('intval', (array) $permissionIds));
        }

        $this->logAction('updated', 'permissions', null, 'Permission matrix changed');

        return back()->with('success', 'Permissions saved.');
    }
}
