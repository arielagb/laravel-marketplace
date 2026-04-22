<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'status',
        'total_amount',
        'shipping_fee',
        'payment_provider',
        'payment_reference',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'total_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}