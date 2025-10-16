<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WpOrder extends Model
{
    protected $fillable = [
        'wp_order_id',
        'invoice_no',
        'store_id',
        'customer_name',
        'email',
        'phone',
        'total',
        'billing',
        'shipping',
        'order_data',
        'status'
    ];

    protected $casts = [
        'billing' => 'array',
        'shipping' => 'array',
        'order_data' => 'array',
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WpOrderItem::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
