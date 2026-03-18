<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
        $userAdmin = User::firstOrCreate([
            'username' => 'admin',
        ], [
            'name' => 'Admin User',
            'role' => 'Administrador',
            'avatar' => 'default.png',
            'email' => '',
            'password' => 'admin123Admin',
        ]);
        $permisos = [
            'Dashboard',
            'Usuarios',
//            'Graderias',
//            'Reservas',
//            'Idiomas',
//            'Precios',
//            'Reportes'
        ];
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
        $userAdmin->givePermissionTo(Permission::all());

        $autoresActuales = Autor::count();
        if ($autoresActuales < 1000) {
            Autor::factory(1000 - $autoresActuales)->create();
        }
//        $this->call([
//                EventoSeeder::class,
////                EventoHorarioSeeder::class,
//        ]);
    }
}
