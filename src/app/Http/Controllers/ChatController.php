<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show($chatRoomId)
    {
        $chatRoom = ChatRoom::with('item')->findOrFail($chatRoomId);
        $userId = auth()->id();

        $isBuyer  = ($userId === $chatRoom->buyer_id);
        $isSeller = ($userId === $chatRoom->seller_id);

        
        $otherUser = $isSeller
            ? User::find($chatRoom->buyer_id)
            : User::find($chatRoom->seller_id);

        $otherUserName = $otherUser ? $otherUser->name : '未登録ユーザー';
        
        $otherUserPhotoUrl = ($otherUser && $otherUser->profile_photo)
            ? asset('storage/' . $otherUser->profile_photo)
            : null;
        $otherUserId = $otherUser ? $otherUser->id : null;

       
        $hasRated = Rating::where('from_user_id', $userId)
            ->where('to_user_id', $otherUserId)
            ->where('item_id', $chatRoom->item->id)
            ->exists();


        $showModal = false;
        if ($isSeller) {
            $showModal = Rating::where('from_user_id', $chatRoom->buyer_id)
                ->where('to_user_id', $chatRoom->seller_id)
                ->where('item_id', $chatRoom->item->id)
                ->exists();
        }

        
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
            'chatRoom'          => $chatRoom,
            'messages'          => $messages,
            'otherChatRooms'    => $otherChatRooms,
            'otherUserName'     => $otherUserName,
            'otherUserPhotoUrl' => $otherUserPhotoUrl,
            'otherUserId'       => $otherUserId,
            'hasRated'          => $hasRated,
            'otherItems'        => $otherItems,
            'buyerId'           => $chatRoom->buyer_id,
            'chatRoomId'        => $chatRoomId,
            'showModal'         => $showModal,
            'isBuyer'           => $isBuyer,
            'isSeller'           => $isSeller,
        ]);
    }

    public function store(Request $request, $chatRoomId)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoomId);
        $userId = auth()->id();

        $receiverId = $chatRoom->buyer_id === $userId
            ? $chatRoom->seller_id
            : $chatRoom->buyer_id;

       
        $request->validate([
            'content' => 'required_without:image|nullable|string|max:400',
            'image'   => 'nullable|image|mimes:jpeg,png|max:2048',
        ], [
            'content.required_without' => '本文を入力してください。',
            'content.max'              => '本文は400文字以内で入力してください。',
            'image.image'              => 'アップロードできるのは画像ファイルのみです。',
            'image.mimes'              => '画像は「.png」または「.jpeg」形式でアップロードしてください。',
            'image.max'                => '画像サイズは2MB以下にしてください。',
        ]);

        $data = [
            'chat_room_id' => $chatRoom->id,
            'sender_id'    => $userId,
            'receiver_id'  => $receiverId,
            'content'      => $request->input('content'),
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

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        if ($message->sender_id !== auth()->id()) {
            abort(403, '不正な操作です');
        }

        
        $request->validate([
            'content' => 'required|string|max:1000',
        ], [
            'content.required' => 'メッセージを入力してください。',
            'content.max'      => 'メッセージは1000文字以内で入力してください。',
        ]);

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
