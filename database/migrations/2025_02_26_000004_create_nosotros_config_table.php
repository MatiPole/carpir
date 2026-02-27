<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nosotros_config', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->text('descripcion');
            $table->text('descripcion_extra');
            $table->string('imagen_portada', 500)->default('');
            $table->timestamps();
        });

        DB::table('nosotros_config')->insert([
            'id' => 1,
            'descripcion' => 'Carpir es un proyecto que comienza en el 2018 y termina de consolidarse a finales del 2021. La banda, compuesta por Tomás Ulacia (voz), Matías Poletto (guitarra), Augusto Mezquida (guitarra), Matías Yarrouge (bajo) y Pablo Sosa (batería), es una mezcla de distintas influencias que desembocan en sonidos, en general afines al rock, pero con tintes de funk, grunge e indie.',
            'descripcion_extra' => 'En febrero del 2022 comienza la grabación del primer EP de la banda en el estudio de "PLV Producciones" y finalmente el 21 de abril se estrena el mismo, al cual lo componen 3 canciones y lleva el nombre "Despertar". Actualmente la banda se encuentra realizando presentaciones en vivo donde interpretan los 3 temas del disco y otras 7 canciones que aún no fueron grabadas pero que ya están preparando para llevarlas al estudio.',
            'imagen_portada' => '/assets/img/carpir-banner-home.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('nosotros_config');
    }
};
