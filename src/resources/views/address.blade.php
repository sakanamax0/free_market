<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送付先住所変更画面</title>
    <link rel="stylesheet" href="/css/address.css">
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
                <a href="{{ route('logout') }}">ログアウト</a>
                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="{{ route('sell.index') }}" class="btn-sell">出品</a>
            @else
                <a href="{{ route('login') }}">ログイン</a>
            @endauth
        </nav>
    </header>

    
    <main class="main-content">
        <h1 class="page-title">住所の変更</h1>
        <form action="/purchase/address/{{ $item_id }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="zipcode">郵便番号</label>
                <!-- 郵便番号を表示する際、$addressがnullの場合は空文字をデフォルトに設定 -->
                <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', optional($address)->zipcode) }}" required>
            </div>
            <div class="form-group">
                <label for="details">住所</label>
                <!-- 住所を表示する際、$addressがnullの場合は空文字をデフォルトに設定 -->
                <input type="text" id="details" name="details" value="{{ old('details', optional($address)->details) }}" required>
            </div>
            <div class="form-group">
                <label for="building">建物名</label>
                <!-- 建物名を表示する際、$addressがnullの場合は空文字をデフォルトに設定 -->
                <input type="text" id="building" name="building" value="{{ old('building', optional($address)->building) }}">
            </div>
            <button type="submit" class="submit-button">更新する</button>
        </form>
    </main>
</body>
</html>
