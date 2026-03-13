<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Crear el usuario administrador por defecto (bibliotecaria)
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sec7.edu.ar'],
            [
                'name' => 'Bibliotecaria SEC7',
                'password' => Hash::make('sec7admin2024'),
                'role' => 'admin',
                'active' => true,
            ]
        );

        $this->command->info('Usuario administrador creado:');
        $this->command->info('  Email: admin@sec7.edu.ar');
        $this->command->info('  Password: sec7admin2024');
        $this->command->warn('  IMPORTANTE: Cambie esta contrasena despues del primer inicio de sesion.');
    }
}
