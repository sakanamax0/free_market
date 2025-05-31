<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 商品詳細ページ表示
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show', compact('product'));
    }

    // 商品出品フォーム表示
    public function create()
    {
        return view('sell.index');
    }

    // 商品一覧ページ表示（おすすめ / マイリスト）
    public function index(Request $request)
    {
        $tab = $request->query('tab'); // ?tab=mylist など

        if ($tab === 'mylist') {
            // マイリスト（いいねした商品）
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $likedProductIds = Like::where('user_id', $user->id)->pluck('item_id');
            $items = Product::whereIn('id', $likedProductIds)
                ->with('orders') // sold_out 判定用
                ->get();

        } else {
            // おすすめ（全商品）
            $items = Product::with('orders')->get();
        }

        // 売り切れ判定を追加
        foreach ($items as $item) {
            $item->sold_out = $item->orders()->exists();
        }

        return view('auth.index', compact('items'));
    }
}
