<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'score' => 'required|integer|min:1|max:5',
        ]);

        // 重複評価の防止
        $alreadyRated = Rating::where('from_user_id', auth()->id())
            ->where('to_user_id', $validated['to_user_id'])
            ->where('item_id', $validated['item_id'])
            ->exists();

        if ($alreadyRated) {
            return redirect()->back()->with('error', '既に評価済みです。');
        }

        Rating::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $validated['to_user_id'],
            'item_id' => $validated['item_id'],
            'score' => $validated['score'],
        ]);

        return redirect()->route('index')->with('success', '評価を送信しました。');
    }
}
