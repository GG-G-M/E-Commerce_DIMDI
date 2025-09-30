<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@dimdi.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'phone' => '123-456-7890',
            'address' => 'Admin Address',
            'city' => 'Admin City',
            'state' => 'Admin State',
            'zip_code' => '12345',
            'country' => 'Admin Country'
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@dimdi.com');
        $this->command->info('Password: 12345678');
    }
}