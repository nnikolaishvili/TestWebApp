<?php

namespace Database\Seeders;

use App\Models\{Order, Product, Role, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!Role::count()) {
            $this->call(RolesTableSeeder::class);
        }
        if (!User::count()) {
            $this->call(UsersTableSeeder::class);
        }
        if (!Order::count()) {
            $this->call(OrdersTableSeeder::class);
        }
        if (!Product::count()) {
            $this->call(ProductsTableSeeder::class);
        }
    }
}
