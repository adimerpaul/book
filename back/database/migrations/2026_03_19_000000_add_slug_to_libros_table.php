<?php

use App\Models\Libro;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
        });

        Libro::withTrashed()->orderBy('id')->each(function (Libro $libro) {
            $libro->forceFill([
                'slug' => Libro::generateUniqueSlug($libro->titulo, $libro->id),
            ])->saveQuietly();
        });

        Schema::table('libros', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
