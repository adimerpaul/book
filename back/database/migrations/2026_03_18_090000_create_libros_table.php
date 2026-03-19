<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('autor_id')->constrained('autores');
            $table->date('fecha_publicacion')->nullable();
            $table->string('pais')->nullable();
            $table->string('idioma')->nullable();
            $table->string('genero')->nullable();
            $table->string('subgenero')->nullable();
            $table->string('editorial')->nullable();
            $table->unsignedInteger('paginas')->nullable();
            $table->longText('contenido')->nullable();
            $table->text('resumen_contenido')->nullable();
            $table->text('reconocimiento')->nullable();
            $table->string('portada')->nullable();
            $table->string('drive_indice_url', 1000)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
