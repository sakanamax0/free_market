<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<header class="header">
    <div class="header__logo">
        <img src="{{ asset('images/logo.png') }}" alt="COACHTECHロゴ">
    </div>
    <div class="header__search">
       
    </div>
    <nav class="header__nav">
        @auth
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">ログアウト</button>
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

    <div class="products__list">
        @php
            $displayItems = collect();
            if(request('tab') === 'mylist' && auth()->check()){
                
                $displayItems = auth()->user()->likes->pluck('item');
            } else {
               
                $displayItems = $items;
            }
        @endphp

        @if($displayItems->isEmpty())
            <p class="no-products">
                {{ request('tab') === 'mylist' ? '現在マイリストはありません' : '商品はありません' }}
            </p>
        @else
            @foreach($displayItems as $item)
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="product-link">
                    <div class="product">
                        <div class="product__image">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                            @if($item->sold_out)
                                <div class="sold-out-overlay">
                                    <span>SOLD OUT</span>
                                </div>
                            @endif
                        </div>
                        <div class="product__name">{{ $item->name }}</div>
                        <p class="product__price">¥{{ number_format($item->price) }}</p>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</main>
</body>
</html>
