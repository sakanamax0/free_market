<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // 🔒 ログイン必須にする
    }

    // マイページの表示
    public function index()
    {
        $user = Auth::user();

        // ログインユーザーが出品した商品
        $sellItems = $user->sellItems ?? collect(); // リレーションがない場合は空のコレクションを返す

        // ログインユーザーが購入した商品
        $purchaseItems = $user->purchaseItems ?? collect(); // リレーションがない場合は空のコレクションを返す

        return view('mypage', [
            'sellItems' => $sellItems,
            'purchaseItems' => $purchaseItems,
            'userData' => $user
        ]);
    }

    // プロフィール編集画面の表示
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

    // プロフィールの更新処理
    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // ユーザー情報を更新
        $user->username = $request->username;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました');
    }
}
