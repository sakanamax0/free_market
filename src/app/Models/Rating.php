<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'item_id',
        'score',
    ];

    // 評価したユーザー
public function fromUser()
{
    return $this->belongsTo(User::class, 'from_user_id');
}

// 評価されたユーザー
public function toUser()
{
    return $this->belongsTo(User::class, 'to_user_id');
}

// 評価された商品
public function item()
{
    return $this->belongsTo(Item::class);
}

}

