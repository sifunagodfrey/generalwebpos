@extends('manage.layouts.workspace')
@section('title', 'Backup')
@section('heading', 'Backup')

@section('content')
<div class="max-w-4xl mx-auto pb-20 md:pb-6 space-y-6" x-data="backupPage()">
    @if(session('success')) <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-xl">{{ session('success') }}</div> @endif

    <div class="text-sm text-gray-600 space-y-2">
        <p class="font-bold text-gray-900">Protected store data</p>
        <p>C:\Users\katis\AppData\Local\Suweka\General POS</p>
        <p>Automatic backups run hourly while the POS is open. The most recent 48 automatic packages are retained. Manual backups are kept.</p>
        <p>Last successful local backup: {{ now()->format('Y-m-d\TH:i:s.u') }}</p>
        <p>Last successful second copy: Not yet created</p>
        <p>A second copy on another drive or a synced cloud folder protects against loss of this device. Without it, backups remain on this device.</p>
        
        <p class="font-medium text-gray-800 mt-4">
            Second folder: <span class="font-normal text-gray-500" x-text="selectedFolder || 'Not configured'"></span>
        </p>
        
        <input type="file" id="folderPickerInput" webkitdirectory directory class="hidden" @change="handleFolderSelect($event)">

        <button type="button" @click="document.getElementById('folderPickerInput').click()" class="border border-gray-400 text-[#713FCE] px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-gray-50 transition mt-2">
            <i class="fa-regular fa-folder-open"></i> Choose second backup folder
        </button>
        
        <p class="text-xs text-gray-500 mt-2">After choosing a folder, use Back up now to create its first copy.</p>
        <p class="text-xs text-gray-400 italic" x-show="selectedFolder">
            <i class="fa-solid fa-circle-info"></i> For security, browsers hide the full drive letter (e.g., C:\). Only the selected folder name is shown.
        </p>
    </div>

    <form action="{{ route('manage.settings.save', 'backup') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @csrf
        <input type="hidden" name="second_backup_folder" :value="selectedFolder">

        <h2 class="text-lg font-bold text-gray-900 mb-2">Online database backup</h2>
        <p class="text-sm text-gray-600 mb-6">Suweka stores the database without images. Email sends a private download link valid for 48 hours. Use a full local ZIP to transfer data with images between Android and Windows.</p>

        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="text-gray-700 block">Automatic backup to Suweka</span>
                <span class="text-xs text-gray-500">Hourly while open and online; failed uploads retry every five minutes.</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="auto_backup" value="0">
                <input type="checkbox" name="auto_backup" value="1" class="sr-only peer" @checked(($settings['auto_backup'] ?? '') === '1')>
                <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#713FCE]"></div>
            </label>
        </div>

        <div class="flex items-center gap-3 mb-4 mt-8">
            <input type="checkbox" name="email_backup" value="1" @checked(($settings['email_backup'] ?? '') === '1') class="w-5 h-5 rounded border-gray-300 text-[#713FCE] focus:ring-[#713FCE]">
            <span class="text-gray-700">Email a database backup link</span>
        </div>

        <div class="mb-6">
            <input type="email" name="backup_email" value="{{ $settings['backup_email'] ?? '' }}" placeholder="Owner backup email" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-gray-900 outline-none focus:ring-1 focus:ring-[#713FCE]">
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm">Save online settings</button>
            <button type="submit" formaction="{{ route('manage.settings.backup.run') }}" class="border border-gray-400 text-gray-700 px-6 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition">Back up database now</button>
            <button type="button" onclick="alert('Would open the online download/restore dialog.')" class="border border-gray-400 text-gray-700 px-6 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition">Download or restore online backup</button>
            <a href="#" class="text-[#713FCE] font-medium hover:underline px-2" onclick="event.preventDefault(); window.location.reload();">Refresh status</a>
        </div>
        
        <p class="text-sm text-gray-500 mt-4">Latest upload: None</p>
        <p class="text-sm text-gray-500">Email: Not sent</p>
    </form>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Back up</h2>
        <p class="text-sm text-gray-500 mb-6">Saves the database, images and saved invoices to this device, ready to download, share or restore from below.</p>
        <form action="{{ route('manage.settings.backup.run') }}" method="POST">
            @csrf
            <button type="submit" class="w-full border border-gray-400 text-[#713FCE] font-medium py-3 rounded-xl hover:bg-gray-50 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i> Back up now
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Recent backups</h2>
        <p class="text-sm text-gray-500 mb-6">Most recent first. Tap one to see when it was taken, by whom, its size, and to download, share or (for the latest one) restore it.</p>

        <div class="space-y-3">
            <!-- Backup Item 1 (Latest) -->
            <div @click="openBackupModal({
                filename: 'general_auto_backup_1793014156088589.zip',
                date: '26/10/2026',
                time: '2:29 PM',
                by: 'Unknown staff',
                size: '26.0 KB',
                is_latest: true
            })" class="flex items-center justify-between p-4 rounded-xl border border-gray-100 bg-[#f8f9fa] hover:bg-gray-100 cursor-pointer transition">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-cloud-arrow-up text-[#713FCE] text-xl"></i>
                    <div>
                        <p class="font-medium text-gray-900">general_auto_backup_1793014156088589.zip</p>
                        <p class="text-xs text-gray-500">2026-10-26 14:29:16.000</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded">Latest</span>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>
            </div>

            <!-- Backup Item 2 -->
            <div @click="openBackupModal({
                filename: 'general_auto_backup_1793002456094636.zip',
                date: '26/10/2026',
                time: '11:14 AM',
                by: 'Unknown staff',
                size: '24.8 KB',
                is_latest: false
            })" class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl"></i>
                    <div>
                        <p class="font-medium text-gray-900">general_auto_backup_1793002456094636.zip</p>
                        <p class="text-xs text-gray-500">2026-10-26 11:14:16.000</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </div>

            <!-- Backup Item 3 -->
            <div @click="openBackupModal({
                filename: 'general_auto_backup_1792998556106996.zip',
                date: '26/10/2026',
                time: '10:09 AM',
                by: 'Unknown staff',
                size: '24.8 KB',
                is_latest: false
            })" class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl"></i>
                    <div>
                        <p class="font-medium text-gray-900">general_auto_backup_1792998556106996.zip</p>
                        <p class="text-xs text-gray-500">2026-10-26 10:09:16.000</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Backup Detail Modal -->
    <div x-cloak x-show="modalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50" @click.self="modalOpen = false" x-transition.opacity>
        <div class="bg-[#f4effb] rounded-3xl w-full max-w-lg p-8 relative shadow-xl" @click.stop>
            <h3 class="text-2xl font-normal text-gray-900 mb-6" x-text="selectedBackup.filename"></h3>
            
            <div class="space-y-4 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Backed up</span>
                    <span class="font-medium text-gray-900"><span x-text="selectedBackup.date"></span>, <span x-text="selectedBackup.time"></span></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">By</span>
                    <span class="font-medium text-gray-900" x-text="selectedBackup.by"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Size</span>
                    <span class="font-medium text-gray-900" x-text="selectedBackup.size"></span>
                </div>
            </div>

            <p class="text-sm text-gray-500 mb-8" x-show="selectedBackup.is_latest">
                This is the latest backup - it can be restored from directly.
            </p>

            <div class="flex items-center justify-end gap-5">
                <!-- Working Download Link -->
                <a :href="'{{ route('manage.settings.backup.download', ':file') }}'.replace(':file', selectedBackup.filename)" 
                   class="text-[#713FCE] font-medium hover:underline">Download</a>
                
                <!-- Working Share Button -->
                <button type="button" @click="shareBackup()" class="text-[#713FCE] font-medium hover:underline">Share</button>
                
                <button type="button" x-show="selectedBackup.is_latest" @click="if(confirm('Are you sure you want to restore this backup? Current data will be overwritten.')) alert('Restoring...')" class="text-red-500 font-medium hover:underline">Restore</button>
                <button type="button" @click="modalOpen = false" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold px-6 py-2 rounded-full transition shadow-sm ml-4">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function backupPage() {
        return {
            selectedFolder: '{{ $settings['second_backup_folder'] ?? '' }}',
            modalOpen: false,
            selectedBackup: {},
            
            handleFolderSelect(event) {
                const files = event.target.files;
                if (files.length > 0) {
                    const pathParts = files[0].webkitRelativePath.split('/');
                    this.selectedFolder = pathParts[0];
                }
            },
            
            openBackupModal(backup) {
                this.selectedBackup = backup;
                this.modalOpen = true;
            },

            async shareBackup() {
                // Construct the download URL for the current backup
                const downloadUrl = "{{ route('manage.settings.backup.download', ':file') }}".replace(':file', this.selectedBackup.filename);
                const fullUrl = window.location.origin + downloadUrl;

                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: 'Suweka POS Backup',
                            text: 'Here is the backup file: ' + this.selectedBackup.filename,
                            url: fullUrl,
                        });
                    } catch (err) {
                        console.error('Share failed:', err);
                    }
                } else {
                    // Fallback: Copy to clipboard
                    navigator.clipboard.writeText(fullUrl).then(() => {
                        alert('Download link copied to clipboard!');
                    }).catch(() => {
                        alert('Failed to copy link. Please use the Download button.');
                    });
                }
            }
        }
    }
</script>
@endpush