<?php

/* -------------------
   Demo data.

   Fills in the configuration screens with realistic sample content so a
   fresh install isn't just empty states everywhere: business hours,
   queues, a two step IVR flow, a handful of contacts, and two demo staff
   accounts (agent + supervisor) since the wallboard and dashboard both
   need at least one of each role to show anything.

   Safe to run more than once, everything goes through updateOrCreate or
   checks for an existing row first.
------------------- */

namespace Database\Seeders;

use App\Models\CallCenter\Contact;
use App\Models\CallCenter\IvrMenu;
use App\Models\CallCenter\Queue;
use App\Models\CallCenter\AgentStatusLog;
use App\Models\Settings\BusinessHour;
use App\Models\User;
use App\Models\Users\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBusinessHours();
        $queues = $this->seedQueues();
        $this->seedContacts();
        $this->seedIvrFlow($queues);
        $this->seedDemoStaff($queues);
    }

    private function seedBusinessHours(): void
    {
        $hours = [
            'monday'    => ['08:00', '17:00', false],
            'tuesday'   => ['08:00', '17:00', false],
            'wednesday' => ['08:00', '17:00', false],
            'thursday'  => ['08:00', '17:00', false],
            'friday'    => ['08:00', '17:00', false],
            'saturday'  => ['09:00', '13:00', false],
            'sunday'    => [null, null, true],
        ];

        foreach ($hours as $day => [$open, $close, $closed]) {
            BusinessHour::updateOrCreate(
                ['day_of_week' => $day],
                ['open_time' => $open, 'close_time' => $close, 'is_closed' => $closed]
            );
        }
    }

    private function seedQueues(): array
    {
        $queues = [
            ['name' => 'Sales', 'department' => 'Sales', 'skip_ivr_if_agent_available' => true],
            ['name' => 'Support', 'department' => 'Customer Support', 'skip_ivr_if_agent_available' => false],
            ['name' => 'Billing', 'department' => 'Finance', 'skip_ivr_if_agent_available' => false],
        ];

        $created = [];

        foreach ($queues as $data) {
            $created[$data['name']] = Queue::updateOrCreate(['name' => $data['name']], $data);
        }

        return $created;
    }

    private function seedContacts(): void
    {
        // A creator is needed for the created_by column, any existing account will do
        $creator = User::first();

        $contacts = [
            ['first_name' => 'Wanjiru', 'last_name' => 'Kamau', 'phone_number' => '+254712345001', 'company_name' => 'Kamau Hardware'],
            ['first_name' => 'Otieno', 'last_name' => 'Ochieng', 'phone_number' => '+254712345002', 'company_name' => 'Lakeview Builders'],
            ['first_name' => 'Amina', 'last_name' => 'Hassan', 'phone_number' => '+254712345003', 'company_name' => null],
            ['first_name' => 'Kiptoo', 'last_name' => 'Rono', 'phone_number' => '+254712345004', 'company_name' => 'Rift Valley Roofing'],
            ['first_name' => 'Grace', 'last_name' => 'Mwangi', 'phone_number' => '+254712345005', 'company_name' => 'Mwangi & Sons'],
        ];

        foreach ($contacts as $data) {
            $data['created_by'] = $creator?->id;
            Contact::updateOrCreate(['phone_number' => $data['phone_number']], $data);
        }
    }

    private function seedIvrFlow(array $queues): void
    {
        $language = IvrMenu::updateOrCreate(
            ['name' => 'Language Menu'],
            ['language' => 'English', 'is_active' => true]
        );

        $clientType = IvrMenu::updateOrCreate(
            ['name' => 'Existing or New Client'],
            ['language' => 'English', 'is_active' => true]
        );

        if ($language->options()->count() === 0) {
            $language->options()->createMany([
                ['key_pressed' => '1', 'label' => 'Continue in English', 'action_type' => 'go_to_menu', 'target_menu_id' => $clientType->id],
                ['key_pressed' => '2', 'label' => 'Continue in Swahili', 'action_type' => 'go_to_menu', 'target_menu_id' => $clientType->id],
            ]);
        }

        if ($clientType->options()->count() === 0 && isset($queues['Support'], $queues['Sales'])) {
            $clientType->options()->createMany([
                ['key_pressed' => '1', 'label' => 'Existing client', 'action_type' => 'route_to_queue', 'target_queue_id' => $queues['Support']->id],
                ['key_pressed' => '2', 'label' => 'New client', 'action_type' => 'route_to_queue', 'target_queue_id' => $queues['Sales']->id],
            ]);
        }
    }

    private function seedDemoStaff(array $queues): void
    {
        $agentRole      = UserRole::where('slug', 'agent')->first();
        $supervisorRole = UserRole::where('slug', 'supervisor')->first();

        if (! $agentRole || ! $supervisorRole) {
            $this->command->warn('Agent or Supervisor role not found, run RolePermissionSeeder first. Skipping demo staff.');

            return;
        }

        $demoAccounts = [
            ['email' => 'demo.agent@duramaxmabati.com', 'first_name' => 'Demo', 'last_name' => 'Agent', 'role' => $agentRole, 'status' => 'online', 'queue' => 'Support'],
            ['email' => 'demo.supervisor@duramaxmabati.com', 'first_name' => 'Demo', 'last_name' => 'Supervisor', 'role' => $supervisorRole, 'status' => 'online', 'queue' => null],
        ];

        $generatedPasswords = [];

        foreach ($demoAccounts as $account) {
            $user = User::where('email', $account['email'])->first();

            if (! $user) {
                $password = Str::password(16);

                $user = User::create([
                    'first_name' => $account['first_name'],
                    'last_name'  => $account['last_name'],
                    'email'      => $account['email'],
                    'password'   => Hash::make($password),
                    'status'     => 'active',
                ]);

                $generatedPasswords[$account['email']] = $password;
            }

            $user->roles()->syncWithoutDetaching([$account['role']->id => ['assigned_at' => now()]]);

            if ($account['queue'] && isset($queues[$account['queue']])) {
                $queues[$account['queue']]->agents()->syncWithoutDetaching([$user->id]);
            }

            // A fresh "online" entry so the wallboard and dashboard show
            // this account as available right away
            AgentStatusLog::create([
                'user_id'    => $user->id,
                'status'     => $account['status'],
                'changed_at' => now(),
            ]);
        }

        if ($generatedPasswords) {
            $this->command->newLine();
            $this->command->info('Demo staff accounts created, save these passwords now:');

            foreach ($generatedPasswords as $email => $password) {
                $this->command->line("  {$email}  ->  {$password}");
            }

            $this->command->newLine();
        }
    }
}
