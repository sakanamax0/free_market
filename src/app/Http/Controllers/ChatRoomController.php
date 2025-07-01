<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ChatRoom;

class ChatRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function enter(Item $item)
    {
        $userId = auth()->id();

        
        if ($userId === $item->seller_id) {
            return redirect()
                ->route('item.show', $item->id)
                ->with('error', '自分の商品にはチャットできません。');
        }

        
        $chatRoom = ChatRoom::where('item_id', $item->id)
            ->where('buyer_id', $userId)
            ->where('seller_id', $item->seller_id)
            ->first();

       
        if (!$chatRoom) {
            $chatRoom = ChatRoom::create([
                'item_id' => $item->id,
                'buyer_id' => $userId,
                'seller_id' => $item->seller_id,
                'is_purchased' => false, 
            ]);
        }

        return redirect()->route('chatroom.show', $chatRoom->id);
    }
}
