<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AutorController extends Controller
{
    public function index(Request $request)
    {
//        abort_unless($request->user()->can('Autores'), 403, 'No autorizado');

        $perPage = (int) $request->get('per_page', 10);
        $perPage = max(1, min($perPage, 100));
        $search = trim((string) $request->get('search', ''));

        $query = Autor::query()->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', "%{$search}%")
                    ->orWhere('lugar_nacimiento', 'like', "%{$search}%")
                    ->orWhere('profesion', 'like', "%{$search}%")
                    ->orWhere('genero_literario', 'like', "%{$search}%")
                    ->orWhere('premios', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function store(Request $request)
    {
//        abort_unless($request->user()->can('Autores'), 403, 'No autorizado');

        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'lugar_nacimiento' => 'nullable|string|max:255',
            'fecha_fallecimiento' => 'nullable|date|after_or_equal:fecha_nacimiento',
            'profesion' => 'nullable|string|max:255',
            'genero_literario' => 'nullable|string|max:255',
            'premios' => 'nullable|string|max:4000',
        ]);

        return Autor::create($validated);
    }

    public function update(Request $request, Autor $autor)
    {
//        abort_unless($request->user()->can('Autores'), 403, 'No autorizado');

        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'lugar_nacimiento' => 'nullable|string|max:255',
            'fecha_fallecimiento' => 'nullable|date|after_or_equal:fecha_nacimiento',
            'profesion' => 'nullable|string|max:255',
            'genero_literario' => 'nullable|string|max:255',
            'premios' => 'nullable|string|max:4000',
        ]);

        $autor->update($validated);

        return $autor->fresh();
    }

    public function destroy(Request $request, Autor $autor)
    {
//        abort_unless($request->user()->can('Autores'), 403, 'No autorizado');

        $autor->delete();

        return response()->json(['message' => 'Autor eliminado']);
    }

    public function updateFotografia(Request $request, Autor $autor)
    {
//        abort_unless($request->user()->can('Autores'), 403, 'No autorizado');

        $request->validate([
            'fotografia' => 'required|image|max:5120',
        ]);

        if (!$request->hasFile('fotografia')) {
            return response()->json(['message' => 'No se ha enviado un archivo'], 400);
        }

        $file = $request->file('fotografia');
        $filename = 'autor_' . time() . '_' . $autor->id . '.jpg';
        $path = public_path('images/' . $filename);

        $manager = new ImageManager(new Driver());
        $manager->read($file->getPathname())
            ->cover(500, 620)
            ->toJpeg(78)
            ->save($path);

        $autor->update([
            'fotografia' => $filename,
        ]);

        return response()->json([
            'message' => 'Fotografia actualizada',
            'fotografia' => $filename,
        ]);
    }
}
