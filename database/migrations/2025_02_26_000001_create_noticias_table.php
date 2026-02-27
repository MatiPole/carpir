<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->text('titulo');
            $table->string('fecha', 20);
            $table->text('noticia');
            $table->text('img')->nullable();
            $table->text('alt')->nullable();
            $table->text('imgExtras')->nullable();
            $table->text('altExtras')->nullable();
            $table->boolean('videoClip')->default(false);
            $table->string('linkVideoClip', 500)->default('');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
