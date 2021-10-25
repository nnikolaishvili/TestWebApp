<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const TABLE_HEADERS = ['uid', 'title', 'price', 'final_price', 'code', 'status', 'image_url'];

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
     * Get products status name
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return $this->status ? 'Visible' : 'Not visible';
    }
}
