<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class SellController extends Controller
{
    public function index()
    {
        return view('sell.index'); // 出品画面を表示
    }

    public function store(Request $request)
    {
    // バリデーション
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'condition' => 'required|integer',
        'description' => 'nullable|string',
        'price' => 'required|integer|min:1',
        'categories' => 'required|array', // カテゴリーは配列で受け取る
        'categories.*' => 'exists:categories,id', // 配列内の各値が有効なカテゴリーIDであることを確認
    ]);

    // 画像アップロード処理
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('public/images'); 
        $url = Storage::url($path);
        $request->session()->put('image_path', $path); // セッションに保存されたパスを格納
    } else {
        $path = null;
        $url = null; // 画像がない場合
    }

    // アイテム保存
    $item = Item::create([
        'name' => $validatedData['name'],
        'image' => $path,
        'description' => $validatedData['description'] ?? null,
        'price' => $validatedData['price'],
        'condition' => $validatedData['condition'],
        'sold_out' => false,
        'img_url' => $url,
    ]);

    // 追加確認: アイテムが正しく保存されたか
    dd($item); // 保存されたアイテムが返されるかを確認

    // カテゴリーの紐付け
    $item->categories()->attach($validatedData['categories']); 

    // 成功メッセージ
    return redirect()->route('sell.index')->with('success', '商品が出品されました！');
    }
}
