<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Rating;

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


    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'to_user_id');
    }


    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'from_user_id');
    }


    public function averageRating()
    {
        return $this->ratingsReceived()->avg('score') ?? 0;
    }

    public function soldItems()
    {
        return $this->hasMany(SoldItem::class, 'user_id');
    }
    

}
