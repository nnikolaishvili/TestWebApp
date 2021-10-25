<?php

namespace Database\Seeders;

use App\Jobs\FetchProductsJob;
use Exception;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        dispatch_sync(new FetchProductsJob);
    }
}
