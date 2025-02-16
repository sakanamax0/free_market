<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
   
    public function show()
    {
        return view('register');
    }

    
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        
        \App\Models\User::create([
            'name' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        
        return redirect()->route('login')->with('success', '登録が完了しました！');
    }
}
