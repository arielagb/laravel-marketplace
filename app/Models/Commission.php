<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'shop_id',
        'rate',
        'amount',
        'is_settled',
        'notes',
        'calculated_at',
    ];

    protected $casts = [
        'is_settled'     => 'boolean',
        'rate'           => 'decimal:2',
        'amount'         => 'decimal:2',
        'calculated_at'  => 'datetime',
    ];

    // Une commission appartient à une ligne de commande
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    // Une commission appartient à une boutique
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}