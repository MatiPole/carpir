<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fechas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('locacion', 200)->default('');
            $table->text('direccion');
            $table->string('horario', 50)->default('');
            $table->string('costo', 100)->default('');
            $table->string('link_entradas', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechas');
    }
};
