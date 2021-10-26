<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Interfaces\FetchOrdersInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(FetchOrdersInterface $fetchOrders)
    {
        $orders = $fetchOrders->fetchOrders();

        if (Order::count()) {
            Order::truncate();
        }

        Order::insert($orders);
    }
}
