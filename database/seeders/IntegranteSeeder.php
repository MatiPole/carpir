<?php

namespace Database\Seeders;

use App\Models\Integrante;
use Illuminate\Database\Seeder;

class IntegranteSeeder extends Seeder
{
    public function run(): void
    {
        if (Integrante::count() > 0) {
            return;
        }

        $integrantes = [
            ['nombre' => 'Tomás Ulacia', 'rol' => 'Voz', 'imagen' => '/assets/img/nosotros-eze.jpg', 'orden' => 1],
            ['nombre' => 'Matías Poletto', 'rol' => 'Guitarra', 'imagen' => '/assets/img/nosotros-mati.jpg', 'orden' => 2],
            ['nombre' => 'Augusto Mezquida', 'rol' => 'Guitarra', 'imagen' => '/assets/img/nosotros-augusto.jpg', 'orden' => 3],
            ['nombre' => 'Matías Yarrouge', 'rol' => 'Bajo', 'imagen' => '/assets/img/nosotros-eze.jpg', 'orden' => 4],
            ['nombre' => 'Pablo Sosa', 'rol' => 'Batería', 'imagen' => '/assets/img/nosotros-pablo.jpg', 'orden' => 5],
        ];

        foreach ($integrantes as $i) {
            Integrante::create([
                'nombre' => $i['nombre'],
                'rol' => $i['rol'],
                'imagen' => $i['imagen'],
                'activo' => true,
                'orden' => $i['orden'],
            ]);
        }
    }
}
