<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メッセージ編集</title>
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>
<body>
    <div class="main" style="padding: 20px;">
        <h2>メッセージ編集</h2>

        {{-- エラー表示 --}}
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('chat.update', $message->id) }}">
            @csrf
            @method('PUT')

            <div class="form-area">
                <textarea name="content" placeholder="本文を編集してください">{{ old('content', $message->content) }}</textarea>
                <button type="submit">更新</button>
            </div>
        </form>

        <br>
        <a href="{{ route('chatroom.show', $message->chat_room_id) }}">← チャット画面に戻る</a>
    </div>
</body>
</html>
