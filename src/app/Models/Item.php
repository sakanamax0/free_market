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

    // カテゴリとの多対多リレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    // コメントとの1対多リレーション
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいねとの1対多リレーション
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 出品者とのリレーション
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // 売却情報とのリレーション
    public function soldItems()
    {
        return $this->hasMany(SoldItem::class, 'item_id');
    }

    // 表示用価格
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price) . '円';
    }

    // 画像URLの取得
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

    // 商品が売り切れかどうかを判定
    public function sold()
    {
        return $this->sold_out;
    }

    // 現在のログインユーザーが出品者かどうかを判定
    public function mine()
    {
        return Auth::check() && $this->seller_id === Auth::id();
    }
}
