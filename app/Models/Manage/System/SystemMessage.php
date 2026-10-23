<?php

namespace App\Models\Manage\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemMessage extends Model
{
    use HasFactory;

    protected $table = 'su_system_messages';

    // ---------------
    // su_system_messages has no updated_at column
    // ---------------
    public const UPDATED_AT = null;

    protected $guarded = [];
}