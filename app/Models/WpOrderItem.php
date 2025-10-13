<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['wp_order_id','product_id','product_name', 'sku','quantity','subtotal','total','meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(WpOrder::class, 'wp_order_id', 'id');
    }
}
