<?php

/* ------------------- Media library ------------------- */

namespace App\Http\Controllers\Manage;

use App\Models\System\Media;
use App\Services\UploadService;
use Illuminate\Http\Request;

class MediaController extends ManageController
{
    public function index(Request $request)
    {
        return view('manage.media.index', [
            'media'   => Media::with('uploader:id,first_name,last_name')
                ->when($request->input('folder'), fn ($q, $f) => $q->where('folder', $f))
                ->when($request->filled('q'), fn ($q) => $q->where('file_name', 'like', '%' . $request->input('q') . '%'))
                ->latest('id')
                ->paginate(48)
                ->withQueryString(),
            'folders' => Media::distinct()->orderBy('folder')->pluck('folder')->filter(),
            'used'    => (int) Media::sum('file_size'),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['file', 'max:4096'],
            'folder'  => ['nullable', 'string', 'max:60'],
        ]);

        $uploader = app(UploadService::class);
        $folder = $request->input('folder', 'products');
        $stored = [];

        foreach ($request->file('files') as $file) {
            $stored[] = $uploader->store($file, $folder);
        }

        return $this->respond($request, true, count($stored) . ' ' . \Illuminate\Support\Str::plural('file', count($stored)) . ' uploaded.', null, [
            'media' => $stored,
        ]);
    }

    /* Backs the picker panel used across the admin panel */
    public function refresh(Request $request)
    {
        return response()->json(
            Media::when($request->input('folder'), fn ($q, $f) => $q->where('folder', $f))
                ->latest('id')
                ->limit(60)
                ->get(['id', 'file_path', 'file_name', 'alt_text'])
        );
    }

    public function destroy(Request $request, int $id)
    {
        app(UploadService::class)->delete(Media::findOrFail($id));

        return $this->respond($request, true, 'File removed.');
    }

    /* Forces a real download rather than letting the browser render an
       image or PDF inline, and hands back the original filename rather
       than the slugged, randomized one it's actually stored under */
    public function download(int $id)
    {
        $item = Media::findOrFail($id);
        $full = public_path($item->file_path);

        abort_unless(is_file($full), 404);

        return response()->download($full, $item->original_name ?: $item->file_name);
    }
}
