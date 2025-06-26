<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { display: flex; margin: 20px; }
        .image { width: 50%; text-align: center; }
        .details { width: 50%; padding: 20px; }
        .details h2 { margin-bottom: 10px; }
        .details p { margin: 5px 0; }
        .button { display: inline-block; padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 5px; }
        .category { margin-top: 10px; }
        .like-button { cursor: pointer; color: red; }
        .liked { color: green; }
    </style>
</head>
<body>
    <header style="background: black; color: white; padding: 10px;">
        <h1>COACHTECH</h1>
    </header>

    <div class="container">
        <!-- 商品画像 -->
        <div class="image">
            <img src="{{ $item->img_url }}" alt="{{ $item->name }}" width="300">
        </div>

        <!-- 商品詳細 -->
        <div class="details">
            <h2>{{ $item->name }}</h2>
            <p><strong>ブランド名:</strong> {{ $item->brand }}</p>
            <p><strong>価格:</strong> ¥{{ number_format($item->price) }}（税込）</p>
            
            <!-- いいねボタン -->
            <div>
                <span class="like-button {{ $isLiked ? 'liked' : '' }}" id="like-button">
                    ❤️ いいね ({{ $item->likes()->count() }})
                </span>
            </div>

            <!-- コメント数 -->
            <p><strong>コメント数:</strong> {{ $item->comments()->count() }}</p>
            
            <div class="category">
                <strong>カテゴリー:</strong>
                @foreach ($item->categories as $category)
                    <span>{{ $category->name }}</span>@if (!$loop->last), @endif
                @endforeach
            </div>

            <p><strong>商品状態:</strong> {{ $item->condition }}</p>
            <p><strong>商品説明:</strong> {{ $item->description }}</p>
            <a href="{{ route('purchase.index', $item->id) }}" class="button">購入手続きへ</a>
        </div>
    </div>

    <div style="padding: 20px;">
        <h3>コメント</h3>
        @foreach ($item->comments as $comment)
            <div>
                <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
            </div>
        @endforeach

        @auth
            <form action="{{ route('item.comment', $item->id) }}" method="POST">
                @csrf
                <textarea name="comment" rows="4" cols="50" placeholder="商品へのコメント" required></textarea>
                <br>
                <button type="submit" style="background-color: red; color: white; padding: 10px; border: none; margin-top: 10px;">コメントを送信する</button>
            </form>
        @else
            <p>ログインするとコメントを投稿できます。</p>
        @endauth
    </div>

    <script>
        // いいねボタンの処理
        document.getElementById('like-button').addEventListener('click', function() {
            fetch("{{ route('item.toggleLike', $item->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                const likeButton = document.getElementById('like-button');
                likeButton.classList.toggle('liked', data.liked);
                likeButton.innerHTML = `❤️ いいね (${data.likesCount})`;
            });
        });
    </script>
</body>
</html>
