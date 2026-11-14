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
        User::updateOrCreate(
            ['email' => 'admin@coffebar.id'],
            [
                'name' => 'Owner & Admin CoffeBar',
                'phone' => '081234567890',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@coffebar.id'],
            [
                'name' => 'Budi Kasir',
                'phone' => '081234567891',
                'role' => 'cashier',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@coffebar.id'],
            [
                'name' => 'Ahmad Pelanggan',
                'phone' => '081234567892',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );
    }
}
