<?php

namespace App\Services\Storeden;

use App\Services\Interfaces\FetchProductsInterface;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StoredenProductsService implements FetchProductsInterface
{
    const PRODUCTS_PATH = 'products/list.json?nodes=title';

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
     * @throws Exception
     */
    public function fetchProducts(): array
    {
        try {
            $response = $this->initializeRequest()->get(self::PRODUCTS_PATH);
            $products = $response->json();
            return array_map(function ($product) {
                return [
                    'uid' => $product['uid'],
                    'title' => $product['title'],
                    'price' => $product['price'],
                    'final_price' => $product['final_price'],
                    'code' => $product['code'],
                    'status' => $product['status'],
                    'image_url' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $products);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new Exception($exception->getMessage());
        }
    }

    private function initializeRequest(): PendingRequest
    {
        return Http::baseUrl($this->apiUrl)->withHeaders([
            'key' => $this->key,
            'exchange' => $this->exchange
        ]);
    }
}
