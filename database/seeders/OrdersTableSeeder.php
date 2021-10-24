<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Services\StoredenAPI;
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
        $orders = (new StoredenAPI())->getOrders();
        Order::insert($orders);
    }
}
