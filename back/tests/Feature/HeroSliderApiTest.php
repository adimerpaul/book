<?php

use App\Models\Autor;
use App\Models\HeroSlider;
use App\Models\Libro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('returns public hero sliders ordered and only published', function () {
    $autor = Autor::factory()->create();
    $libro = Libro::factory()->create([
        'autor_id' => $autor->id,
        'portada' => 'portada-test.jpg',
    ]);

    HeroSlider::factory()->create([
        'title' => 'Oculto',
        'publicado_web' => false,
        'orden' => 1,
    ]);

    HeroSlider::factory()->create([
        'title' => 'Segundo',
        'publicado_web' => true,
        'orden' => 2,
    ]);

    HeroSlider::factory()->create([
        'title' => 'Primero',
        'publicado_web' => true,
        'orden' => 1,
        'libro_principal_id' => $libro->id,
    ]);

    $response = $this->getJson('/api/public/hero-sliders');

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.title', 'Primero')
        ->assertJsonPath('data.0.covers.0.titulo', $libro->titulo);

    expect(collect($response->json('data'))->pluck('title'))->not->toContain('Oculto');
});

it('stores and lists hero sliders in the admin api', function () {
    Sanctum::actingAs(User::factory()->create());

    $autor = Autor::factory()->create();
    $libro = Libro::factory()->create([
        'autor_id' => $autor->id,
    ]);

    $created = $this->postJson('/api/hero-sliders', [
        'eyebrow' => 'Editorial contemporanea',
        'title' => 'Slider principal',
        'description' => 'Descripcion hero para la home.',
        'badge' => 'Colecciones con identidad',
        'theme' => 'heritage',
        'primary_cta_label' => 'Ver libros',
        'primary_cta_url' => '/libros',
        'secondary_cta_label' => 'Contactanos',
        'secondary_cta_url' => '/#contacto',
        'libro_principal_id' => $libro->id,
        'orden' => 3,
        'publicado_web' => true,
    ]);

    $created->assertCreated()
        ->assertJsonPath('title', 'Slider principal')
        ->assertJsonPath('libro_principal.id', $libro->id);

    $list = $this->getJson('/api/hero-sliders?per_page=10&page=1');

    $list->assertOk()
        ->assertJsonPath('data.0.title', 'Slider principal');
});
