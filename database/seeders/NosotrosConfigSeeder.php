<?php

namespace Database\Seeders;

use App\Models\NosotrosConfig;
use Illuminate\Database\Seeder;

class NosotrosConfigSeeder extends Seeder
{
    public function run(): void
    {
        NosotrosConfig::updateOrCreate(
            ['id' => 1],
            [
                'descripcion' => 'Carpir es un proyecto que comienza en el 2018 y termina de consolidarse a finales del 2021. La banda, compuesta por Tomás Ulacia (voz), Matías Poletto (guitarra), Augusto Mezquida (guitarra), Matías Yarrouge (bajo) y Pablo Sosa (batería), es una mezcla de distintas influencias que desembocan en sonidos, en general afines al rock, pero con tintes de funk, grunge e indie.',
                'descripcion_extra' => 'En febrero del 2022 comienza la grabación del primer EP de la banda en el estudio de "PLV Producciones" y finalmente el 21 de abril se estrena el mismo, al cual lo componen 3 canciones y lleva el nombre "Despertar". Actualmente la banda se encuentra realizando presentaciones en vivo donde interpretan los 3 temas del disco y otras 7 canciones que aún no fueron grabadas pero que ya están preparando para llevarlas al estudio.',
                'imagen_portada' => '/assets/img/carpir-banner-home.jpg',
            ]
        );
    }
}
