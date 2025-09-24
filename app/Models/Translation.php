<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = ['locale', 'key', 'value', 'tags'];

    protected $casts = [
        'tags' => 'array',
    ];
}
