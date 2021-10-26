<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageProcessingTrait
{
    /**
     * Store uploaded image into storage
     *
     * @param $filePath
     * @param $image
     * @return string
     */
    private function storeImage($filePath, $image): string
    {
        $imageOriginalName = $image->getClientOriginalName();

        return Storage::disk('public')->putFileAs($filePath, $image, $imageOriginalName);
    }
}
