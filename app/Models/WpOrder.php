<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wp_order_id','invoice_no', 'store_id','customer_name','email','phone','total','billing','shipping','order_data','status'
    ];

    protected $casts = [
        'billing' => 'array',
        'shipping' => 'array',
        'order_data' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(WpOrderItem::class, 'wp_order_id', 'id');
    }
}
