<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $item->name }} | 取引チャット</title>
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>
<body>
    <div class="sidebar">
        <h2>その他の取引</h2>
        <div class="chat-list">
            @foreach ($otherItems as $chatItem)
                <form method="GET" action="{{ route('chat.purchase', $chatItem->id) }}">
                    <button>{{ $chatItem->name }}</button>
                </form>
            @endforeach
        </div>
    </div>

    <div class="main">
        <div class="item-info">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
            <div class="details">
                <h2>{{ $item->name }}</h2>
                <p>{{ $item->price }}円</p>
            </div>
        </div>

        <div class="messages">
            @foreach ($messages as $msg)
                <div class="message {{ $msg->sender_id === auth()->id() ? 'mine' : 'their' }}">
                    <div class="content">
                        <strong>{{ $msg->sender->name }}</strong><br>
                        {{ $msg->content }}
                        @if ($msg->image_path)
                            <div><img src="{{ asset('storage/' . $msg->image_path) }}" class="message-img"></div>
                        @endif
                        @if ($msg->sender_id === auth()->id())
                            <div class="form-actions">
                                <a href="{{ route('chat.edit', $msg->id) }}">編集</a>
                                <form method="POST" action="{{ route('chat.destroy', $msg->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">削除</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('chat.send', $item->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-area">
                <textarea name="content" placeholder="取引メッセージを記入してください">{{ old('content') }}</textarea>
                <input type="file" name="image" accept="image/jpeg,image/png">
                <button type="submit">送信</button>
            </div>
        </form>
    </div>
</body>
</html>
