<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Créez un super utilisateur avec un email spécifique
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('supersecurepassword'), // Assurez-vous que le mot de passe est crypté
            'is_admin' => true,  // Marquer cet utilisateur comme administrateur
        ]);
    }
}
