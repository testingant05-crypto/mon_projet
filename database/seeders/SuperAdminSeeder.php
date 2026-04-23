<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // unique
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'phone' => '00000000',
                'password' => Hash::make('admin123'),
                'role' => 'super-admin',
                'email_verified_at' => now(), // déjà vérifié
            ]
        );
    }
}
