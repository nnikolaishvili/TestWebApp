<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Services\StoredenAPI;
use Exception;
use Illuminate\Database\Seeder;

class OrdersStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $data = [];
        $statuses = (new StoredenAPI())->getOrdersStatuses();
        foreach ($statuses as $key => $status) {
            $data[$key]['name'] = $status;
            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();
        }
        Status::insert($data);
    }
}
