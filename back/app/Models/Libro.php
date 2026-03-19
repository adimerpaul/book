<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Libro extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'autor_id',
        'fecha_publicacion',
        'pais',
        'idioma',
        'genero',
        'subgenero',
        'editorial',
        'paginas',
        'contenido',
        'resumen_contenido',
        'reconocimiento',
        'portada',
        'drive_indice_url',
        'publicado_web',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    public function fotografias()
    {
        return $this->hasMany(LibroFotografia::class)->orderByDesc('id');
    }
}
