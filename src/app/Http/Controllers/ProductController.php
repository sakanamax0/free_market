<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

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
        // sell/index.blade.php ビューを返す
        return view('sell.index');
    }

    // 商品一覧ページ表示
    public function index(Request $request)
    {
        // 商品を全て取得
        $items = Product::all();  // ここで全商品を取得

        if ($tab === 'mylist') {
        // ユーザーのマイリストに関連する商品を取得する場合（仮に）
        $items = auth()->user()->myListItems(); // マイリストに関連するメソッドが必要
    }
        return view('auth.index', compact('items'));  // 取得した商品データをビューに渡す
    }
}
