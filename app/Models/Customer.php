<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postcode',
        'country',
    ];
}
