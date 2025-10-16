<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WpOrderItem extends Model
{
    protected $fillable = [
        'wp_order_id',
        'product_id',
        'product_name',
        'sku',
        'quantity',
        'subtotal',
        'total',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(WpOrder::class);
    }
}
