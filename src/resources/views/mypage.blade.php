<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>マイページ</title>
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
</head>
<body>
<header class="header">
    <div class="header__logo">
        <img src="{{ asset('images/logo.png') }}" alt="COACHTECHロゴ" />
    </div>
    <div class="header__search">
        <form method="GET" action="{{ route('index') }}">
            <input
                type="text"
                name="keyword"
                placeholder="なにをお探しですか？"
                value="{{ request('keyword') }}"
            />
            <button type="submit">検索</button>
        </form>
    </div>
    <nav class="header__nav">
        @auth
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button
                    type="submit"
                    style="background:none; border:none; color:#007bff; padding:0;"
                >
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
    <div class="profile-section">
        <div class="profile-header">
            <img 
                src="{{ $userData->profile_photo ? asset('storage/' . $userData->profile_photo) : asset('images/default-profile.png') }}" 
                alt="プロフィール画像" 
                class="profile-img" 
            />
            <h2>{{ $userData->name ?? $userData->name ?? 'ユーザー' }}</h2>


            <div class="user-rating">
                @php
                    $average = $userData->averageRating();
                    $fullStars = floor($average);
                    $halfStar = ($average - $fullStars) >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - $fullStars - $halfStar;
                @endphp

                @for ($i = 0; $i < $fullStars; $i++)
                    <span class="star full">&#9733;</span>
                @endfor

                @if ($halfStar)
                    <span class="star half">&#9733;</span>
                @endif

                @for ($i = 0; $i < $emptyStars; $i++)
                    <span class="star empty">&#9733;</span>
                @endfor

                <span class="rating-score">{{ number_format($average, 2) }} / 5</span>
            </div>

            <a href="{{ route('mypage.edit') }}" class="edit-profile-btn">プロフィールを編集</a>
        </div>

        <div class="tabs">
            <a href="#" class="tab-link active" data-tab="sell">出品した商品</a>
            <a href="#" class="tab-link" data-tab="purchase">購入した商品</a>
            <a href="#" class="tab-link" data-tab="ongoing">
                <span class="tab-text">取引中の商品</span>
                @if ($ongoingItems->sum('unread_count') > 0)
                    <span class="badge">{{ $ongoingItems->sum('unread_count') }}</span>
                @endif
            </a>
        </div>

        <div class="tab-content">

            <div id="sell" class="tab-panel active">
                @if ($sellItems->isEmpty())
                    <p>商品はありません。</p>
                @else
                    <div class="item-grid">
                        @foreach ($sellItems as $item)
                            <div class="item-card">
                                <a href="{{ route('item.show', $item->id) }}">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" />
                                    <p class="item-name">{{ $item->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


            <div id="purchase" class="tab-panel">
                @if ($purchaseItems->isEmpty())
                    <p>商品はありません。</p>
                @else
                    <div class="item-grid">
                         @foreach ($purchaseItems as $chatRoom)
                            <div class="item-card">
                                <a href="{{ route('chatroom.show', $chatRoom->id) }}">
                                    <img src="{{ $chatRoom->item->image_url }}" alt="{{ $chatRoom->item->name }}" />
                                    <p class="item-name">{{ $chatRoom->item->name }}</p>
                                </a>
                            </div>
                        @endforeach

                    </div>
                @endif
            </div>


            <div id="ongoing" class="tab-panel">
                @if ($ongoingItems->isEmpty())
                    <p>商品はありません。</p>
                @else
                    <div class="item-grid">
                        @foreach ($ongoingItems as $chatRoom)
                            <div class="item-card">
                                <a href="{{ route('chatroom.show', $chatRoom->id) }}">
                                    <img
                                        src="{{ $chatRoom->item->image_url }}"
                                        alt="{{ $chatRoom->item->name }}"
                                    />
                                    <p class="item-name">{{ $chatRoom->item->name }}</p>
                                    @if ($chatRoom->unread_count > 0)
                                        <span class="badge">{{ $chatRoom->unread_count }}</span>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<script>
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabLinks.forEach((link) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelector('.tab-link.active')?.classList.remove('active');
            document.querySelector('.tab-panel.active')?.classList.remove('active');

            link.classList.add('active');
            document.getElementById(link.dataset.tab)?.classList.add('active');
        });
    });
</script>
</body>
</html>
