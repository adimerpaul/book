<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cox', function (Blueprint $table) {
            $table->id();
            $table->string('whatsapp_number', 30)->default('59170000000');
            $table->timestamps();
        });

        DB::table('cox')->insert([
            'id' => 1,
            'whatsapp_number' => '59170000000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cox');
    }
};
