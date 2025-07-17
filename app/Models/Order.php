<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id', 'customer_id', 'address', 'items', 'total', 'created_at'
    ];

    public $timestamps = false;

    protected $casts = [
        'items' => 'array',
        'created_at' => 'datetime'
    ];
}
