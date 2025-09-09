<?php

// Salve este código em: database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garante que o papel de Admin existe
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Cria o utilizador administrador se ele não existir
        $adminUser = User::firstOrCreate(
            ['email' => 'carlosvitor86@gmail.com'], // Email único para encontrar o utilizador
            [
                'name' => 'Carlos Vitor',
                'password' => Hash::make('clarice3011'), // Altere para uma senha segura
            ]
        );

        // Atribui o papel de Admin ao utilizador
        $adminUser->assignRole($adminRole);
    }
}

