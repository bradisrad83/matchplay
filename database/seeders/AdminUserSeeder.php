<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'brad.m.goldsmith@gmail.com'], // Change to your email
            [
                'name' => 'Brad Goldsmith',
                'password' => Hash::make('password123'), // Change to a secure password
                'nickname' => 'Stork Slayer', 
                'phone_number' => '9183464253',
                'active' => true,
                'team_id' => 1,
                'role' => 'superadmin',
            ]
        );
    
    }
}
