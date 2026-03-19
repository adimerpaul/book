<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow', 120);
            $table->string('title');
            $table->text('description');
            $table->string('badge', 160)->nullable();
            $table->string('theme', 40)->default('heritage');
            $table->string('primary_cta_label', 80)->nullable();
            $table->string('primary_cta_url')->nullable();
            $table->string('secondary_cta_label', 80)->nullable();
            $table->string('secondary_cta_url')->nullable();
            $table->foreignId('libro_principal_id')->nullable()->constrained('libros')->nullOnDelete();
            $table->foreignId('libro_secundario_id')->nullable()->constrained('libros')->nullOnDelete();
            $table->foreignId('libro_terciario_id')->nullable()->constrained('libros')->nullOnDelete();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('publicado_web')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('hero_sliders')->insert([
            [
                'eyebrow' => 'Gerencia editorial',
                'title' => 'Ediciones con criterio cultural y salida comercial.',
                'description' => 'Impulsamos libros pensados para lectores, instituciones y espacios formativos, con una gestion editorial profesional y cercana.',
                'badge' => 'Catalogo con identidad',
                'theme' => 'heritage',
                'primary_cta_label' => 'Ver libros',
                'primary_cta_url' => '/libros',
                'secondary_cta_label' => 'Contactanos',
                'secondary_cta_url' => '/#contacto',
                'orden' => 1,
                'publicado_web' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'eyebrow' => 'Lectura y formacion',
                'title' => 'Libros que fortalecen conocimiento, memoria y comunidad.',
                'description' => 'Construimos un catalogo en espanol que conecta lectura, cultura y proyeccion editorial para distintos tipos de publico.',
                'badge' => 'Publicaciones destacadas',
                'theme' => 'catalog',
                'primary_cta_label' => 'Explorar catalogo',
                'primary_cta_url' => '/libros',
                'secondary_cta_label' => 'Solicitar informacion',
                'secondary_cta_url' => '/#contacto',
                'orden' => 2,
                'publicado_web' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'eyebrow' => 'Promocion editorial',
                'title' => 'Una presencia web preparada para difundir obras con confianza.',
                'description' => 'Cada slider puede destacar libros, mensajes comerciales y llamados a la accion para acompanar la conversion desde la pagina principal.',
                'badge' => 'Carrusel administrable',
                'theme' => 'community',
                'primary_cta_label' => 'Descubrir colecciones',
                'primary_cta_url' => '/libros',
                'secondary_cta_label' => 'Hablar con el equipo',
                'secondary_cta_url' => '/#contacto',
                'orden' => 3,
                'publicado_web' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_sliders');
    }
};
