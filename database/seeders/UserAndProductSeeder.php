<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;

class UserAndProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'is_admin' => true, 
        ]);

        // Seed Regular User
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
        ]);

        // Seed Sample Products
        $products = [
            ['name' => 'Product 1', 'price' => 10.99, 'stock' => 100],
            ['name' => 'Product 2', 'price' => 15.99, 'stock' => 200],
            ['name' => 'Product 3', 'price' => 20.99, 'stock' => 150],
            ['name' => 'Product 4', 'price' => 25.99, 'stock' => 300],
            ['name' => 'Product 5', 'price' => 30.99, 'stock' => 400],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Admin, User, and 5 Sample Products seeded successfully!');
    }
}
