<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品出品画面</title>
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
</head>
<body>
    <header class="header">
        <div class="header__logo">
            <img src="{{ asset('images/logo.png') }}" alt="COACHTECHロゴ">
        </div>
        <div class="header__search">
            <form method="GET" action="{{ route('index') }}">
                <input type="text" name="keyword" placeholder="商品名で検索" value="{{ request('keyword') }}">
                <button type="submit">検索</button>
            </form>
        </div>
        <nav class="header__nav">
        @auth
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #007bff; padding: 0; cursor: pointer; text-decoration: underline;">
                    ログアウト
                </button>
            </form>
            <a href="{{ route('mypage') }}">マイページ</a>
            <a href="{{ route('sell.index') }}" class="btn-sell">出品</a>
        @else
            <a href="{{ route('login') }}">ログイン</a>
        @endauth
    </nav>
    </header>
    <main>
        <h1>商品の出品</h1>
        <form action="/sell" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="image">商品画像</label>
                <div class="image-upload">
                    <input type="file" id="image" name="image">
                    <button type="button">画像を選択する</button>
                </div>
            </div>
            <div class="form-group">
                <h2>商品の詳細</h2>
                <div class="categories">
                    <span>カテゴリ</span>
                    <div class="tags">
                        <button type="button">ファッション</button>
                        <button type="button">家具</button>
                        <button type="button">インテリア</button>
                        <button type="button">レディース</button>
                        <button type="button">メンズ</button>
                        <button type="button">コスメ</button>
                        <button type="button">本</button>
                        <button type="button">ゲーム</button>
                        <button type="button">スポーツ</button>
                        <button type="button">キッチン</button>
                        <button type="button">ハンドメイド</button>
                        <button type="button">アクセサリー</button>
                        <button type="button">おもちゃ</button>
                        <button type="button">ベビー・キッズ</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="condition">商品の状態</label>
                    <select id="condition" name="condition">
                        <option value="" disabled selected>選択してください</option>
                        <option value="1">新品</option>
                        <option value="2">目立った傷や汚れなし</option>
                        <option value="3">やや傷や汚れあり</option>
                        <option value="4">傷や汚れあり</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="name">商品名</label>
                <input type="text" id="name" name="name" placeholder="商品名を入力してください">
            </div>
            <div class="form-group">
                <label for="description">商品の説明</label>
                <textarea id="description" name="description" placeholder="商品の説明を入力してください"></textarea>
            </div>
            <div class="form-group">
                <label for="price">販売価格</label>
                <div class="price-input">
                    <span>¥</span>
                    <input type="number" id="price" name="price" placeholder="販売価格を入力してください">
                </div>
            </div>
            <button type="submit" class="submit-button">出品する</button>
        </form>
    </main>
</body>
</html>
