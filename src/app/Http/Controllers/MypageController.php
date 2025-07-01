<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Message;
use App\Models\ChatRoom;

class MypageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // 出品商品はリレーションそのまま
        $sellItems = $user->sellItems ?? collect();

        // 購入商品も同様にそのまま
        $purchaseItems = $user->purchaseItems ?? collect();

        // 取引中チャットルームを取得（購入者か販売者かつ未購入）
        $ongoingChatRooms = ChatRoom::with(['item', 'messages' => function ($query) use ($user) {
            $query->where('receiver_id', $user->id)
                  ->where('is_read', false);
        }])
        ->where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id);
        })
        ->where('is_purchased', false)
        ->get();

        // unread_count を ChatRoom オブジェクトに動的プロパティとしてセット
        foreach ($ongoingChatRooms as $room) {
            $room->unread_count = $room->messages->count();
        }

        return view('mypage', [
            'sellItems' => $sellItems,
            'purchaseItems' => $purchaseItems,
            'ongoingItems' => $ongoingChatRooms,  // ChatRoomコレクションをそのまま渡す
            'userData' => $user,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();

        $userData = [
            'username' => $user->username ?? '',
            'postal_code' => $user->postal_code ?? '',
            'address' => $user->address ?? '',
            'building' => $user->building ?? '',
        ];

        return view('mypage.profile', compact('userData'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $user->username = $request->username;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました');
    }
}
