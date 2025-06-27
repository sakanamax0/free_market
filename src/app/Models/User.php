<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function sellItems()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(Order::class, 'buyer_id'); 
    }

    
    public function address()
    {
        return $this->hasOne(Address::class); 
    }
}

