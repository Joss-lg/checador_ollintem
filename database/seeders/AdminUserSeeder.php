<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@checador.com',
            'password' => Hash::make(env('ADMIN_SEED_PASSWORD', 'password123')),
            'role' => 'admin', // Asignamos el rol admin
        ]);
    }
}