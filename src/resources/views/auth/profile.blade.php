<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール設定</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo {
            font-size: 24px;
            font-weight: bold;
        }
        header .search-bar input {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 300px;
        }
        header .nav-links a {
            margin: 0 10px;
            color: #fff;
            text-decoration: none;
        }
        header .nav-links a:hover {
            text-decoration: underline;
        }
        .content {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .content h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .profile-image {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ccc;
        }
        .profile-image button {
            margin-top: 10px;
            padding: 10px 20px;
            color: #fff;
            background-color: #e60000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .profile-image button:hover {
            background-color: #cc0000;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 10px;
            color: #fff;
            background-color: #e60000;
            border: none;
            border-radius: 4px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #cc0000;
        }
    </style>
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
    <div class="content">
        <h1>プロフィール設定</h1>
        <div class="profile-image">
            <img src="/path/to/placeholder-image.jpg" alt="プロフィール画像">
            <button type="button">画像を選択する</button>
        </div>
        <form action="/profile/update" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="postal_code">郵便番号</label>
                <input type="text" id="postal_code" name="postal_code" required>
            </div>
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="building">建物名</label>
                <input type="text" id="building" name="building">
            </div>
            <button type="submit" class="btn">更新する</button>
        </form>
    </div>
</body>
</html>
