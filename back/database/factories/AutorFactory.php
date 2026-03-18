<?php

namespace Database\Factories;

use App\Models\Autor;
use Illuminate\Database\Eloquent\Factories\Factory;

class AutorFactory extends Factory
{
    protected $model = Autor::class;

    public function definition(): array
    {
        $fechaNacimiento = fake()->dateTimeBetween('-95 years', '-35 years');
        $estaFallecido = fake()->boolean(18);
        $fechaFallecimiento = $estaFallecido
            ? fake()->dateTimeBetween($fechaNacimiento, 'now')
            : null;

        return [
            'nombre_completo' => fake()->name(),
            'fecha_nacimiento' => $fechaNacimiento->format('Y-m-d'),
            'lugar_nacimiento' => fake()->city() . ', ' . fake()->country(),
            'fecha_fallecimiento' => $fechaFallecimiento?->format('Y-m-d'),
            'profesion' => fake()->randomElement([
                'Escritor',
                'Poeta',
                'Narrador',
                'Ensayista',
                'Profesor',
                'Periodista cultural',
                'Investigador',
            ]),
            'genero_literario' => fake()->randomElement([
                'Novela',
                'Poesia',
                'Ensayo',
                'Cuento',
                'Cronica',
                'Literatura infantil',
                'Biografia',
            ]),
            'premios' => fake()->boolean(65)
                ? collect(fake()->randomElements([
                    'Premio Nacional de Literatura',
                    'Reconocimiento Editorial Hispanoamericano',
                    'Premio de Narrativa Contemporanea',
                    'Medalla al Merito Cultural',
                    'Premio Internacional de Poesia',
                    'Distincion a la Trayectoria Intelectual',
                ], fake()->numberBetween(1, 3)))->implode(', ')
                : null,
            'fotografia' => null,
        ];
    }
}
