<?php

namespace App\Models;
use App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'is_active',
        'is_deleted',
        'commission_override',
        'settings',
        'phone',
        'address',
        'payment_method',
        'payment_details',
        'id_document',
        'category_id',
        'status',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'settings' => 'array', 
        'commission_override' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Une boutique a plusieurs commissions
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    // Retourne le taux de commission applicable à cette boutique
    // Si commission_override est défini, on l'utilise
    // Sinon on retourne le taux par défaut de 10%
    public function getCommissionRate(): float
    {
        return $this->commission_override ?? 10.0;
    }
}