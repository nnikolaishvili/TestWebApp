<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HasFactory, SearchableTrait;

    const ITEMS_PER_PAGE = 10;

    const DELETED_AT = 'canceled_at';

    /**
     * The dates
     *
     * @var string[]
     */
    protected $dates = ['date'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'total',
        'date',
        'status_id',
        'canceled_at'
    ];

    /**
     * The searchable fields
     *
     * @var string[]
     */
    protected $searchable = [
        'order_id'
    ];

    /**
     * Order status relation
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     * Soft delete order
     *
     * @return bool|null
     */
    public function cancel(): ?bool
    {
        return $this->delete();
    }
}
