<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

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


    public function show($chatRoomId)
    {
        $chatRoom = ChatRoom::with(['item', 'buyer', 'seller', 'messages.sender'])->findOrFail($chatRoomId);
        $userId = Auth::id();

       
        if (!in_array($userId, [$chatRoom->buyer_id, $chatRoom->seller_id])) {
            return redirect()->route('index')->with('error', 'このチャットルームにアクセスできません。');
        }

      
        if ($userId === $chatRoom->buyer_id) {
            $otherUser = $chatRoom->seller;
            $isBuyer = true;
            $isSeller = false;
        } else {
            $otherUser = $chatRoom->buyer;
            $isBuyer = false;
            $isSeller = true;
        }

       
        $messages = $chatRoom->messages()->with('sender')->get();

       
        $hasRated = Rating::where('from_user_id', $chatRoom->buyer_id)
            ->where('to_user_id', $chatRoom->seller_id)
            ->where('item_id', $chatRoom->item_id)
            ->exists();

        
        $showModal = $isSeller && $hasRated;

        
        $otherChatRooms = ChatRoom::where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)
                  ->orWhere('seller_id', $userId);
            })
            ->where('id', '<>', $chatRoom->id)
            ->get();

        return view('chat.show', [
            'chatRoom' => $chatRoom,
            'messages' => $messages,
            'otherUserId' => $otherUser->id,
            'otherUserName' => $otherUser->name,
            'otherUserPhotoUrl' => $otherUser->profile_photo ? asset('storage/' . $otherUser->profile_photo) : null,
            'isBuyer' => $isBuyer,
            'isSeller' => $isSeller,
            'hasRated' => $hasRated,
            'showModal' => $showModal,
            'otherChatRooms' => $otherChatRooms,
        ]);
    }
}
