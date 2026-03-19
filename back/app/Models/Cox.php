<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cox extends Model
{
    use HasFactory;

    protected $table = 'cox';

    protected $fillable = [
        'whatsapp_number',
    ];
}
