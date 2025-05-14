<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'adresse' => '123 Admin Street',
            'telephone' => '987654321',
            'role' => 'admin',
            'email' => 'admin@system.com',
            'password' => Hash::make('admin123'),
            'statut' => 'actif',
        ]);
    }
}
