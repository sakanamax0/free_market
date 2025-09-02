<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $chatRoom->item->name }} | 取引チャット</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>

<body data-chatroom-id="{{ $chatRoom->id }}">
<header class="main-header">
    <span class="logo">COACHTECH</span>
</header>

<div class="container">
    <aside class="sidebar">
        <h2>その他の取引</h2>
        <div class="chat-list">
            @foreach ($otherChatRooms as $room)
                <form method="GET" action="{{ route('chatroom.show', $room->id) }}">
                    @csrf
                    <button class="chat-link" type="submit">{{ $room->item->name }}</button>
                </form>
            @endforeach
        </div>
    </aside>

    <main class="main">
      
        <div class="user-info user-info--with-action">
            <div class="user-info-left">
                <img src="{{ $otherUserPhotoUrl ?? asset('images/default-profile.png') }}" alt="ユーザー写真">
                <div class="username">{{ $otherUserName ?? '未登録ユーザー' }}さんとの取引画面</div>
            </div>

          
            @if ($isBuyer && !$hasRated)
                <div class="complete-btn-container" id="complete-container">
                    <button id="complete-transaction-btn" class="complete-btn" type="button">取引を完了する</button>
                </div>
            @endif
        </div>
        

        <hr class="rule">

        <div class="item-info">
            <a href="{{ route('item.show', ['item_id' => $chatRoom->item->id]) }}" class="item-thumb">
                <img src="{{ $chatRoom->item->image_url }}" alt="商品画像">
            </a>
            <div class="details">
                <h2 class="item-name">{{ $chatRoom->item->name }}</h2>
                <p class="item-price">{{ number_format($chatRoom->item->price) }}円</p>
            </div>
        </div>

        <hr class="rule">

        <section class="messages" aria-label="メッセージ一覧">
            @foreach ($messages as $msg)
                <article class="message {{ $msg->sender_id === auth()->id() ? 'mine' : 'their' }}">
                    <div class="content-header">
                        <strong>{{ $msg->sender->name }}</strong>
                        <img
                            src="{{ $msg->sender->profile_photo ? asset('storage/' . $msg->sender->profile_photo) : asset('images/default-profile.png') }}"
                            alt="プロフィール画像">
                    </div>

                    <div class="content">
                        {{-- content が [画像のみ] の場合は非表示 --}}
                        @if ($msg->content && $msg->content !== '[画像のみ]')
                            <p>{{ $msg->content }}</p>
                        @endif

                        @if ($msg->image_path)
                            <img src="{{ asset('storage/' . $msg->image_path) }}" alt="メッセージ画像" class="message-img">
                        @endif
                    </div>

                    @if ($msg->sender_id === auth()->id())
                        <div class="message-actions">
                            <a href="{{ route('chat.edit', $msg->id) }}">編集</a>
                            <form method="POST" action="{{ route('chat.destroy', $msg->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">削除</button>
                            </form>
                        </div>
                    @endif
                </article>
            @endforeach
        </section>

       
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        
        <form method="POST" action="{{ route('chatroom.send', $chatRoom->id) }}" enctype="multipart/form-data" class="send-form">
            @csrf
            <div class="form-area">
                <textarea id="chat-content" name="content" placeholder="取引メッセージを記入してください">{{ old('content') }}</textarea>

                <label for="image-upload" class="image-upload-btn">画像を追加</label>
                <input type="file" name="image" id="image-upload" accept="image/jpeg,image/png" hidden onchange="updateFileName(this)">
                <span id="image-status" class="upload-status"></span>

                <button type="submit" class="send-btn" aria-label="送信">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/></svg>
                </button>
            </div>
        </form>
    </main>
</div>


@php
    $autoOpen = ($isSeller && $showModal && !$hasRated);
@endphp
<div id="rating-modal" class="modal {{ $autoOpen ? 'is-open' : '' }}" aria-hidden="{{ $autoOpen ? 'false' : 'true' }}">
    <div class="modal-backdrop" data-close="true"></div>
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="rating-title">
        <h3 id="rating-title" class="modal-title">
            {{ $isBuyer ? '取引を完了しました。' : '購入者からの評価が届きました。' }}
        </h3>
        <p class="modal-subtitle">
            {{ $isBuyer ? '今回の取引相手はどうでしたか？' : '相手の評価をお願いします。' }}
        </p>

        <form id="rating-form" method="POST" action="{{ route('ratings.store') }}">
            @csrf
            <input type="hidden" name="to_user_id" value="{{ $otherUserId }}">
            <input type="hidden" name="item_id" value="{{ $chatRoom->item->id }}">
            <input type="hidden" name="score" id="rating-score" value="">

            <div id="rating-stars" class="rating-stars" role="radiogroup" aria-label="5段階評価">
                <button type="button" class="star" data-value="1" aria-label="1点">★</button>
                <button type="button" class="star" data-value="2" aria-label="2点">★</button>
                <button type="button" class="star" data-value="3" aria-label="3点">★</button>
                <button type="button" class="star" data-value="4" aria-label="4点">★</button>
                <button type="button" class="star" data-value="5" aria-label="5点">★</button>
            </div>

            <button id="rating-submit" type="submit" class="submit-rating" disabled>送信する</button>
        </form>
    </div>
</div>

<script>
    function updateFileName(input) {
        const status = document.getElementById('image-status');
        status.textContent = input.files.length > 0 ? "（追加済み）" : "";
    }
</script>
<script src="{{ asset('js/chat.js') }}" defer></script>
</body>
</html>
