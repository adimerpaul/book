<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Autor extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table="autores";
    protected $fillable = [
        'nombre_completo',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'fecha_fallecimiento',
        'profesion',
        'genero_literario',
        'premios',
        'fotografia',
    ];

//    protected function casts(): array
//    {
//        return [
//            'fecha_nacimiento' => 'date',
//            'fecha_fallecimiento' => 'date',
//        ];
//    }

    public function libros()
    {
        return $this->hasMany(Libro::class, 'autor_id');
    }
}
