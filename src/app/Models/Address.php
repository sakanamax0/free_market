<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zipcode',
        'details',
        'building',
    ];

    // Address モデルと User モデルのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);  // Address が User に属するリレーション
    }
}
