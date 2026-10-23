<?php

/*
 * ---------------------------------------------------------------------------
 * File uploads.
 *
 * Every upload lands in a folder named for what it belongs to, gets a slugged
 * unique filename and is recorded in the media library so it can be picked
 * again later rather than uploaded twice.
 * ---------------------------------------------------------------------------
 */

namespace App\Services;

use App\Models\System\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UploadService
{
    /* Stores a file and registers it in the media library */
    public function store(UploadedFile $file, string $folderKey = 'products', ?string $altText = null): Media
    {
        $folder = 'uploads/' . $folderKey;
        $name   = $this->filename($file);

        $file->move(public_path($folder), $name);

        $path = $folder . '/' . $name;
        $size = @filesize(public_path($path)) ?: null;
        [$width, $height] = $this->dimensions(public_path($path));

        return Media::create([
            'file_name'     => $name,
            'original_name' => $file->getClientOriginalName(),
            'file_path'     => $path,
            'disk'          => 'public',
            'folder'        => $folderKey,
            'mime_type'     => $file->getClientMimeType(),
            'extension'     => strtolower($file->getClientOriginalExtension()),
            'file_size'     => $size,
            'width'         => $width,
            'height'        => $height,
            'alt_text'      => $altText,
            'uploaded_by'   => Auth::id(),
        ]);
    }

    /* Stores without registering, for private files such as quotation attachments */
    public function storeQuietly(UploadedFile $file, string $folder): string
    {
        $name = $this->filename($file);
        $file->move(public_path($folder), $name);

        return $folder . '/' . $name;
    }

    public function delete(Media $media): void
    {
        $full = public_path($media->file_path);

        if (is_file($full)) {
            @unlink($full);
        }

        $media->delete();
    }

    /* Slugged original name plus a short token, so files stay readable and unique */
    private function filename(UploadedFile $file): string
    {
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $base = Str::limit($base, 60, '');

        return ($base ?: 'file') . '-' . Str::lower(Str::random(6)) . '.' . strtolower($file->getClientOriginalExtension());
    }

    private function dimensions(string $path): array
    {
        if (! function_exists('getimagesize') || ! is_file($path)) {
            return [null, null];
        }

        $info = @getimagesize($path);

        return $info ? [$info[0], $info[1]] : [null, null];
    }
}
