<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'image_url'
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
     * Get products status name
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return $this->status ? 'Visible' : 'Not visible';
    }

    /**
     * Add image full path attribute
     *
     * @return string|null
     */
    public function getImageFullPathAttribute(): ?string
    {
        if ($this->image_url) {
            if (Str::startsWith($this->image_url, ['http', 'https'])) {
                return $this->image_url;
            }

            return config('app.url') . '/storage/' . $this->image_url;
        }

        return null;
    }
}
