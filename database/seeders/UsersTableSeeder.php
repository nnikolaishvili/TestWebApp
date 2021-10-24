<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('TestAdmin123'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Editor',
                'email' => 'editor@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('TestEditor123'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Assistant',
                'email' => 'assistant@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('TestAssistant123'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        User::insert($data);
    }
}
