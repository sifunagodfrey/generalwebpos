<?php

/* -------------------
   Root seeder. Order matters here: roles and permissions must exist before
   an account can be given a role, and demo data depends on both roles and
   at least one account already existing.
------------------- */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
