<?php

namespace Database\Factories;

use App\Models\Libro;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LibroFactory extends Factory
{
    protected $model = Libro::class;

    public function definition(): array
    {
        $titulo = fake()->sentence(fake()->numberBetween(2, 5));

        return [
            'slug' => Str::slug($titulo).'-'.fake()->unique()->numerify('######'),
            'titulo' => $titulo,
            'fecha_publicacion' => fake()->dateTimeBetween('-35 years', 'now')->format('Y-m-d'),
            'pais' => fake()->country(),
            'idioma' => fake()->randomElement([
                'Espanol',
                'Ingles',
                'Portugues',
                'Frances',
            ]),
            'genero' => fake()->randomElement([
                'Novela',
                'Ensayo',
                'Poesia',
                'Cuento',
                'Cronica',
                'Biografia',
            ]),
            'subgenero' => fake()->randomElement([
                'Historica',
                'Contemporanea',
                'Filosofica',
                'Social',
                'Infantil',
                'Juvenil',
                'Fantastica',
            ]),
            'editorial' => fake()->randomElement([
                'Latinas Editores',
                'Editorial Horizonte',
                'Casa del Libro',
                'Ediciones Sur',
                'Letra Viva',
            ]),
            'paginas' => fake()->numberBetween(80, 780),
            'precio' => fake()->randomFloat(2, 100, 180),
            'contenido' => fake()->paragraphs(fake()->numberBetween(4, 8), true),
            'resumen_contenido' => fake()->paragraphs(2, true),
            'reconocimiento' => fake()->boolean(35)
                ? collect(fake()->randomElements([
                    'Premio Nacional de Narrativa',
                    'Seleccion Oficial de Feria Internacional',
                    'Mencion de Honor Editorial',
                    'Reconocimiento a la Innovacion Cultural',
                ], fake()->numberBetween(1, 2)))->implode(', ')
                : null,
            'portada' => null,
            'drive_indice_url' => fake()->boolean(80) ? fake()->url() : null,
            'publicado_web' => fake()->boolean(70),
        ];
    }
}
