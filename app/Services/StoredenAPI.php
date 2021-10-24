<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StoredenAPI
{
    public $endPointUrl;
    public $apiVersion;
    public $key;
    public $exchange;

    public function __construct()
    {
        $this->endPointUrl = config('services.storeden.endpoint_url');
        $this->apiVersion = config('services.storeden.api_version');
        $this->key = config('services.storeden.key');
        $this->exchange = config('services.storeden.exchange');
    }

    /**
     * Fetch order statuses
     *
     * @throws Exception
     */
    public function getOrdersStatuses()
    {
        try {
            $response = $this->makeRequest('/orders/statuses.json');
            return call_user_func_array('array_merge', $response->json());
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Fetch orders
     *
     * @throws Exception
     */
    public function getOrders()
    {
        try {
            $response = $this->makeRequest('/orders/list.json');
            $orders = $response->json();
            if (count($orders)) {
                foreach ($orders as $key => $order) {
                    $order['order_id'] = $order['orderID'];
                    $order['status_id'] = $order['status'] + 1;
                    $order['date'] = Carbon::parse($order['orderDate'])->toDateTimeString();
                    $order['created_at'] = now();
                    $order['updated_at'] = now();
                    unset($order['iso'], $order['orderID'], $order['orderDate'], $order['status'], $order['locale']);
                    $orders[$key] = $order;
                }
            }

            return $orders;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new Exception($exception->getMessage());
        }
    }

    private function getUrl(): string
    {
        return "$this->endPointUrl/$this->apiVersion";
    }

    private function getKey()
    {
        return $this->key;
    }

    private function getExchange()
    {
        return $this->exchange;
    }

    private function makeRequest($path)
    {
        $url = $this->getUrl() . $path;

        return Http::withHeaders([
            'key' => $this->getKey(),
            'exchange' => $this->getExchange()
        ])->get($url);
    }
}
