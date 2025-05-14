<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'adresse' => '123 Rue Admin',
            'telephone' => '0123456789',
            'role' => 'administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'statut' => 'actif'
        ]);

        // Créer un éditeur
        User::create([
            'nom' => 'Editeur',
            'prenom' => 'Principal',
            'adresse' => '456 Rue Editeur',
            'telephone' => '0234567891',
            'role' => 'editeur',
            'email' => 'editeur@example.com',
            'password' => Hash::make('editeur123'),
            'statut' => 'actif'
        ]);

        // Créer un gestionnaire
        User::create([
            'nom' => 'Gestionnaire',
            'prenom' => 'Commercial',
            'adresse' => '789 Rue Gestionnaire',
            'telephone' => '0345678912',
            'role' => 'gestionnaire',
            'email' => 'gestionnaire@example.com',
            'password' => Hash::make('gestionnaire123'),
            'statut' => 'actif'
        ]);

        // Créer un client
        User::create([
            'nom' => 'Client',
            'prenom' => 'Test',
            'adresse' => '012 Rue Client',
            'telephone' => '0456789123',
            'role' => 'client',
            'email' => 'client@example.com',
            'password' => Hash::make('client123'),
            'statut' => 'actif'
        ]);
    }
}
