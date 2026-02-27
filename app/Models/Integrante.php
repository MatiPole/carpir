<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integrante extends Model
{
    protected $table = 'integrantes';

    public $timestamps = true;

    protected $guarded = [];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
