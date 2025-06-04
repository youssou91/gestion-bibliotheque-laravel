<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUsersWithRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Principal',
            'adresse' => '123 Admin Street',
            'telephone' => '987654321',
            'role' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'statut' => 'actif',
        ]);

        // Créer un gestionnaire
        User::create([
            'nom' => 'Gestionnaire',
            'prenom' => 'Principal',
            'adresse' => '456 Manager Street',
            'telephone' => '123456789',
            'role' => 'gestionnaire',
            'email' => 'manager@test.com',
            'password' => Hash::make('manager123'),
            'statut' => 'actif',
        ]);

        // Créer un éditeur
        User::create([
            'nom' => 'Editeur',
            'prenom' => 'Principal',
            'adresse' => '789 Editor Street',
            'telephone' => '456789123',
            'role' => 'editeur',
            'email' => 'editor@test.com',
            'password' => Hash::make('editor123'),
            'statut' => 'actif',
        ]);
    }
}
