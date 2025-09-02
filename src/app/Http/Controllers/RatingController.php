<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseNotificationMail;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'item_id'    => 'required|exists:items,id',
            'score'      => 'required|integer|min:1|max:5',
        ], [
            'to_user_id.required' => '評価対象のユーザーが不正です。',
            'to_user_id.exists'   => '対象ユーザーが存在しません。',
            'item_id.required'    => '対象の商品が不正です。',
            'item_id.exists'      => '対象商品が存在しません。',
            'score.required'      => '評価点数を選択してください。',
            'score.integer'       => '評価点数は数値で指定してください。',
            'score.min'           => '評価は1以上で入力してください。',
            'score.max'           => '評価は5以下で入力してください。',
        ]);

        
        $alreadyRated = Rating::where('from_user_id', Auth::id())
            ->where('to_user_id', $validated['to_user_id'])
            ->where('item_id', $validated['item_id'])
            ->exists();

        if ($alreadyRated) {
            return redirect()->back()->with('error', '既に評価済みです。');
        }

        
        $rating = Rating::create([
            'from_user_id' => Auth::id(),
            'to_user_id'   => $validated['to_user_id'],
            'item_id'      => $validated['item_id'],
            'score'        => $validated['score'],
        ]);

     
        $item = Item::find($validated['item_id']);
        if (Auth::id() === $item->buyer_id) {
            $seller = User::find($item->seller_id);
            if ($seller && $seller->email) {
                Mail::to($seller->email)->send(
                    new PurchaseNotificationMail($item, Auth::user()->name)
                );
            }
        }

        
        return redirect()->route('index')->with('success', '評価を送信しました。');
    }
}
