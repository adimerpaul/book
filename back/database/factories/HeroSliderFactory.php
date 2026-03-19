<?php

namespace Database\Factories;

use App\Models\HeroSlider;
use Illuminate\Database\Eloquent\Factories\Factory;

class HeroSliderFactory extends Factory
{
    protected $model = HeroSlider::class;

    public function definition(): array
    {
        return [
            'eyebrow' => fake()->randomElement([
                'Editorial contemporanea',
                'Catalogo curado',
                'Lectura que transforma',
            ]),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(2),
            'badge' => fake()->sentence(3),
            'theme' => fake()->randomElement(['heritage', 'catalog', 'community']),
            'primary_cta_label' => 'Ver libros',
            'primary_cta_url' => '/libros',
            'secondary_cta_label' => 'Contactanos',
            'secondary_cta_url' => '/#contacto',
            'orden' => fake()->numberBetween(1, 20),
            'publicado_web' => fake()->boolean(70),
        ];
    }
}
