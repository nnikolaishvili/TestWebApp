<?php

namespace App\Services\Storeden;

use App\Models\OrderStatus;
use App\Services\Interfaces\FetchOrdersInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StoredenOrdersService implements FetchOrdersInterface
{
    const ORDERS_PATH = 'orders/list.json';
    const ORDER_STATUSES_PATH = 'orders/statuses.json';

    protected $apiUrl;
    protected $key;
    protected $exchange;

    public function __construct()
    {
        $this->apiUrl = config('services.storeden.api_url');
        $this->key = config('services.storeden.key');
        $this->exchange = config('services.storeden.exchange');
    }

    /**
     * Fetch orders
     *
     * @throws Exception
     */
    public function fetchOrders(): array
    {
        try {
            if (!OrderStatus::count()) {
                $statuses = $this->fetchStatuses();
                OrderStatus::insert($statuses);
            }

            $statuses = OrderStatus::all();
            $response = $this->initializeRequest()->get(self::ORDERS_PATH);
            $orders = $response->json();

            return array_map(function ($order) use ($statuses) {
                return [
                    'order_id' => $order['orderID'],
                    'status_id' => $statuses->where('foreign_api_id', $order['status'])->first()->id,
                    'total' => $order['total'],
                    'date' => Carbon::parse($order['orderDate'])->toDateTimeString(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $orders);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Fetch order statuses
     *
     * @throws Exception
     */
    public function fetchStatuses(): array
    {
        try {
            $response = $this->initializeRequest()->get(self::ORDER_STATUSES_PATH);
            $statuses = call_user_func_array('array_merge', $response->json());

            return array_map(function ($key, $value) {
                return [
                    'name' => $value,
                    'foreign_api_id' => $key,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, array_keys($statuses), array_values($statuses));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Initialize request
     *
     * @return PendingRequest
     */
    private function initializeRequest(): PendingRequest
    {
        return Http::baseUrl($this->apiUrl)->withHeaders([
            'key' => $this->key,
            'exchange' => $this->exchange
        ]);
    }
}
