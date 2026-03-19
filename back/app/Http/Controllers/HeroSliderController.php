<?php

namespace App\Http\Controllers;

use App\Models\HeroSlider;
use Illuminate\Http\Request;

class HeroSliderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min($perPage, 100));
        $search = trim((string) $request->string('search', ''));
        $publicadoWeb = $request->get('publicado_web');

        $query = HeroSlider::query()
            ->with($this->relations())
            ->orderBy('orden')
            ->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('eyebrow', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('badge', 'like', "%{$search}%");
            });
        }

        if ($publicadoWeb !== null && $publicadoWeb !== '') {
            $query->where('publicado_web', filter_var($publicadoWeb, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false);
        }

        return $query->paginate($perPage);
    }

    public function show(HeroSlider $heroSlider)
    {
        return $heroSlider->load($this->relations());
    }

    public function store(Request $request)
    {
        $heroSlider = HeroSlider::create($this->validateHeroSlider($request));

        return $heroSlider->load($this->relations());
    }

    public function update(Request $request, HeroSlider $heroSlider)
    {
        $heroSlider->update($this->validateHeroSlider($request));

        return $heroSlider->fresh($this->relations());
    }

    public function destroy(HeroSlider $heroSlider)
    {
        $heroSlider->delete();

        return response()->json([
            'message' => 'Hero slider eliminado',
        ]);
    }

    private function validateHeroSlider(Request $request): array
    {
        return $request->validate([
            'eyebrow' => 'required|string|max:120',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'badge' => 'nullable|string|max:160',
            'theme' => 'required|string|in:heritage,catalog,community',
            'primary_cta_label' => 'nullable|string|max:80',
            'primary_cta_url' => 'nullable|string|max:255',
            'secondary_cta_label' => 'nullable|string|max:80',
            'secondary_cta_url' => 'nullable|string|max:255',
            'libro_principal_id' => 'nullable|integer|exists:libros,id',
            'libro_secundario_id' => 'nullable|integer|exists:libros,id|different:libro_principal_id',
            'libro_terciario_id' => 'nullable|integer|exists:libros,id|different:libro_principal_id|different:libro_secundario_id',
            'orden' => 'nullable|integer|min:0|max:9999',
            'publicado_web' => 'nullable|boolean',
        ]);
    }

    private function relations(): array
    {
        return [
            'libroPrincipal:id,titulo,slug,portada',
            'libroSecundario:id,titulo,slug,portada',
            'libroTerciario:id,titulo,slug,portada',
        ];
    }
}
