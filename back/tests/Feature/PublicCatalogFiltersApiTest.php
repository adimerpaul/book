<?php

use App\Models\Autor;
use App\Models\Libro;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns public categories and authors for the catalog filters', function () {
    $autorVisible = Autor::factory()->create([
        'nombre_completo' => 'Lucia Andrade',
    ]);

    $autorOculto = Autor::factory()->create([
        'nombre_completo' => 'Autor oculto',
    ]);

    Libro::factory()->create([
        'autor_id' => $autorVisible->id,
        'titulo' => 'Biblioteca del viento',
        'genero' => 'Narrativa',
        'publicado_web' => true,
    ]);

    Libro::factory()->create([
        'autor_id' => $autorVisible->id,
        'titulo' => 'Cartas para la escuela',
        'genero' => 'Educacion',
        'publicado_web' => true,
    ]);

    Libro::factory()->create([
        'autor_id' => $autorOculto->id,
        'titulo' => 'Libro interno',
        'genero' => 'Narrativa',
        'publicado_web' => false,
    ]);

    $response = $this->getJson('/api/public/catalogo/filtros');

    $response->assertOk()
        ->assertJsonPath('data.categorias.0.nombre', 'Educacion')
        ->assertJsonPath('data.categorias.1.nombre', 'Narrativa')
        ->assertJsonCount(2, 'data.categorias')
        ->assertJsonCount(1, 'data.autores')
        ->assertJsonPath('data.autores.0.nombre', 'Lucia Andrade')
        ->assertJsonPath('data.autores.0.total', 2);
});
