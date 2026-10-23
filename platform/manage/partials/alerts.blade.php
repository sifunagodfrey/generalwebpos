{{-- ------------------- Flash messages and validation errors ------------------- --}}
@if (session('success') || session('error') || session('import_errors') || $errors->any())
    <div class="space-y-3 mb-6">

        @if (session('success'))
            <div class="flex items-start gap-3 border-l-4 border-secondary bg-secondary-light rounded-card px-4 py-3 text-sm font-medium text-secondary-900">
                <i class="fas fa-check mt-0.5"></i><span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 border-l-4 border-primary bg-primary-light rounded-card px-4 py-3 text-sm font-medium text-primary-900">
                <i class="fas fa-circle-exclamation mt-0.5"></i><span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="border-l-4 border-primary bg-primary-light rounded-card px-4 py-3 text-sm text-primary-900">
                <p class="font-bold mb-1">Please check the following</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Rows that failed during a bulk import, listed with their line numbers --}}
        @if (session('import_errors') && count(session('import_errors')))
            <div class="border nm-surface rounded-card px-4 py-3 text-sm">
                <p class="font-ui font-bold text-xs uppercase tracking-label text-ink mb-2">
                    {{ count(session('import_errors')) }} rows were skipped
                </p>
                <ul class="space-y-1 text-ink-muted max-h-40 overflow-y-auto">
                    @foreach (session('import_errors') as $line)<li>{{ $line }}</li>@endforeach
                </ul>
            </div>
        @endif

    </div>
@endif
