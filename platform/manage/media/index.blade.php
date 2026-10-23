{{-- ------------------- Media library ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Media library')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Media library',
        'lead'   => 'Every file uploaded through the admin panel, in one place.',
        'crumbs' => ['Site Media' => null],
    ])

    <div class="nm-panel mb-6">
        <div class="nm-panel__head">Upload</div>
        <div class="nm-panel__body">
            <form method="POST" action="{{ route('manage.media.upload') }}" enctype="multipart/form-data"
                class="flex flex-wrap items-end gap-3">
                @csrf

                <div class="flex-1 min-w-[240px]">
                    <label class="nm-label">Files</label>
                    <input type="file" name="files[]" multiple required
                        class="nm-input file:mr-3 file:border-0 file:bg-overlay file:text-white file:px-3 file:py-1.5 file:text-xs file:font-ui file:font-bold file:uppercase file:tracking-label">
                </div>

                <div class="w-48">
                    <label class="nm-label">Folder</label>
                    <input type="text" name="folder" value="{{ request('folder', 'products') }}"
                        placeholder="products" class="nm-input">
                </div>

                <button type="submit" class="nm-btn nm-btn--primary nm-btn--sm">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </form>
        </div>
    </div>

    @include('manage.partials.filters', [
        'search' => 'File name',
        'selects' => [
            'folder' => ['label' => 'Folder', 'options' => $folders->combine($folders)],
        ],
    ])

    @php
        $usedForHumans = $used;
        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($usedForHumans < 1024) {
                $usedForHumans = round($usedForHumans, 1) . ' ' . $unit;
                break;
            }
            $usedForHumans /= 1024;
        }
    @endphp
    <p class="text-xs text-ink-muted mb-4">
        {{ $media->total() }} {{ Str::plural('file', $media->total()) }} · {{ $usedForHumans }} used
    </p>

    @if ($media->isEmpty())

        @include('manage.partials.empty', [
            'icon'    => 'fa-photo-film',
            'title'   => 'Nothing uploaded yet',
            'message' => 'Files added anywhere in the admin panel, product photos, gallery shots, banners, all land here too.',
        ])

    @else

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach ($media as $item)
                <div x-data="{ open: false }" class="border nm-surface rounded-card overflow-hidden group relative">

                    <button type="button" @click="open = true" class="block w-full aspect-square bg-steel flex items-center justify-center relative overflow-hidden">
                        <span class="absolute top-1.5 left-1.5 z-10 px-1.5 py-0.5 rounded-card text-[9px] font-ui font-bold uppercase tracking-label text-white"
                              style="background-color: {{ $item->type_color }}">{{ $item->type_label }}</span>

                        @if ($item->is_image)
                            <img src="{{ $item->url }}" alt="{{ $item->alt_text ?: $item->file_name }}" loading="lazy"
                                class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                        @elseif (str_starts_with((string) $item->mime_type, 'video/'))
                            <i class="fas fa-file-video text-3xl text-ink-muted"></i>
                        @elseif ($item->extension === 'pdf')
                            <i class="fas fa-file-pdf text-3xl text-primary"></i>
                        @elseif (in_array($item->extension, ['xlsx', 'xls', 'csv']))
                            <i class="fas fa-file-excel text-3xl" style="color: {{ $item->type_color }}"></i>
                        @elseif (in_array($item->extension, ['doc', 'docx']))
                            <i class="fas fa-file-word text-3xl text-ink-muted"></i>
                        @else
                            <i class="fas fa-file text-3xl text-ink-muted"></i>
                        @endif

                        <span class="absolute inset-0 bg-overlay/0 group-hover:bg-overlay/30 transition flex items-center justify-center">
                            <i class="fas fa-eye text-white opacity-0 group-hover:opacity-100 transition"></i>
                        </span>
                    </button>

                    <div class="p-2.5">
                        <p class="text-[11px] text-ink-muted truncate" title="{{ $item->original_name ?: $item->file_name }}">{{ $item->original_name ?: $item->file_name }}</p>
                        <div class="flex items-center justify-between mt-1.5">
                            <span class="text-[10px] font-mono text-ink-muted">{{ $item->size_for_humans }}</span>
                            <div class="flex items-center">
                                <button type="button" @click="open = true" title="View details"
                                        class="w-6 h-6 flex items-center justify-center text-ink-muted hover:text-primary transition">
                                    <i class="fas fa-circle-info text-xs"></i>
                                </button>
                                <a href="{{ route('manage.media.download', $item->id) }}" title="Download" data-turbo="false"
                                   class="w-6 h-6 flex items-center justify-center text-ink-muted hover:text-primary transition">
                                    <i class="fas fa-download text-xs"></i>
                                </a>
                                @include('manage.partials.delete', [
                                    'action'  => route('manage.media.destroy', $item->id),
                                    'confirm' => 'Delete ' . ($item->original_name ?: $item->file_name) . '? This cannot be undone.',
                                    'class'   => 'w-6 h-6 flex items-center justify-center text-ink-muted hover:text-primary transition',
                                ])
                            </div>
                        </div>
                    </div>

                    {{-- Details modal --}}
                    <div x-show="open" x-cloak x-transition
                         class="fixed inset-0 z-[9996] flex items-center justify-center px-4 bg-overlay/60">
                        <div class="bg-surface text-ink rounded-card shadow-lg w-full max-w-md overflow-hidden" @click.outside="open = false">

                            <div class="aspect-video bg-steel flex items-center justify-center overflow-hidden">
                                @if ($item->is_image)
                                    <img src="{{ $item->url }}" alt="{{ $item->alt_text ?: $item->file_name }}" class="w-full h-full object-contain">
                                @elseif (str_starts_with((string) $item->mime_type, 'video/'))
                                    <i class="fas fa-file-video text-5xl text-ink-muted"></i>
                                @elseif ($item->extension === 'pdf')
                                    <i class="fas fa-file-pdf text-5xl text-primary"></i>
                                @elseif (in_array($item->extension, ['xlsx', 'xls', 'csv']))
                                    <i class="fas fa-file-excel text-5xl" style="color: {{ $item->type_color }}"></i>
                                @elseif (in_array($item->extension, ['doc', 'docx']))
                                    <i class="fas fa-file-word text-5xl text-ink-muted"></i>
                                @else
                                    <i class="fas fa-file text-5xl text-ink-muted"></i>
                                @endif
                            </div>

                            <div class="px-5 py-4 border-b border-line flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-heading font-bold truncate">{{ $item->original_name ?: $item->file_name }}</p>
                                    <span class="inline-block mt-1 px-1.5 py-0.5 rounded-card text-[9px] font-ui font-bold uppercase tracking-label text-white"
                                          style="background-color: {{ $item->type_color }}">{{ $item->type_label }}</span>
                                </div>
                                <button type="button" @click="open = false" class="text-ink-muted hover:text-primary shrink-0" aria-label="Close">
                                    <i class="fas fa-xmark"></i>
                                </button>
                            </div>

                            <div class="p-5 space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-ink-muted">Type</span><span>{{ $item->mime_type ?: '—' }}</span></div>
                                <div class="flex justify-between"><span class="text-ink-muted">Size</span><span>{{ $item->size_for_humans }}</span></div>
                                @if ($item->width && $item->height)
                                    <div class="flex justify-between"><span class="text-ink-muted">Dimensions</span><span>{{ $item->width }} &times; {{ $item->height }} px</span></div>
                                @endif
                                <div class="flex justify-between"><span class="text-ink-muted">Folder</span><span>{{ $item->folder ?: '—' }}</span></div>
                                <div class="flex justify-between"><span class="text-ink-muted">Uploaded by</span><span>{{ $item->uploader->name ?? '—' }}</span></div>
                                <div class="flex justify-between"><span class="text-ink-muted">Uploaded</span><span>{{ $item->created_at?->format('d M Y, H:i') }}</span></div>
                                @if ($item->alt_text)
                                    <div class="flex justify-between"><span class="text-ink-muted">Alt text</span><span>{{ $item->alt_text }}</span></div>
                                @endif
                            </div>

                            <div class="px-5 py-4 border-t border-line flex items-center justify-end gap-2">
                                <button type="button" @click="open = false" class="nm-btn nm-btn--ghost nm-btn--sm">Close</button>
                                <a href="{{ route('manage.media.download', $item->id) }}" data-turbo="false" class="nm-btn nm-btn--primary nm-btn--sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $media->links() }}</div>

    @endif

@endsection
