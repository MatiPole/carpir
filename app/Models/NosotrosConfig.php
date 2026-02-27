<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NosotrosConfig extends Model
{
    protected $table = 'nosotros_config';

    public $timestamps = true;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $guarded = [];

    public static function getConfig(): self
    {
        $row = self::find(1);
        if ($row) {
            return $row;
        }
        return new self([
            'id' => 1,
            'descripcion' => '',
            'descripcion_extra' => '',
            'imagen_portada' => '',
        ]);
    }
}
