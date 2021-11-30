<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SearchableTrait;

    const ITEMS_PER_PAGE = 10;

    const MAX_IMAGE_SIZE = 512; // 5 mb

    const STATUSES = ['Not visible', 'Visible'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uid',
        'title',
        'price',
        'final_price',
        'code',
        'status',
    ];

    /**
     * The searchable fields
     *
     * @var string[]
     */
    protected $searchable = [
        'uid',
        'title',
    ];

    /**
     * Get product images
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get products status name
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return $this->status ? 'Visible' : 'Not visible';
    }
}
