<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User; 

class ChatController extends Controller
{
   
    public function show($chatRoomId)
    {
        $chatRoom = ChatRoom::with('item')->findOrFail($chatRoomId);

        $userId = auth()->id();

       
        if ($userId === $chatRoom->seller_id) {
            $otherUser = User::find($chatRoom->buyer_id);
        } else {
            $otherUser = User::find($chatRoom->seller_id);
        }
        $otherUserName = $otherUser ? $otherUser->name : '未登録ユーザー';

        
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
            ->where('is_purchased', false)
            ->get();

        
        return view('chat.show', compact('chatRoom', 'messages', 'otherChatRooms', 'otherUserName'));
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
