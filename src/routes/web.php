<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatRoomController;

// 認証関連
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// トップページ
Route::get('/', [IndexController::class, 'index'])->name('index');

// 商品閲覧
Route::get('/goods/{id}', [ProductController::class, 'show'])->name('goods.show');

// 出品
Route::get('/sell', [ProductController::class, 'create'])->name('sell.index');
Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

// 商品詳細・コメント・いいね
Route::get('/products', [ItemController::class, 'index'])->name('products.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item_id}/comment', [ItemController::class, 'postComment'])->name('item.comment');
Route::post('/item/{item_id}/toggle-like', [ItemController::class, 'toggleLike'])->name('item.toggleLike');

// 検索・マイリスト
Route::get('/search', [ItemController::class, 'search'])->name('search');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');

// 購入画面・処理
Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase.index');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase'])->name('purchase.purchase');
Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])->name('purchase.success');

// 住所登録
Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('address.edit');
Route::post('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('address.update');

// ユーザ登録
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// マイページ・チャット機能（認証必須）
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');

    // チャットルームの作成・入室
    Route::post('/chatroom/{item}', [ChatRoomController::class, 'enter'])->name('chatroom.enter');

    // チャット画面・メッセージ送信
    Route::get('/chatroom/{chatRoom}', [ChatController::class, 'show'])->name('chatroom.show');
    Route::post('/chatroom/{chatRoom}/send', [ChatController::class, 'store'])->name('chatroom.send');

    // メッセージ編集・削除
    Route::get('/chat/message/{message}/edit', [ChatController::class, 'edit'])->name('chat.edit');
    Route::put('/chat/message/{message}', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('/chat/message/{message}', [ChatController::class, 'destroy'])->name('chat.destroy');
});
