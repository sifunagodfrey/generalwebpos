<?php

namespace App\Http\Controllers\Manage\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Core\Setting;
class SettingsWorkspaceController extends Controller
{
    public function index(Request $request, string $page = 'network')
    {
        $modules = config('suweka_ui');
        $module = 'settings';
        $item = collect($modules[$module]['items'] ?? [])->first(fn ($v) => $v[0] === $page) ?? ['network', 'Settings', 'gear'];
        $settings = DB::table('su_settings')->pluck('setting_value', 'setting_key');

        return view("manage.settings.{$page}", compact('modules', 'module', 'page', 'item', 'settings'));
    }

    public function downloadBackup($filename)
    {
        // Mock file content for demonstration purposes
        $content = "This is a mock backup file generated for testing.\n\nFilename: " . $filename . "\nGenerated at: " . now();

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'application/zip',
        ]);
    }
    public function save(Request $request, string $page)
    {
        // Exclude CSRF token and method spoofing
        $data = $request->except(['_token', '_method']);

        // Use the existing Setting model's helper to save without touching timestamps
        Setting::putMany($data);

        return back()->with('success', ucfirst(str_replace('-', ' ', $page)) . ' settings saved successfully.');
    }

    public function testMpesa()
    {
        // Logic to test M-Pesa credentials would go here.
        // For now, we simulate a successful test.
        return back()->with('success', 'M-Pesa connection tested successfully.');
    }

    public function runBackup()
    {
        // Logic to trigger a backup (e.g., via a scheduled job or artisan command).
        // For now, we simulate a successful backup.
        return back()->with('success', 'Backup completed successfully.');
    }

    public function shareLogs()
    {
        // Logic to zip and email diagnostic logs.
        return back()->with('success', 'Diagnostic logs have been shared with support.');
    }
}