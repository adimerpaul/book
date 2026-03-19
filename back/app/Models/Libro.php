<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Libro extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table = 'libros';

    protected $fillable = [
        'slug',
        'titulo',
        'autor_id',
        'fecha_publicacion',
        'pais',
        'idioma',
        'genero',
        'subgenero',
        'editorial',
        'paginas',
        'precio',
        'contenido',
        'resumen_contenido',
        'reconocimiento',
        'portada',
        'drive_indice_url',
        'publicado_web',
    ];

    protected function casts(): array
    {
        return [
            'fecha_publicacion' => 'date',
            'precio' => 'decimal:2',
            'publicado_web' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $libro) {
            if ($libro->precio === null) {
                $libro->precio = 100;
            }

            if (!$libro->isDirty('titulo') && filled($libro->slug)) {
                return;
            }

            $libro->slug = static::generateUniqueSlug($libro->titulo, $libro->getKey());
        });
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    public function fotografias()
    {
        return $this->hasMany(LibroFotografia::class)->orderByDesc('id');
    }

    public static function generateUniqueSlug(string $titulo, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($titulo);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'libro';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::withTrashed()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
