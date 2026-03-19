<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\LibroFotografia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LibroController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $perPage = max(1, min($perPage, 100));
        $search = trim((string) $request->get('search', ''));
        $autorId = $request->get('autor_id');
        $publicadoWeb = $request->get('publicado_web');

        $query = Libro::query()
            ->with(['autor:id,nombre_completo,fotografia', 'fotografias:id,libro_id,archivo'])
            ->withCount('fotografias')
            ->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('pais', 'like', "%{$search}%")
                    ->orWhere('idioma', 'like', "%{$search}%")
                    ->orWhere('genero', 'like', "%{$search}%")
                    ->orWhere('subgenero', 'like', "%{$search}%")
                    ->orWhere('editorial', 'like', "%{$search}%")
                    ->orWhereHas('autor', function ($autorQuery) use ($search) {
                        $autorQuery->where('nombre_completo', 'like', "%{$search}%");
                    });
            });
        }

        if ($autorId !== null && $autorId !== '') {
            $query->where('autor_id', (int) $autorId);
        }

        if ($publicadoWeb !== null && $publicadoWeb !== '') {
            $query->where('publicado_web', filter_var($publicadoWeb, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false);
        }

        return $query->paginate($perPage);
    }

    public function show(Libro $libro)
    {
        return $libro->load(['autor:id,nombre_completo,fotografia', 'fotografias:id,libro_id,archivo']);
    }

    public function store(Request $request)
    {
        $validated = $this->validateLibro($request);

        return DB::transaction(function () use ($validated) {
            return Libro::create($validated);
        });
    }

    public function update(Request $request, Libro $libro)
    {
        $validated = $this->validateLibro($request);

        return DB::transaction(function () use ($libro, $validated) {
            $libro->update($validated);
            return $libro->fresh(['autor:id,nombre_completo,fotografia', 'fotografias:id,libro_id,archivo']);
        });
    }

    public function destroy(Libro $libro)
    {
        $libro->fotografias()->delete();
        $libro->delete();

        return response()->json(['message' => 'Libro eliminado']);
    }

    public function updatePortada(Request $request, Libro $libro)
    {
        $request->validate([
            'portada' => 'required|file|max:5120',
        ]);

        if (!$request->hasFile('portada')) {
            return response()->json(['message' => 'No se ha enviado una portada'], 400);
        }

        $this->ensureImageFile($request->file('portada'));

        $filename = $this->storeOriginalImage($request->file('portada'), 'libro_portada_' . $libro->id);

        $libro->update([
            'portada' => $filename,
        ]);

        return response()->json([
            'message' => 'Portada actualizada',
            'portada' => $filename,
        ]);
    }

    public function addFotografias(Request $request, Libro $libro)
    {
        $request->validate([
            'fotografias' => 'required|array|min:1',
            'fotografias.*' => 'file|max:5120',
        ]);

        $created = [];
        foreach ($request->file('fotografias', []) as $index => $file) {
            $this->ensureImageFile($file);
            $filename = $this->storeOriginalImage($file, 'libro_galeria_' . $libro->id . '_' . $index);
            $created[] = $libro->fotografias()->create([
                'archivo' => $filename,
            ]);
        }

        return response()->json([
            'message' => 'Fotografias agregadas',
            'items' => $created,
        ]);
    }

    public function removeFotografia(Libro $libro, LibroFotografia $fotografia)
    {
        abort_if($fotografia->libro_id !== $libro->id, 404, 'Fotografia no encontrada');

        $fotografia->delete();

        return response()->json(['message' => 'Fotografia eliminada']);
    }

    private function validateLibro(Request $request): array
    {
        return $request->validate([
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|integer|exists:autores,id',
            'fecha_publicacion' => 'nullable|date',
            'pais' => 'nullable|string|max:255',
            'idioma' => 'nullable|string|max:255',
            'genero' => 'nullable|string|max:255',
            'subgenero' => 'nullable|string|max:255',
            'editorial' => 'nullable|string|max:255',
            'paginas' => 'nullable|integer|min:1',
            'precio' => 'nullable|numeric|min:0|max:999999.99',
            'contenido' => 'nullable|string|max:20000',
            'resumen_contenido' => 'nullable|string|max:5000',
            'reconocimiento' => 'nullable|string|max:4000',
            'drive_indice_url' => 'nullable|url|max:1000',
            'publicado_web' => 'nullable|boolean',
        ]);
    }

    private function ensureImageFile($file): void
    {
        $mimeType = (string) $file->getMimeType();
        if (!Str::startsWith($mimeType, 'image/')) {
            abort(response()->json([
                'message' => 'El archivo debe ser una imagen valida.',
                'errors' => [
                    'fotografias' => ['El archivo debe ser una imagen valida.'],
                ],
            ], 422));
        }
    }

    private function storeOriginalImage($file, string $prefix): string
    {
        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'bin';
        $filename = $prefix . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . strtolower($extension);
        $path = public_path('images/' . $filename);
        $file->move(dirname($path), basename($path));
        return $filename;
    }
}
