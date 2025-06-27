<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'img_url',
        'image',
        'sold_out',
        'is_sold',
        'seller_id'
    ];

    protected $attributes = [
        'condition' => 'new',
    ];

    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

   
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    
    public function soldItems()
    {
        return $this->hasMany(SoldItem::class, 'item_id');
    }

    
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price) . '円';
    }

    
    public function getImageUrlAttribute()
    {
        if (!empty($this->img_url)) {
            return $this->img_url;
        }

        if (!empty($this->image)) {
            return asset('storage/' . $this->image);
        }

        return asset('images/default.png');
    }

    
    public function sold()
    {
        return $this->sold_out;
    }

    
    public function mine()
    {
        return Auth::check() && $this->seller_id === Auth::id();
    }
}
