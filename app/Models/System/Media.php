<?php

/*
 * ---------------------------------------------------------------------------
 * An uploaded file in the shared media library.
 * Table: su_media
 * ---------------------------------------------------------------------------
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_media';

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'file_name',
        'original_name',
        'file_path',
        'disk',
        'folder',
        'mime_type',
        'extension',
        'file_size',
        'width',
        'height',
        'alt_text',
        'title',
        'uploaded_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'uploaded_by' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return asset($this->file_path);
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    /* Short, human label for the file type badge, image/pdf/video/sheet
       cover the common cases, everything else just shows its extension */
    public function getTypeLabelAttribute(): string
    {
        if ($this->is_image) {
            return 'Image';
        }

        return match (true) {
            str_starts_with((string) $this->mime_type, 'video/') => 'Video',
            str_starts_with((string) $this->mime_type, 'audio/') => 'Audio',
            $this->extension === 'pdf' => 'PDF',
            in_array($this->extension, ['xlsx', 'xls', 'csv'], true) => 'Sheet',
            in_array($this->extension, ['doc', 'docx'], true) => 'Doc',
            default => strtoupper((string) $this->extension) ?: 'File',
        };
    }

    /* A hex, not a Tailwind class, deliberately, the badge needs to read
       the same regardless of what other utility classes the card around
       it is carrying */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type_label) {
            'Image' => '#16a34a',
            'PDF' => '#dc2626',
            'Sheet' => '#ea580c',
            'Video', 'Audio' => '#5c617d',
            default => '#7f8399',
        };
    }

    /* Human readable size such as 1.4 MB */
    public function getSizeForHumansAttribute(): string
    {
        $bytes = (int) $this->file_size;

        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, 1) . ' ' . $unit;
            }
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' TB';
    }
}
