<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'sku',
        'description',
        'price',
        'stock_qty',
        'image',
        'attribute',
        'status',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')->withPivot('quantity', 'price')->withTimestamps();
    }

}
