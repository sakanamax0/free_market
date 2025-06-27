<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
   
    public function showLoginForm()
    {
        return view('login'); 
    }

    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            
            return redirect()->route('index')->with('success', 'ログインに成功しました！');
        }

        
        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません。',
        ])->withInput();
    }

    
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'ログアウトしました。');
    }
}
