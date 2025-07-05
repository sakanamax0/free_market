<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $sellItems = $user->sellItems ?? collect();
        $purchaseItems = $user->purchaseItems ?? collect();

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

        foreach ($ongoingChatRooms as $room) {
            $room->unread_count = $room->messages->count();
        }

        return view('mypage', [
            'sellItems' => $sellItems,
            'purchaseItems' => $purchaseItems,
            'ongoingItems' => $ongoingChatRooms,
            'userData' => $user,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();

        $userData = [
            'name' => $user->name ?? '',
            'postal_code' => $user->postal_code ?? '',
            'address' => $user->address ?? '',
            'building' => $user->building ?? '',
            'profile_photo' => $user->profile_photo ?? null,
        ];

        return view('mypage.profile', compact('userData'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);
        
        $user = Auth::user();
        
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('postal_code')) {
            $user->postal_code = $request->postal_code;
        }
        if ($request->filled('address')) {
            $user->address = $request->address;
        }
        if ($request->filled('building')) {
            $user->building = $request->building;
        }
        
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }
        
        $user->save();
        
        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました');
    }

    public function show()
    {
        $user = auth()->user();

        return view('mypage', [
            'user' => $user,
        ]);
    }
}
