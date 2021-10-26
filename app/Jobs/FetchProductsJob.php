<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\Interfaces\FetchProductsInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FetchProductsJob implements ShouldQueue
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
     */
    public function handle(FetchProductsInterface $fetchProducts)
    {
        $products = $fetchProducts->fetchProducts();

        if (Product::count()) {
            Product::truncate();
            Storage::disk('public')->deleteDirectory('images/products');
        }

        Product::insert($products);
    }
}
