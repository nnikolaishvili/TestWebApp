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
    const PRODUCT_IMAGES_PATH = 'products/image.json?uid=';

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
     * Fetch products
     *
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
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $products);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Fetch images by product uid
     *
     * @throws Exception
     */
    public function fetchProductImages($productUid)
    {
        try {
            $response = $this->initializeRequest()->get(self::PRODUCT_IMAGES_PATH . $productUid);

            return $response->json();
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
