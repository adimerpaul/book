<?php

namespace App\Http\Controllers;

use App\Models\HeroSlider;

class PublicHeroSliderController extends Controller
{
    public function index()
    {
        $items = HeroSlider::query()
            ->with([
                'libroPrincipal:id,titulo,slug,portada',
                'libroSecundario:id,titulo,slug,portada',
                'libroTerciario:id,titulo,slug,portada',
            ])
            ->where('publicado_web', true)
            ->orderBy('orden')
            ->orderByDesc('id')
            ->get()
            ->map(fn (HeroSlider $heroSlider) => [
                'id' => $heroSlider->id,
                'eyebrow' => $heroSlider->eyebrow,
                'title' => $heroSlider->title,
                'description' => $heroSlider->description,
                'badge' => $heroSlider->badge,
                'theme' => $heroSlider->theme,
                'primary_cta_label' => $heroSlider->primary_cta_label,
                'primary_cta_url' => $heroSlider->primary_cta_url,
                'secondary_cta_label' => $heroSlider->secondary_cta_label,
                'secondary_cta_url' => $heroSlider->secondary_cta_url,
                'covers' => collect([
                    $heroSlider->libroPrincipal,
                    $heroSlider->libroSecundario,
                    $heroSlider->libroTerciario,
                ])->filter()->map(fn ($libro) => [
                    'id' => $libro->id,
                    'titulo' => $libro->titulo,
                    'slug' => $libro->slug,
                    'portada_url' => blank($libro->portada) ? null : url('images/' . ltrim($libro->portada, '/')),
                ])->values(),
            ])
            ->values();

        return response()->json([
            'data' => $items,
        ]);
    }
}
