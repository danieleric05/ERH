<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportError extends Model
{
    protected $table = 'import_errors';

    protected $fillable = [
        'import_type',
        'line_number',
        'data',
        'error_message',
        'status',
    ];

    protected $casts = [
        'data' => 'json',
        'created_at' => 'datetime',
    ];
}
