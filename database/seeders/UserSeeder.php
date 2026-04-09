<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Ouattara N\'Golo',
            'email' => 'admin@ivoireshop.ci',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+225 07 18 95 95 92',
            'is_active' => true,
        ]);

        // Clients de test
        $clients = [
            ['name' => 'Koné Aminata',  'email' => 'kone.aminata@test.ci'],
            ['name' => 'Traoré Bakary', 'email' => 'traore.bakary@test.ci'],
            ['name' => 'Diallo Mariam', 'email' => 'diallo.mariam@test.ci'],
        ];

        foreach ($clients as $client) {
            User::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
            ]);
        }
    }
}
