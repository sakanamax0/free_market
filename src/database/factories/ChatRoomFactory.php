<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatRoomFactory extends Factory
{
    protected $model = ChatRoom::class;

    public function definition()
    {
        return [
            'seller_id' => \App\Models\User::factory(),
            'buyer_id' => \App\Models\User::factory(),
            'item_id' => \App\Models\Item::factory(),
            'is_purchased' => false,
        ];
    }
}
