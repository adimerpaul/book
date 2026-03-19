<?php

namespace App\Http\Controllers;

use App\Models\Cox;
use Illuminate\Http\Request;

class CoxController extends Controller
{
    public function show()
    {
        return $this->config();
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number' => 'required|string|max:30',
        ]);

        $config = $this->config();
        $config->update($validated);

        return $config->fresh();
    }

    private function config(): Cox
    {
        return Cox::firstOrCreate(
            ['id' => 1],
            ['whatsapp_number' => '59170000000']
        );
    }
}
