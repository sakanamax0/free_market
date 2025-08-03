<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;
use App\Models\Item;

class UpdateIsPurchasedSeeder extends Seeder
{
    public function run()
    {
        // itemsテーブルのsold_outがtrueの商品のchatroomsのis_purchasedをtrueに更新
        $chatRooms = ChatRoom::with('item')->get();

        foreach ($chatRooms as $chatRoom) {
            if ($chatRoom->item && $chatRoom->item->sold_out) {
                $chatRoom->is_purchased = true;
            } else {
                $chatRoom->is_purchased = false;
            }
            $chatRoom->save();
        }
    }
}
