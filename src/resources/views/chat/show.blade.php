<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $chatRoom->item->name }} | 取引チャット</title>
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>
<body>
    <div class="sidebar">
        <h2>その他の取引</h2>
        <div class="chat-list">
            @foreach ($otherChatRooms as $room)
                <form method="GET" action="{{ route('chatroom.show', $room->id) }}">
                    <button>{{ $room->item->name }}</button>
                </form>
            @endforeach
        </div>
    </div>

    <div class="main">

        {{-- 追加部分: ユーザー登録写真と名前の表示 --}}
        <div class="user-info">
            {{-- 仮に登録写真URLが $otherUserPhotoUrl 変数、名前が $otherUserName と仮定 --}}
            {{-- コントローラー側で他方ユーザー情報を渡している想定で --}}
            <img src="{{ $otherUserPhotoUrl ?? asset('images/default-profile.png') }}" alt="ユーザー写真">
            <div class="username">{{ $otherUserName ?? '未登録ユーザー' }}さんとの取引画面</div>
        </div>
        <hr>

        <div class="item-info">
            <img src="{{ $chatRoom->item->image_url }}" alt="商品画像">
            <div class="details">
                <h2>{{ $chatRoom->item->name }}</h2>
                <p>{{ number_format($chatRoom->item->price) }}円</p>
            </div>
        </div>

        <hr>

        <div class="messages">
            @foreach ($messages as $msg)
                <div class="message {{ $msg->sender_id === auth()->id() ? 'mine' : 'their' }}">
                    <div class="content">
                        <strong>{{ $msg->sender->name }}</strong><br>
                        {{ $msg->content }}

                        @if ($msg->image_path)
                            <img src="{{ asset('storage/' . $msg->image_path) }}" alt="メッセージ画像" class="message-img" />
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

        <form method="POST" action="{{ route('chatroom.send', $chatRoom->id) }}" enctype="multipart/form-data">
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
