<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('returns public cox configuration', function () {
    $response = $this->getJson('/api/public/cox');

    $response->assertOk()
        ->assertJsonPath('data.whatsapp_number', '59170000000');
});

it('updates cox configuration from admin api', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->putJson('/api/cox', [
        'whatsapp_number' => '59171234567',
    ]);

    $response->assertOk()
        ->assertJsonPath('whatsapp_number', '59171234567');
});
