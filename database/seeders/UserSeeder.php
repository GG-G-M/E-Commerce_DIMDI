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
            'first_name' => 'Gilgre Gene',
            'middle_name' => 'G',
            'last_name' => 'Mantilla',
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
        $this->command->info('Email: gilgregenemantilla@gmail.com');
        $this->command->info('Password: 12345678');
    }
}