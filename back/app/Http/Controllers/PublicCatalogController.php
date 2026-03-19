<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Libro;

class PublicCatalogController extends Controller
{
    public function filters()
    {
        $categories = Libro::query()
            ->where('publicado_web', true)
            ->whereNotNull('genero')
            ->where('genero', '!=', '')
            ->selectRaw('genero as nombre, COUNT(*) as total')
            ->groupBy('genero')
            ->orderBy('genero')
            ->get()
            ->map(fn ($item) => [
                'nombre' => $item->nombre,
                'total' => (int) $item->total,
            ])
            ->values();

        $authors = Autor::query()
            ->select('autores.id', 'autores.nombre_completo', 'autores.fotografia')
            ->whereHas('libros', fn ($query) => $query->where('publicado_web', true))
            ->withCount([
                'libros as libros_publicados_count' => fn ($query) => $query->where('publicado_web', true),
            ])
            ->orderBy('nombre_completo')
            ->get()
            ->map(fn (Autor $autor) => [
                'id' => $autor->id,
                'nombre' => $autor->nombre_completo,
                'fotografia_url' => $this->buildImageUrl($autor->fotografia),
                'total' => (int) $autor->libros_publicados_count,
            ])
            ->values();

        return response()->json([
            'data' => [
                'categorias' => $categories,
                'autores' => $authors,
            ],
        ]);
    }

    private function buildImageUrl(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        return url('images/' . ltrim($filename, '/'));
    }
}
