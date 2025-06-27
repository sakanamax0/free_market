<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>
    <header class="header">
        <div class="header__logo">
            <img src="{{ asset('images/logo.png') }}" alt="COACHTECHロゴ">
        </div>
        <div class="header__search">
        <form method="GET" action="{{ route('index') }}">
    <input type="text" name="keyword" placeholder="商品名で検索" value="{{ request('keyword') }}">
    <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">
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

    <main class="products">
        <nav class="tabs">
            <a href="{{ route('index', ['tab' => 'recommend']) }}" 
               class="tab {{ request('tab') === 'recommend' || !request('tab') ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('index', ['tab' => 'mylist']) }}" 
               class="tab {{ request('tab') === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </nav>

        @if(request('tab') === 'mylist')
            {{-- マイリスト表示 --}}
            @if($items->isEmpty())
                <p class="no-products">現在マイリストはありません</p>
            @else
                <div class="products__list">
                @foreach($items as $item)
    <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="product-link">
        <div class="product" style="position: relative;">
            <div class="product__image">
                <img src="{{ $item->image_url }}" alt="商品画像">
                @if ($item->sold_out)
                    <div class="sold-out-overlay">
                        <span>SOLD OUT</span>
                    </div>
                @endif
            </div>
            <div class="product__name">
                {{ $item->name }}
            </div>
            <p>¥{{ number_format($item->price) }}</p>
        </div>
    </a>
@endforeach
                </div>
            @endif
        @else
            {{-- おすすめ表示 --}}
            <div class="products__list">
                @foreach($items as $item)
                    <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="product-link">
                        <div class="product" style="position: relative;">
                            <div class="product__image">
                                <img src="{{ $item->image_url }}" alt="商品画像">
                                
                                @if ($item->sold_out)
                                    <div class="sold-out-overlay">
                                        <span>SOLD OUT</span>
                                    </div>
                                @endif
                            </div>
                            <div class="product__name">
                                {{ $item->name }}
                            </div>
                            <p>¥{{ number_format($item->price) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </main>

</body>
</html>
