<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('mypage', [
            'sellItems' => $sellItems,
            'purchaseItems' => $purchaseItems,
            'userData' => $user
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
