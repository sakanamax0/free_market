<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ログインフォームを表示
    public function showLoginForm()
    {
        return view('login'); // resources/views/login.blade.php を表示
    }

    // ログイン処理
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // ログイン成功後、index.blade.php へリダイレクト
            return redirect()->route('index')->with('success', 'ログインに成功しました！');
        }

        // ログイン失敗時の処理
        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません。',
        ])->withInput();
    }

    // ログアウト処理
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'ログアウトしました。');
    }
}
