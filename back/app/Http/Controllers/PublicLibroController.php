<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class PublicLibroController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 9);
        $perPage = max(1, min($perPage, 24));
        $page = max(1, (int) $request->integer('page', 1));
        $search = trim((string) $request->string('search', ''));

        $query = Libro::query()
            ->with(['autor:id,nombre_completo', 'fotografias:id,libro_id,archivo'])
            ->where('publicado_web', true)
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('titulo', 'like', "%{$search}%")
                    ->orWhere('genero', 'like', "%{$search}%")
                    ->orWhere('subgenero', 'like', "%{$search}%")
                    ->orWhereHas('autor', function ($autorQuery) use ($search) {
                        $autorQuery->where('nombre_completo', 'like', "%{$search}%");
                    });
            });
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginator->getCollection()->map(fn (Libro $libro) => $this->transformListItem($libro));

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'links' => [
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $libro = Libro::query()
            ->with(['autor:id,nombre_completo,fotografia', 'fotografias:id,libro_id,archivo'])
            ->where('publicado_web', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => $this->transformDetailItem($libro),
        ]);
    }

    private function transformListItem(Libro $libro): array
    {
        return [
            'id' => $libro->id,
            'slug' => $libro->slug,
            'titulo' => $libro->titulo,
            'autor' => $libro->autor?->nombre_completo,
            'resumen' => $libro->resumen_contenido,
            'genero' => $libro->genero,
            'subgenero' => $libro->subgenero,
            'editorial' => $libro->editorial,
            'paginas' => $libro->paginas,
            'fecha_publicacion' => $libro->fecha_publicacion?->toDateString(),
            'portada_url' => $this->buildImageUrl($libro->portada),
            'fotografias' => $libro->fotografias
                ->take(2)
                ->map(fn ($fotografia) => $this->buildImageUrl($fotografia->archivo))
                ->filter()
                ->values(),
        ];
    }

    private function transformDetailItem(Libro $libro): array
    {
        return [
            'id' => $libro->id,
            'slug' => $libro->slug,
            'titulo' => $libro->titulo,
            'autor' => [
                'nombre' => $libro->autor?->nombre_completo,
                'fotografia_url' => $this->buildImageUrl($libro->autor?->fotografia),
            ],
            'resumen' => $libro->resumen_contenido,
            'contenido' => $libro->contenido,
            'reconocimiento' => $libro->reconocimiento,
            'genero' => $libro->genero,
            'subgenero' => $libro->subgenero,
            'editorial' => $libro->editorial,
            'idioma' => $libro->idioma,
            'pais' => $libro->pais,
            'paginas' => $libro->paginas,
            'fecha_publicacion' => $libro->fecha_publicacion?->toDateString(),
            'drive_indice_url' => $libro->drive_indice_url,
            'portada_url' => $this->buildImageUrl($libro->portada),
            'fotografias' => $libro->fotografias
                ->map(fn ($fotografia) => $this->buildImageUrl($fotografia->archivo))
                ->filter()
                ->values(),
        ];
    }

    private function buildImageUrl(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        return url('images/' . ltrim($filename, '/'));
    }
}
