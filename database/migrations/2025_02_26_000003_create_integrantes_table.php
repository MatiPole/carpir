<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('rol', 100);
            $table->string('imagen', 500)->default('');
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrantes');
    }
};
