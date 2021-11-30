<?php

namespace App\Observers;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageObserver
{
    /**
     * Handle the Product "updated" event.
     *
     * @param ProductImage $image
     * @return void
     */
    public function updated(ProductImage $image)
    {
        if ($image->isDirty('image_url') && Storage::disk('public')->exists($image->getOriginal('image_url'))) {
            Storage::disk('public')->delete($image->getOriginal('image_url'));
        }
    }
}
