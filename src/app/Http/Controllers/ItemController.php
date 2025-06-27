<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Comment;
use App\Models\SoldItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function show($item_id)
    {
        $item = Item::with(['categories', 'comments.user', 'likes'])
            ->findOrFail($item_id);

        $isLiked = Auth::check() && $item->likes()->where('user_id', Auth::id())->exists();

        return view('items.show', [
            'item' => $item,
            'isLiked' => $isLiked,
            'likesCount' => $item->likes()->count(),
        ]);
    }

    public function postComment(Request $request, $item_id)
    {
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        Comment::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'content' => $request->comment,
        ]);

        return back()->with('success', 'コメントを送信しました。');
    }

    public function toggleLike($item_id)
    {
        $item = Item::findOrFail($item_id);

        $like = $item->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $item->likes()->create(['user_id' => Auth::id()]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likesCount' => $item->likes()->count(),
        ]);
    }

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend');
        $keyword = $request->input('keyword');
        $userId = Auth::id();

        if ($tab === 'mylist') {
            $query = Item::whereHas('likes', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

            if ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }

            $items = $query->orderBy('created_at', 'desc')->get();
        } else {
            $query = Item::query();

            if (!is_null($userId)) {
                // 自分の出品商品を除外
                $query->where('seller_id', '!=', $userId);
            }
            \Log::info('ItemController@index called');
            \Log::info('SQL: '.$query->toSql());
            \Log::info('Bindings: '.json_encode($query->getBindings()));
            
            if ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }

            $items = $query->orderBy('created_at', 'desc')->get();
        }

        return view('auth.index', [
            'items' => $items,
            'tab' => $tab,
            'keyword' => $keyword,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $items = Item::where('name', 'like', "%{$keyword}%")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('auth.index', [
            'items' => $items,
            'tab' => 'search',
        ]);
    }

    public function mylist()
    {
        $items = Auth::check()
            ? Auth::user()->likes()->with('item')->get()->map(function ($like) {
                return $like->item;
            })->filter()
            : collect();

        return view('auth.index', [
            'items' => $items,
            'tab' => 'mylist',
        ]);
    }
}
