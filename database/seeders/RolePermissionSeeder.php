<?php

/* -------------------
   Roles and permissions.

   Seeds the three roles named in the project plan (Agent, Supervisor,
   Super Admin) plus the permission set that actually gates something in
   the app today. More permissions get added here as each call center
   module (queues, IVR, contacts, calls) ships its own screens.

   Safe to run more than once, everything goes through updateOrCreate so a
   reseed updates descriptions instead of duplicating rows.
------------------- */

namespace Database\Seeders;

use App\Models\Users\Permission;
use App\Models\Users\UserRole;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = $this->seedPermissions();
        $roles = $this->seedRoles();

        // Super Admin also bypasses every check in code, but a real row
        // here keeps the permission matrix screen showing everything ticked
        $roles['super_admin']->permissions()->sync($permissions->pluck('id'));

        $roles['supervisor']->permissions()->sync(
            $permissions->whereIn('slug', ['wallboard.view', 'recordings.view', 'reports.view'])->pluck('id')
        );

        // Agent starts with no admin-panel permissions, their own dashboard
        // is not permission gated. A Super Admin can grant individual
        // permissions per agent later, per the project plan.
        $roles['agent']->permissions()->sync([]);
    }

    private function seedPermissions()
    {
        $permissions = [
            ['name' => 'Manage staff, roles and permissions', 'slug' => 'users.manage', 'module' => 'system', 'description' => 'Create, edit and deactivate staff accounts, and manage roles and permissions.'],
            ['name' => 'View audit log', 'slug' => 'view_system_logs', 'module' => 'system', 'description' => 'View the system wide audit trail.'],
            ['name' => 'Manage settings', 'slug' => 'manage_settings', 'module' => 'settings', 'description' => 'Change company details, branding, email and security settings.'],
            ['name' => 'Manage voice provider', 'slug' => 'voice_provider.manage', 'module' => 'settings', 'description' => 'Configure the voice provider connection.'],
            ['name' => 'Manage media library', 'slug' => 'media', 'module' => 'media', 'description' => 'Upload and manage files in the media library.'],
            ['name' => 'View live wallboard', 'slug' => 'wallboard.view', 'module' => 'calls', 'description' => 'See live agent status and queue activity.'],
            ['name' => 'View call recordings', 'slug' => 'recordings.view', 'module' => 'calls', 'description' => 'Listen to and download recorded calls.'],
            ['name' => 'View reports', 'slug' => 'reports.view', 'module' => 'calls', 'description' => 'View agent performance and call reports.'],
            ['name' => 'Manage queues', 'slug' => 'queues.manage', 'module' => 'calls', 'description' => 'Create and edit call queues.'],
            ['name' => 'Manage IVR menus', 'slug' => 'ivr.manage', 'module' => 'calls', 'description' => 'Build and edit IVR menu flows.'],
            ['name' => 'Manage contacts', 'slug' => 'contacts.manage', 'module' => 'calls', 'description' => 'Add and edit saved contacts.'],
        ];

        $ids = collect($permissions)->map(function ($permission) {
            return Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        });

        return $ids;
    }

    private function seedRoles(): array
    {
        $roles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full access. Manages users, roles, permissions, IVR menus, queues, voice provider credentials, branding and system settings.',
                'is_system' => true,
            ],
            'supervisor' => [
                'name' => 'Supervisor',
                'slug' => 'supervisor',
                'description' => 'Sees the live wallboard and can view call recordings, reports and team performance.',
                'is_system' => true,
            ],
            'agent' => [
                'name' => 'Agent',
                'slug' => 'agent',
                'description' => 'Handles calls, manages their own contacts, and logs call notes and follow ups.',
                'is_system' => true,
            ],
        ];

        $created = [];

        foreach ($roles as $slug => $attributes) {
            $created[$slug] = UserRole::updateOrCreate(['slug' => $slug], $attributes);
        }

        return $created;
    }
}
