<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Correr el seeder dos veces ya no falla por correo duplicado
        if (User::where('email', 'admin@checador.com')->exists()) {
            $this->command?->info('El administrador admin@checador.com ya existe; no se modificó.');
            return;
        }

        // Sin ADMIN_SEED_PASSWORD en el .env se genera una contraseña
        // aleatoria (antes quedaba "password123", fácil de adivinar).
        $password = env('ADMIN_SEED_PASSWORD') ?: Str::password(16);

        User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@checador.com',
            'password' => Hash::make($password),
            'role' => 'admin', // Asignamos el rol admin
        ]);

        if (!env('ADMIN_SEED_PASSWORD')) {
            $this->command?->warn("Contraseña generada para admin@checador.com: {$password}");
            $this->command?->warn('Guárdala y cámbiala al entrar por primera vez.');
        }
    }
}
