<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',         
        'is_active',    
        'is_blocked',   
        'is_deleted',   
        'profile_meta', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_blocked' => 'boolean',
        'is_deleted' => 'boolean',
        'profile_meta' => 'array', 
    ];

    // Un user appartient à un rôle
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Un seller a un seul shop
    public function shop() {
        return $this->hasOne(Shop::class);
    }

    // Un buyer peut avoir plusieurs commandes
    public function orders() {
        return $this->hasMany(Order::class);
    }

    // Un user peut écrire plusieurs reviews
    public function reviews() {
        return $this->hasMany(Review::class);
    }

   public function isAdmin()
    {
        return $this->role?->name === 'admin';
    }

    public function isSeller()
    {
        return $this->role?->name === 'seller';
    }

    public function isBuyer()
    {
        return $this->role?->name === 'buyer';
    }
}