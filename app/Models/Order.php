<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    const DELETED_AT = 'canceled_at';
    const TABLE_HEADERS = ['order ID', 'total', 'date', 'status'];

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
