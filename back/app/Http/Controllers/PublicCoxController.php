<?php

namespace App\Http\Controllers;

use App\Models\Cox;

class PublicCoxController extends Controller
{
    public function show()
    {
        $config = Cox::firstOrCreate(
            ['id' => 1],
            ['whatsapp_number' => '59170000000']
        );

        return response()->json([
            'data' => [
                'whatsapp_number' => $config->whatsapp_number,
            ],
        ]);
    }
}
