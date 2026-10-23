{{-- ------------------- Sign in errors and notices ------------------- --}}
@if ($errors->any())
    <div class="mt-6 flex items-start gap-3 border-l-4 border-primary bg-primary-light px-4 py-3 text-sm text-primary-900">
        <i class="fas fa-circle-exclamation mt-0.5"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

@if (session('status'))
    <div class="mt-6 flex items-start gap-3 border-l-4 border-secondary bg-secondary-light px-4 py-3 text-sm text-secondary-900">
        <i class="fas fa-circle-info mt-0.5"></i>
        <span>{{ session('status') }}</span>
    </div>
@endif

@if (session('success'))
    <div class="mt-6 flex items-start gap-3 border-l-4 border-secondary bg-secondary-light px-4 py-3 text-sm text-secondary-900">
        <i class="fas fa-check mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mt-6 flex items-start gap-3 border-l-4 border-primary bg-primary-light px-4 py-3 text-sm text-primary-900">
        <i class="fas fa-circle-exclamation mt-0.5"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif
