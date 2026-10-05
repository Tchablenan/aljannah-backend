<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Crée ou met à jour le super administrateur à partir des variables
     * ADMIN_EMAIL et ADMIN_PASSWORD (jamais de mot de passe dans le code :
     * le dépôt est public).
     */
    public function run(): void
    {
        // Lu via config/auth.php : env() est vide une fois la config mise en cache
        $admin = config('auth.super_admin');
        $email = $admin['email'] ?? null;
        $password = $admin['password'] ?? null;

        if (!$email || !$password) {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD non définis : super admin non créé.');
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $admin['name'] ?? 'Super Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
            ]
        );
    }
}
