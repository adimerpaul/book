<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibroFotografia extends Model
{
    use HasFactory;

    protected $table = 'libro_fotografias';

    protected $fillable = [
        'libro_id',
        'archivo',
    ];

    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }
}
