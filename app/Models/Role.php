<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_at',
        'label',
        'updated_at',
    ];

    // Un rôle peut avoir plusieurs users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
