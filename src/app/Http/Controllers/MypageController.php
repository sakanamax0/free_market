<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
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
        $userData = [
            'username' => '既存の値が入力されている',
            'postal_code' => '既存の値が入力されている',
            'address' => '既存の値が入力されている',
            'building' => '既存の値が入力されている',
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

        // 更新処理（仮）
        // $user = Auth::user();
        // $user->update($request->all());

        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました');
    }
}
