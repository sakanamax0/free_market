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

// カスタムログインページのルート
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ホームページ
Route::get('/', [IndexController::class, 'index'])->name('index');

// 商品関連のルート
Route::get('/goods/{id}', [ProductController::class, 'show'])->name('goods.show');

// 出品関連
Route::get('/sell', [ProductController::class, 'create'])->name('sell.index'); // 出品ページ表示
Route::post('/sell', [SellController::class, 'store'])->name('sell.store'); // 出品処理

// 商品一覧ページ（ItemControllerに移行）
Route::get('/products', [ItemController::class, 'index'])->name('products.index');

// 購入関連
Route::get('/purchase/{itemId}', [PurchaseController::class, 'show'])->name('purchase.show');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase'])->name('purchase.purchase');

// ユーザー登録関連
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// アイテム詳細およびコメント・いいね関連
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item_id}/comment', [ItemController::class, 'postComment'])->name('item.comment');
Route::post('/item/{item_id}/toggle-like', [ItemController::class, 'toggleLike'])->name('item.toggleLike');

// 配送関連
Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('address.edit');
Route::post('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('address.update');

// マイページ関連（authミドルウェア追加）
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');
});

// その他のルート
Route::get('/search', [ItemController::class, 'search'])->name('search');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');
