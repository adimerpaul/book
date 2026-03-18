<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
//        $permission = Permission::firstOrCreate(['name' => 'Autores']);
//
//        User::query()
//            ->where('role', 'Administrador')
//            ->get()
//            ->each(function (User $user) use ($permission) {
//                if (!$user->hasPermissionTo($permission)) {
//                    $user->givePermissionTo($permission);
//                }
//            });
    }

    public function down(): void
    {
//        $permission = Permission::where('name', 'Autores')->first();
//
//        if (!$permission) {
//            return;
//        }
//
//        User::query()
//            ->where('role', 'Administrador')
//            ->get()
//            ->each(function (User $user) use ($permission) {
//                $user->revokePermissionTo($permission);
//            });
//
//        $permission->delete();
    }
};
