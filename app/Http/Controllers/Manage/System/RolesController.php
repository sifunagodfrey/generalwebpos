<?php

/* ------------------- Roles ------------------- */

namespace App\Http\Controllers\Manage\System;

use App\Http\Controllers\Manage\ManageController;
use App\Models\Users\Permission;
use App\Models\Users\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RolesController extends ManageController
{
    public function index()
    {
        return view('manage.system.roles.index', [
            'roles' => UserRole::withCount(['permissions', 'users'])->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('manage.system.roles.create', ['permissions' => Permission::grouped()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:80'],
            'description'   => ['nullable', 'string', 'max:255'],
            'permissions'   => ['array'],
            'permissions.*' => ['integer', 'exists:su_permissions,id'],
        ]);

        $role = UserRole::create([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name'], '_'),
            'description' => $data['description'] ?? null,
            'is_system'   => 0,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        $this->logAction('created', 'roles', $role);

        return redirect()->route('manage.roles.index')->with('success', 'Role created.');
    }

    public function edit(int $id)
    {
        return view('manage.system.roles.edit', [
            'role'        => UserRole::with('permissions')->findOrFail($id),
            'permissions' => Permission::grouped(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $role = UserRole::findOrFail($id);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
        ]);

        $role->update(['name' => $data['name'], 'description' => $data['description'] ?? null]);

        /* The super administrator role always holds everything */
        if ($role->slug !== 'super_admin') {
            $role->permissions()->sync($data['permissions'] ?? []);
        }

        $this->logAction('updated', 'roles', $role);

        return back()->with('success', 'Role saved.');
    }

    public function destroy(int $id)
    {
        $role = UserRole::withCount('users')->findOrFail($id);

        if ($role->is_system) {
            return back()->with('error', 'This is a system role and cannot be deleted.');
        }

        if ($role->users_count > 0) {
            return back()->with('error', 'Move the ' . $role->users_count . ' accounts on this role first.');
        }

        $role->delete();

        return back()->with('success', 'Role removed.');
    }
}
