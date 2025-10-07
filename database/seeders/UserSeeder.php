<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Gilgre Gene G. Mantilla',
            'email' => 'gilgregenemantilla@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'customer',
            'phone' => '123-456-7890',
            'address' => 'Bankal Skibidi',
            'city' => 'Davao City',
            'state' => 'Davao State',
            'zip_code' => '12345',
            'country' => 'Philippines'
        ]);

        $this->command->info('Customer created successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: 12345678');
    }
}