<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品詳細画面
    public function show($item_id)
    {
        // 商品詳細情報の取得、カテゴリとコメントも関連付け
        $item = Item::with(['categories', 'comments.user', 'likes'])
            ->findOrFail($item_id);

        // 現在のユーザーがこの商品を「いいね」しているか
        $isLiked = Auth::check() && $item->likes()->where('user_id', Auth::id())->exists();

        return view('items.show', [
            'item' => $item,
            'isLiked' => $isLiked,
            'likesCount' => $item->likes()->count(),
        ]);
    }

    // コメント投稿
    public function postComment(Request $request, $item_id)
    {
        // バリデーション
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        // コメントの保存
        Comment::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'content' => $request->comment,
        ]);

        // コメント数を反映させてページをリロード
        return back()->with('success', 'コメントを送信しました。');
    }

    // いいねのトグル
    public function toggleLike($item_id)
    {
        $item = Item::findOrFail($item_id);

        // ユーザーがすでにこの商品を「いいね」しているかをチェック
        $like = $item->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            // いいね解除
            $like->delete();
            $liked = false;
        } else {
            // いいね追加
            $item->likes()->create(['user_id' => Auth::id()]);
            $liked = true;
        }

        // いいね数を再取得して返す
        return response()->json([
            'liked' => $liked,
            'likesCount' => $item->likes()->count(),
        ]);
    }

    // 商品一覧
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend'); // デフォルトは 'recommend'

        if ($tab === 'mylist') {
            // マイリストタブ
            $products = Auth::check()
                ? Auth::user()->likes()->with('item')->get()->pluck('item') // ユーザーがいいねした商品
                : collect(); // ログインしていない場合は空のコレクション
        } else {
            // おすすめタブ
            $products = Item::where('is_sold', false)
                ->orderBy('created_at', 'desc') // 最近登録された順
                ->when(Auth::check(), function ($query) {
                    $query->where('user_id', '!=', Auth::id()); // 自分が出品した商品を除外
                })
                ->get();
        }

        return view('auth.index', [
            'products' => $products,
            'tab' => $tab,
        ]);
    }
}
