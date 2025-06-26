<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\SoldItem;
use App\Models\Address;
use Stripe\StripeClient;

class PurchaseController extends Controller
{
    // 商品購入ページ（住所確認）表示
    public function index($item_id, Request $request){
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        $item = Item::findOrFail($item_id);
        if ($item->sold_out) {
            return redirect()->route('index')->with('error', 'この商品は売り切れです。');
        }

        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first();

        return view('purchase', compact('item', 'user', 'address'));
    }

    // Stripe Checkoutを使った支払い処理
    public function purchase($item_id, Request $request){
        $item = Item::findOrFail($item_id);
        if ($item->sold_out) {
            return redirect()->route('index')->with('error', 'この商品はすでに売り切れです。');
        }

        $stripe = new StripeClient(config('stripe.stripe_secret_key'));

        [
            $user_id,
            $amount,
            $sending_postcode,
            $sending_address,
            $sending_building
        ] = [
            Auth::id(),
            $item->price,
            $request->destination_postcode,
            urlencode($request->destination_address),
            urlencode($request->destination_building) ?? null
        ];

        $checkout_session = $stripe->checkout->sessions->create([
            'payment_method_types' => [$request->payment_method],
            'payment_method_options' => [
                'konbini' => ['expires_after_days' => 7],
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', [
                'item_id' => $item_id,
                'user_id' => $user_id,
                'amount' => $amount,
                'sending_postcode' => $sending_postcode,
                'sending_address' => $sending_address,
                'sending_building' => $sending_building,
            ]),
        ]);

        return redirect($checkout_session->url);
    }

    // Stripe決済完了後の処理
    public function success($item_id, Request $request){
        if (
            !$request->user_id ||
            !$request->amount ||
            !$request->sending_postcode ||
            !$request->sending_address
        ) {
            throw new Exception("必要なクエリパラメータが不足しています（user_id, amount, sending_postcode, sending_address）");
        }

        $item = Item::findOrFail($item_id);
        if ($item->sold_out) {
            return redirect('/')->with('error', 'この商品はすでに購入済みです。');
        }

        $stripe = new StripeClient(config('stripe.stripe_secret_key'));
        $stripe->charges->create([
            'amount' => $request->amount,
            'currency' => 'jpy',
            'source' => 'tok_visa',
        ]);

        SoldItem::create([
            'user_id' => $request->user_id,
            'item_id' => $item_id,
            'sending_postcode' => $request->sending_postcode,
            'sending_address' => urldecode($request->sending_address),
            'sending_building' => $request->sending_building ? urldecode($request->sendingsending_building) : null,
        ]);

        $item->sold_out = true;
        $item->save();

        return redirect('/')->with('flashSuccess', '決済が完了しました！');
    }
}
