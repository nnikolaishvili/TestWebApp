<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'image_url',
    ];

    /**
     * Get product of the image
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Add image full path attribute
     *
     * @return string|null
     */
    public function getFullPathAttribute(): ?string
    {
        if (Str::startsWith($this->image_url, ['http', 'https'])) {
            return $this->image_url;
        }

        return config('app.url') . '/storage/' . $this->image_url;
    }
}
