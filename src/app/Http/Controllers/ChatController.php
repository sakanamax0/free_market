<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\Rating;
use App\Models\User;

class ChatController extends Controller
{
    public function show($chatRoomId)
    {
        $chatRoom = ChatRoom::with('item')->findOrFail($chatRoomId);

        $userId = auth()->id();

        
        $otherUser = $userId === $chatRoom->seller_id
            ? User::find($chatRoom->buyer_id)
            : User::find($chatRoom->seller_id);

        $otherUserName = $otherUser ? $otherUser->name : '未登録ユーザー';
        $otherUserPhotoUrl = $otherUser ? $otherUser->profile_photo : null; 
        $otherUserId = $otherUser ? $otherUser->id : null;

       
        $hasRated = Rating::where('from_user_id', $userId)
            ->where('to_user_id', $otherUserId)
            ->where('item_id', $chatRoom->item->id)
            ->exists();

       
        Message::where('chat_room_id', $chatRoomId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

       
        $messages = Message::where('chat_room_id', $chatRoomId)
            ->orderBy('created_at')
            ->get();

        
        $otherChatRooms = ChatRoom::with('item')
            ->where(function ($query) use ($userId) {
                $query->where('buyer_id', $userId)
                      ->orWhere('seller_id', $userId);
            })
            ->where('id', '!=', $chatRoomId)
            ->where('is_purchased', true)
            ->get();

        
        $otherChatRooms->loadCount(['messages as unread_count' => function ($query) use ($userId) {
            $query->where('receiver_id', $userId)
                  ->where('is_read', false);
        }]);


        $otherItems = $otherChatRooms->map(function ($room) {
            return $room->item;
        });

        return view('chat.show', [
            'chatRoom' => $chatRoom,
            'messages' => $messages,
            'otherChatRooms' => $otherChatRooms,
            'otherUserName' => $otherUserName,
            'otherUserPhotoUrl' => $otherUserPhotoUrl,
            'otherUserId' => $otherUserId,
            'hasRated' => $hasRated,
            'otherItems' => $otherItems, 
            'buyerId' => $chatRoom->buyer_id,
            'chatRoomId' => $chatRoomId,
        ]);
    }

    public function store(StoreChatRequest $request, $chatRoomId)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoomId);
        $userId = auth()->id();

        $receiverId = $chatRoom->buyer_id === $userId
            ? $chatRoom->seller_id
            : $chatRoom->buyer_id;

        $data = [
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('chat_images', 'public');

            $data['image_path'] = $path;
        }

        Message::create($data);

        return redirect()->route('chatroom.show', $chatRoom->id)->withInput();
    }

    public function edit($id)
    {
        $message = Message::findOrFail($id);
        if ($message->sender_id !== auth()->id()) {
            abort(403, '不正な操作です');
        }
        return view('chat.edit', compact('message'));
    }

    public function update(StoreChatRequest $request, $id)
    {
        $message = Message::findOrFail($id);
        if ($message->sender_id !== auth()->id()) {
            abort(403, '不正な操作です');
        }

        $message->update([
            'content' => $request->content,
        ]);

        return redirect()->route('chatroom.show', $message->chat_room_id);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        if ($message->sender_id !== auth()->id()) {
            abort(403, '不正な操作です');
        }

        $message->delete();

        return back();
    }
}
