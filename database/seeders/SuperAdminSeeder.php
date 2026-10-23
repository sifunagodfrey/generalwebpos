<?php

/* -------------------
   Initial Super Admin accounts.

   Creates the three accounts the client asked for and gives each one the
   Super Admin role. A password is only generated and printed the first
   time an account is created, reseeding never overwrites a password that
   may already have been changed.
------------------- */

namespace Database\Seeders;

use App\Models\User;
use App\Models\Users\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = UserRole::where('slug', 'super_admin')->first();

        if (! $superAdminRole) {
            $this->command->warn('Super Admin role not found, run RolePermissionSeeder first. Skipping.');

            return;
        }

        $accounts = [
            ['email' => 'sifuna.godfreyw@gmail.com', 'first_name' => 'Sifuna', 'last_name' => 'Godfrey'],
            ['email' => 'info@duramaxmabati.com', 'first_name' => 'Call Center', 'last_name' => null],
            ['email' => 'duramaxmabati@gmail.com', 'first_name' => 'Collince', 'last_name' => 'Ouma'],
        ];

        $generatedPasswords = [];

        foreach ($accounts as $account) {
            $user = User::where('email', $account['email'])->first();

            if ($user) {
                $this->command->info("Already exists, leaving password untouched: {$account['email']}");

                $user->update([
                    'first_name' => $account['first_name'],
                    'last_name' => $account['last_name'],
                ]);
            } else {
                $password = Str::password(16);

                $user = User::create([
                    'first_name' => $account['first_name'],
                    'last_name' => $account['last_name'],
                    'email' => $account['email'],
                    'password' => Hash::make($password),
                    'status' => 'active',
                ]);

                $generatedPasswords[$account['email']] = $password;
            }

            $user->roles()->syncWithoutDetaching([
                $superAdminRole->id => ['assigned_at' => now()],
            ]);
        }

        if ($generatedPasswords) {
            $this->command->newLine();
            $this->command->info('Generated passwords, save these now, they will not be shown again:');

            foreach ($generatedPasswords as $email => $password) {
                $this->command->line("  {$email}  ->  {$password}");
            }

            $this->command->newLine();
        }
    }
}
