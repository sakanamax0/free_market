<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
    <title>マイページ</title>
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

    <main>
        <div class="profile-section">
            <div class="profile-header">
                <img src="/path/to/default-profile.png" alt="プロフィール画像" class="profile-img">
                <h2>{{ $userData['username'] }}</h2>
                <a href="{{ route('mypage.edit') }}" class="edit-profile-btn">プロフィールを編集</a>
            </div>
            <div class="tabs">
                <a href="#" class="tab-link active" data-tab="sell">出品した商品</a>
                <a href="#" class="tab-link" data-tab="purchase">購入した商品</a>
            </div>

            <div class="tab-content">
                <!-- 出品した商品のリスト -->
                <div id="sell" class="tab-panel active">
                    @if (isset($sellItems) && $sellItems->isNotEmpty())
    <ul>
        @foreach ($sellItems as $item)
            <li>{{ $item->name }}</li>
        @endforeach
    </ul>
@else
    <p>商品はありません。</p>
@endif

                </div>

                <!-- 購入した商品のリスト -->
                <div id="purchase" class="tab-panel">
                    @if ($purchaseItems->isEmpty())
                        <p>商品はありません。</p>
                    @else
                        <ul>
                            @foreach ($purchaseItems as $item)
                                <li>{{ $item->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/tabs.js') }}"></script>
</body>
</html>
