<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('libros')
            ->whereNull('precio')
            ->update(['precio' => 100]);
    }

    public function down(): void
    {
        //
    }
};
