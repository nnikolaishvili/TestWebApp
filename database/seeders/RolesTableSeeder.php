<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $names = ['Admin', 'Editor', 'Assistant'];
        foreach ($names as $key => $name) {
            $data[$key]['name'] = $name;
            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();
        }
        Role::insert($data);
    }
}
