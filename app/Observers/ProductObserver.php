<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "deleting" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleting(Product $product)
    {
        Storage::disk('public')->deleteDirectory("images/products/$product->id");
    }
}
