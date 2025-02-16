<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // 一括代入可能な属性
    protected $fillable = ['name', 'price', 'description', 'img_url','image','sold_out'];
    
    protected $attributes = [
    'condition' => 'new', // デフォルト値
    ];

    // カテゴリーとのリレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    // コメントとのリレーション
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいねとのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 補足的なアクセサ（例: フォーマットされた価格）
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price) . '円';
    }

    public function seller()
    {
    return $this->belongsTo(User::class, 'seller_id');
    }

    public function getImageUrlAttribute()
    {
        // img_url が存在する場合はそれを返し、存在しない場合は image を返す
        if (!empty($this->img_url)) {
            return $this->img_url;
        }

        if (!empty($this->image)) {
            return asset('storage/' . $this->image);
        }

        // デフォルト画像（オプション）
        return asset('images/default.png');
    }

}
