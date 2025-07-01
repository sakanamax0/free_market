<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;  // ここを忘れずにuse追加

class ChatController extends Controller
{
    // チャット画面表示（チャットルームIDで）
    public function show($chatRoomId)
    {
        $chatRoom = ChatRoom::with('item')->findOrFail($chatRoomId);

        $userId = auth()->id();

        // 取引相手のユーザーを判別・取得
        if ($userId === $chatRoom->seller_id) {
            $otherUser = User::find($chatRoom->buyer_id);
        } else {
            $otherUser = User::find($chatRoom->seller_id);
        }
        $otherUserName = $otherUser ? $otherUser->name : '未登録ユーザー';

        // 未読メッセージを既読に更新
        Message::where('chat_room_id', $chatRoomId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // メッセージを取得（作成日時昇順）
        $messages = Message::where('chat_room_id', $chatRoomId)
            ->orderBy('created_at')
            ->get();

        // 他のチャットルーム（未購入で関係あるもの）
        $otherChatRooms = ChatRoom::with('item')
            ->where(function ($query) use ($userId) {
                $query->where('buyer_id', $userId)
                      ->orWhere('seller_id', $userId);
            })
            ->where('id', '!=', $chatRoomId)
            ->where('is_purchased', false)
            ->get();

        // ビューに相手ユーザー名も渡す
        return view('chat.show', compact('chatRoom', 'messages', 'otherChatRooms', 'otherUserName'));
    }

    // メッセージ送信
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

    // メッセージ編集画面
    public function edit($id)
    {
        $message = Message::findOrFail($id);
        if ($message->sender_id !== auth()->id()) {
            abort(403, '不正な操作です');
        }
        return view('chat.edit', compact('message'));
    }

    // メッセージ更新処理
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

    // メッセージ削除処理
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
