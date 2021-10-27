<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     *
     * @param Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        if ($product->isDirty('image_url') && Storage::disk('public')->exists($product->getOriginal('image_url'))) {
            Storage::disk('public')->delete($product->getOriginal('image_url'));
        }
    }

    /**
     * Handle the Product "deleting" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleting(Product $product)
    {
        if (Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->deleteDirectory("images/products/$product->id");
        }
    }
}
