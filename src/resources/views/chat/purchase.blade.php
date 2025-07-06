<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>{{ $item->name }} | 取引チャット</title>
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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


        <div class="user-info" style="display: flex; align-items: center; gap: 10px; padding: 10px 0;">
            <img 
                src="{{ $otherUserPhotoUrl ?? asset('images/default-profile.png') }}" 
                alt="ユーザー写真" 
                style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid #ccc;"
            >
            <div class="username" style="font-weight: bold; font-size: 1.1rem;">
                {{ $otherUserName ?? '未登録ユーザー' }}さんとの取引画面
            </div>
        </div>

        <div class="item-info">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像" />
            <div class="details">
                <h2>{{ $item->name }}</h2>
                <p>{{ number_format($item->price) }}円</p>
            </div>
        </div>


        @if (auth()->id() === $buyerId && !$hasRated)
            <div class="complete-btn-container" style="text-align: right; margin: 1em 0;">
                <button id="complete-transaction-btn" class="complete-btn">取引を完了する</button>
            </div>
        @endif

        <hr />

        <div class="messages">
            @foreach ($messages as $msg)
                <div class="message {{ $msg->sender_id === auth()->id() ? 'mine' : 'their' }}">
                    <div class="content-header" style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <img 
                            src="{{ $msg->sender->profile_photo 
                                ? asset('storage/' . $msg->sender->profile_photo) 
                                : asset('images/default-profile.png') }}" 
                            alt="プロフィール画像" 
                            style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;"
                        >

                        <strong>{{ $msg->sender->name }}</strong>
                    </div>

                    <div class="content">
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

        <form method="POST" action="{{ route('chatroom.send', $chatRoomId) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-area">
                <textarea name="content" placeholder="取引メッセージを記入してください">{{ old('content') }}</textarea>
                <input type="file" name="image" accept="image/jpeg,image/png">
                <button type="submit">送信</button>
            </div>
        </form>
    </div>


    <div id="rating-modal" class="modal hidden">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <h3>取引が完了しました。</h3>
            <p>今回の取引相手はどうでしたか？</p>

            <form method="POST" action="{{ route('ratings.store') }}">
                @csrf
                <input type="hidden" name="to_user_id" value="{{ $otherUserId }}">
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                <div class="star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <label>
                            <input type="radio" name="score" value="{{ $i }}">
                            <span class="star">&#9733;</span>
                        </label>
                    @endfor
                </div>

                <button type="submit" class="submit-rating">送信する</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('complete-transaction-btn');
            const modal = document.getElementById('rating-modal');

            if (btn && modal) {
                btn.addEventListener('click', function () {
                    modal.classList.remove('hidden');
                });
            }
        });
    </script>

</body>
</html>
