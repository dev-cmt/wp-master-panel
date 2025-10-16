<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prefix',
        'base_url',
        'api_key',
        'api_secret',
        'custom_secret',
        'ep_order_create',
        'ep_order_update',
        'ep_order_status',
        'ep_order_delete',
        'status',
    ];

    // Scope to filter only active stores
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
