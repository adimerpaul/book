<?php

use App\Models\Autor;
use App\Models\Libro;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns only published books in public pagination', function () {
    $autor = Autor::factory()->create([
        'nombre_completo' => 'Mariela Suarez',
    ]);

    Libro::factory()->create([
        'autor_id' => $autor->id,
        'titulo' => 'Libro oculto',
        'publicado_web' => false,
    ]);

    Libro::factory()->count(2)->create([
        'autor_id' => $autor->id,
        'publicado_web' => true,
    ]);

    $response = $this->getJson('/api/public/libros?per_page=1&page=1');

    $response->assertOk()
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 1)
        ->assertJsonCount(1, 'data');

    expect($response->json('meta.total'))->toBe(2);
    expect(collect($response->json('data'))->pluck('titulo'))->not->toContain('Libro oculto');
});

it('returns a published book by slug with detailed payload', function () {
    $autor = Autor::factory()->create([
        'nombre_completo' => 'Carlos Medina',
    ]);

    $libro = Libro::factory()->create([
        'autor_id' => $autor->id,
        'titulo' => 'Aulas que leen',
        'publicado_web' => true,
        'contenido' => 'Contenido extenso del libro.',
        'resumen_contenido' => 'Resumen del libro.',
    ]);

    $response = $this->getJson("/api/public/libros/{$libro->slug}");

    $response->assertOk()
        ->assertJsonPath('data.slug', $libro->slug)
        ->assertJsonPath('data.titulo', 'Aulas que leen')
        ->assertJsonPath('data.autor.nombre', 'Carlos Medina')
        ->assertJsonPath('data.contenido', 'Contenido extenso del libro.');
});

it('does not expose unpublished books in public detail', function () {
    $autor = Autor::factory()->create();

    $libro = Libro::factory()->create([
        'autor_id' => $autor->id,
        'publicado_web' => false,
    ]);

    $this->getJson("/api/public/libros/{$libro->slug}")
        ->assertNotFound();
});
