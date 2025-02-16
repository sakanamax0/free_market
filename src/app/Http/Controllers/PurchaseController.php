<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 商品購入ページを表示するメソッド
    public function show($itemId)
    {
        // ユーザーがログインしているか確認
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        // 商品情報を取得
        $item = Item::findOrFail($itemId);

        // 商品が売り切れの場合はトップページにリダイレクト
        if ($item->sold_out) {
            return redirect()->route('index')->with('error', 'この商品は売り切れです。');
        }

        // ユーザーの配送先情報を取得
        $address = Auth::user()->address; // ここでaddressリレーションを使って配送先を取得

        return view('purchase', compact('item', 'address'));
    }

    // コンストラクタでStripeの秘密鍵を設定
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY')); // 環境変数からシークレットキーを設定
    }

    // 購入完了処理
    public function complete(Request $request)
{
    try {
        // 受け取ったitem_idをログに記録
        \Log::info('Received item_id: ' . $request->item_id);  // ここで送信されたitem_idを確認する

        // 商品IDを取得して商品情報を取得
        $item = Item::find($request->item_id);

        // 商品が存在しない場合のエラーチェック
        if (!$item) {
            return response()->json(['success' => false, 'message' => '商品が見つかりません。']);
        }

        // 商品が既に売り切れの場合のエラーチェック
        if ($item->sold_out) {
            return response()->json(['success' => false, 'message' => 'この商品は売り切れです。']);
        }

        // 支払い処理
        $paymentIntent = PaymentIntent::create([
            'amount' => $item->price * 100, // 価格はセン単位で渡す
            'currency' => 'jpy',
            'payment_method' => $request->stripe_token, // フロントエンドから送信されたトークン
            'confirmation_method' => 'manual',
            'confirm' => true,
        ]);

        // 支払いが成功した場合、sold_outをtrueに更新
        $item->sold_out = true;
        $item->save(); // 更新を保存

        // 購入が成功した場合
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        // エラーハンドリング
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

}
