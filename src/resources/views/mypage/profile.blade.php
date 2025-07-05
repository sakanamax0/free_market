<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
    <title>プロフィール編集</title>
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
        <div class="profile-edit-section">
            <h2>プロフィール設定</h2>

          
            <div class="profile-image-preview">
                <img 
                    src="{{ $userData['profile_photo'] ? asset('storage/' . $userData['profile_photo']) : asset('images/default-profile.png') }}" 
                    alt="プロフィール画像" 
                    class="profile-img"
                >
            </div>

           
            <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="profile_photo">プロフィール画像を変更</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="name">ユーザー名</label>
                    <input type="text" id="name" name="name" value="{{ $userData['name'] }}">
                </div>
                <div class="form-group">
                    <label for="postal_code">郵便番号</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ $userData['postal_code'] }}">
                </div>
                <div class="form-group">
                    <label for="address">住所</label>
                    <input type="text" id="address" name="address" value="{{ $userData['address'] }}">
                </div>
                <div class="form-group">
                    <label for="building">建物名</label>
                    <input type="text" id="building" name="building" value="{{ $userData['building'] }}">
                </div>
                <button type="submit" class="submit-btn">更新する</button>
            </form>
        </div>
    </main>
</body>
</html>
