# Run from the Laravel project root AFTER backing up your project.
# Moves obsolete migration PHP files out of Laravel's active migrations folder.
$ErrorActionPreference = 'Stop'
$source = Join-Path (Get-Location) 'database\migrations'
$archive = Join-Path (Get-Location) 'database\archived_callcenter_migrations'
New-Item -ItemType Directory -Path $archive -Force | Out-Null
$names = @(
    '2025_01_01_000008_create_su_voice_provider_credentials_table.php'
    '2025_01_01_000009_create_su_ivr_menus_table.php'
    '2025_01_01_000010_create_su_queues_table.php'
    '2025_01_01_000011_create_su_ivr_options_table.php'
    '2025_01_01_000012_create_su_contacts_table.php'
    '2025_01_01_000013_create_su_queue_agents_table.php'
    '2025_01_01_000014_create_su_calls_table.php'
    '2025_01_01_000015_create_su_call_events_table.php'
    '2025_01_01_000016_create_su_call_recordings_table.php'
    '2025_01_01_000017_create_su_call_notes_table.php'
    '2025_01_01_000018_create_su_agent_status_logs_table.php'
    '2025_01_01_000019_create_su_agent_daily_stats_table.php'
    '2025_01_01_000020_create_su_csat_ratings_table.php'
    '2025_01_01_000021_create_su_business_hours_table.php'
    '2025_01_01_000022_create_su_hold_media_table.php'
    '2025_02_01_000001_add_provider_session_id_to_su_calls_table.php'
    '2025_02_01_000002_add_current_ivr_menu_id_to_su_calls_table.php'
    '2025_02_01_000003_add_greeting_text_to_su_ivr_menus_table.php'
    '2025_02_01_000004_add_tried_agent_ids_to_su_calls_table.php'
    '2025_02_01_000005_add_direction_to_su_calls_table.php'
    '2025_02_01_000007_add_follow_up_agent_id_to_su_call_notes_table.php'
    '2025_02_01_000008_add_normalized_phone_to_su_contacts_table.php'
    '2025_02_01_000010_add_ivr_path_to_su_calls_table.php'
    '2025_02_01_000011_add_ivr_rating_support.php'
    '2025_02_01_000012_add_purpose_to_su_calls_table.php'
)
foreach ($name in $names) {
    $original = Join-Path $source $name
    if (Test-Path -LiteralPath $original) {
        Move-Item -LiteralPath $original -Destination $archive
        Write-Host "Archived $name"
    }
}
Write-Host 'Existing migration HISTORY is intentionally untouched.'
