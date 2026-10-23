<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PosDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PosRolePermissionSeeder::class,
            PosSampleProductsSeeder::class,
        ]);
    }
}
