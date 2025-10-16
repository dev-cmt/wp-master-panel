<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'invoice_no',
        'order_date',
        'customer_name',
        'email',
        'phone',
        'total',
        'paid',
        'due',
        'source',
        'courier_id',
        'courier_city_id',
        'courier_zone_id',
        'shipping',
        'order_data',
        'status',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'shipping' => 'array',
        'order_data' => 'array',
    ];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /** 🔗 Many-to-Many: Orders ↔ Products (through order_items) */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot('quantity', 'price')->withTimestamps();
    }

}
