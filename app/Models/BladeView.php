<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BladeView extends Model
{
    //
    protected $table = "blade_views";

    protected $fillable = [
        'key',
        'content',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
