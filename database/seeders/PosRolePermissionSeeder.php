<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class PosRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['su_roles', 'su_permissions', 'su_role_permissions', 'su_user_roles', 'su_users'] as $table) {
            if (!Schema::hasTable($table)) {
                throw new RuntimeException("Required existing authentication table {$table} does not exist.");
            }
        }

        $matrix = [
            'dashboard' => ['view'],
            'products' => ['view', 'create', 'update', 'delete', 'import', 'export', 'manage_prices'],
            'categories' => ['view', 'manage'],
            'brands' => ['view', 'manage'],
            'inventory' => ['view', 'receive', 'adjust', 'count', 'transfer', 'view_costs'],
            'sales' => ['view', 'create', 'hold', 'discount', 'void', 'refund', 'reprint'],
            'payments' => ['view', 'receive', 'refund'],
            'customers' => ['view', 'manage', 'credit'],
            'suppliers' => ['view', 'manage', 'settle'],
            'purchases' => ['view', 'create', 'approve', 'receive', 'cancel'],
            'documents' => ['view', 'create', 'update', 'convert', 'export'],
            'shifts' => ['view', 'open', 'close', 'review'],
            'cashbox' => ['view', 'manage', 'approve'],
            'mpesa' => ['view', 'initiate', 'reconcile', 'configure'],
            'reports' => ['view', 'sales', 'inventory', 'financial', 'export'],
            'staff' => ['view', 'manage', 'manage_roles'],
            'businesses' => ['view', 'manage'],
            'branches' => ['view', 'manage'],
            'settings' => ['view', 'manage'],
            'audit' => ['view'],
            'backups' => ['view', 'create', 'restore'],
            'licensing' => ['view', 'manage'],
        ];

        $roleGrants = [
            'pos_owner' => ['*'],
            'pos_manager' => [
                'dashboard.*', 'products.*', 'categories.*', 'brands.*', 'inventory.*',
                'sales.*', 'payments.*', 'customers.*', 'suppliers.*', 'purchases.*',
                'documents.*', 'shifts.*', 'cashbox.*', 'mpesa.view', 'mpesa.initiate',
                'mpesa.reconcile', 'reports.*', 'staff.view', 'branches.view',
                'settings.view', 'audit.view', 'backups.view',
            ],
            'pos_supervisor' => [
                'dashboard.view', 'products.view', 'categories.view', 'brands.view',
                'inventory.view', 'inventory.receive', 'inventory.count',
                'sales.view', 'sales.create', 'sales.hold', 'sales.discount',
                'sales.refund', 'sales.reprint', 'payments.view', 'payments.receive',
                'customers.view', 'customers.manage', 'suppliers.view',
                'purchases.view', 'documents.view', 'shifts.view', 'shifts.open',
                'shifts.close', 'cashbox.view', 'mpesa.view', 'reports.view',
                'reports.sales', 'staff.view',
            ],
            'pos_cashier' => [
                'dashboard.view', 'products.view', 'categories.view', 'inventory.view',
                'sales.view', 'sales.create', 'sales.hold', 'sales.reprint',
                'payments.view', 'payments.receive', 'customers.view', 'customers.manage',
                'shifts.view', 'shifts.open', 'shifts.close', 'mpesa.view', 'mpesa.initiate',
            ],
            'pos_inventory_clerk' => [
                'dashboard.view', 'products.view', 'products.create', 'products.update',
                'categories.view', 'brands.view', 'inventory.view', 'inventory.receive',
                'inventory.count', 'suppliers.view', 'purchases.view', 'purchases.receive',
            ],
            'pos_purchasing_officer' => [
                'dashboard.view', 'products.view', 'inventory.view', 'inventory.receive',
                'suppliers.view', 'suppliers.manage', 'purchases.view', 'purchases.create',
                'purchases.receive', 'documents.view',
            ],
            'pos_accountant' => [
                'dashboard.view', 'products.view', 'inventory.view', 'inventory.view_costs',
                'sales.view', 'payments.view', 'customers.view', 'customers.credit',
                'suppliers.view', 'suppliers.settle', 'purchases.view',
                'documents.view', 'documents.export', 'shifts.view', 'shifts.review',
                'cashbox.view', 'cashbox.approve', 'mpesa.view', 'mpesa.reconcile',
                'reports.view', 'reports.sales', 'reports.inventory', 'reports.financial',
                'reports.export', 'audit.view',
            ],
            'pos_auditor' => [
                'dashboard.view', 'products.view', 'inventory.view', 'sales.view',
                'payments.view', 'customers.view', 'suppliers.view', 'purchases.view',
                'documents.view', 'shifts.view', 'cashbox.view', 'mpesa.view',
                'reports.view', 'reports.sales', 'reports.inventory', 'reports.financial',
                'reports.export', 'audit.view',
            ],
        ];

        $roleDescriptions = [
            'pos_owner' => ['POS Owner', 'Owner of the business; all POS permissions.'],
            'pos_manager' => ['POS Manager', 'Manages store operations, stock, purchasing, and staff oversight.'],
            'pos_supervisor' => ['POS Supervisor', 'Supervises daily checkout, inventory and shifts.'],
            'pos_cashier' => ['POS Cashier', 'Processes sales, receives payments and operates shifts.'],
            'pos_inventory_clerk' => ['POS Inventory Clerk', 'Maintains product catalog and stock records.'],
            'pos_purchasing_officer' => ['POS Purchasing Officer', 'Maintains suppliers and purchasing documents.'],
            'pos_accountant' => ['POS Accountant', 'Oversees finances, reports, reconciliations and audit trails.'],
            'pos_auditor' => ['POS Auditor', 'Read-only operational and financial visibility.'],
        ];

        DB::transaction(function () use ($matrix, $roleGrants, $roleDescriptions): void {
            $permissionIds = [];
            foreach ($matrix as $module => $actions) {
                foreach ($actions as $action) {
                    $permissionKey = "{$module}.{$action}";
                    $slug = "pos.{$permissionKey}";
                    $existing = DB::table('su_permissions')->where('slug', $slug)->first();
                    if ($existing) {
                        $permissionIds[$slug] = $existing->id;
                        continue;
                    }
                    $permissionIds[$slug] = DB::table('su_permissions')->insertGetId([
                        'name' => 'POS: ' . ucwords(str_replace('_', ' ', "{$action} {$module}")),
                        'slug' => $slug,
                        'module' => $module,
                        'description' => "POS access: {$action} {$module}.",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Keep all existing role IDs, slugs, and su_user_roles assignments unchanged.
            foreach ($roleGrants as $slug => $patterns) {
                $existing = DB::table('su_roles')->where('slug', $slug)->first();
                if ($existing) {
                    $roleId = $existing->id;
                } else {
                    [$name, $description] = $roleDescriptions[$slug];
                    $roleId = DB::table('su_roles')->insertGetId([
                        'name' => $name, 'slug' => $slug,
                        'description' => $description, 'is_system' => 1,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }

                foreach ($permissionIds as $permissionSlug => $permissionId) {
                    $permissionKey = substr($permissionSlug, 4); // Remove the 'pos.' namespace for role matrix matching.
                    $allowed = in_array('*', $patterns, true)
                        || in_array($permissionKey, $patterns, true)
                        || in_array(explode('.', $permissionKey)[0] . '.*', $patterns, true);
                    if (!$allowed) continue;
                    if (!DB::table('su_role_permissions')->where('role_id', $roleId)
                        ->where('permission_id', $permissionId)->exists()) {
                        DB::table('su_role_permissions')->insert([
                            'role_id' => $roleId, 'permission_id' => $permissionId,
                            'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Retain the existing super_admin role and users; grant POS access to it.
            $superAdminId = DB::table('su_roles')->where('slug', 'super_admin')->value('id');
            if ($superAdminId !== null) {
                foreach ($permissionIds as $permissionId) {
                    if (!DB::table('su_role_permissions')->where('role_id', $superAdminId)
                        ->where('permission_id', $permissionId)->exists()) {
                        DB::table('su_role_permissions')->insert([
                            'role_id' => $superAdminId, 'permission_id' => $permissionId,
                            'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }
            }
        });
    }
}
