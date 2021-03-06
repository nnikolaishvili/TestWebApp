<?php

namespace Database\Seeders;

use App\Jobs\FetchOrdersJob;
use Exception;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        dispatch_sync(new FetchOrdersJob);
    }
}
