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

    /**
     * Ordenar por columna fecha (dd-mm-yyyy), más nueva primero.
     */
    public function scopeOrderByFechaNewestFirst($query)
    {
        $driver = $query->getConnection()->getDriverName();

        if ($driver === 'mysql') {
            return $query->orderByRaw("STR_TO_DATE(fecha, '%d-%m-%Y') DESC");
        }

        if ($driver === 'sqlite') {
            return $query->orderByRaw("(substr(fecha, 7, 4) || '-' || substr(fecha, 4, 2) || '-' || substr(fecha, 1, 2)) DESC");
        }

        return $query->orderBy('created_at', 'desc');
    }
}
