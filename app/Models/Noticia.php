<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table = 'noticias';

    public static $snakeAttributes = false;

    protected $guarded = [];

    protected $casts = [
        'videoClip' => 'boolean',
        'img' => 'array',
        'alt' => 'array',
        'imgExtras' => 'array',
        'altExtras' => 'array',
    ];
}
