<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class HeroSlider extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table = 'hero_sliders';

    protected $fillable = [
        'eyebrow',
        'title',
        'description',
        'badge',
        'theme',
        'primary_cta_label',
        'primary_cta_url',
        'secondary_cta_label',
        'secondary_cta_url',
        'libro_principal_id',
        'libro_secundario_id',
        'libro_terciario_id',
        'orden',
        'publicado_web',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'publicado_web' => 'boolean',
        ];
    }

    public function libroPrincipal()
    {
        return $this->belongsTo(Libro::class, 'libro_principal_id');
    }

    public function libroSecundario()
    {
        return $this->belongsTo(Libro::class, 'libro_secundario_id');
    }

    public function libroTerciario()
    {
        return $this->belongsTo(Libro::class, 'libro_terciario_id');
    }
}
