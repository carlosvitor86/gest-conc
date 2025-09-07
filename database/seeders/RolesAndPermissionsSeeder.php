<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa o cache de roles e permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Cria os papéis (Roles)
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Gestor de Concurso']);
        Role::create(['name' => 'Observador']);
    }
}
