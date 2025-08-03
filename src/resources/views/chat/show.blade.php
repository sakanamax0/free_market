<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>{{ $chatRoom->item->name }} | 取引チャット</title>
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header class="main-header">
        <span class="logo">COACHTECH</span>
    </header>

    <div class="container">
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

            <div class="user-info">
                <img src="{{ $otherUserPhotoUrl ?? asset('images/default-profile.png') }}" alt="ユーザー写真">
                <div class="username">{{ $otherUserName ?? '未登録ユーザー' }}さんとの取引画面</div>
            </div>


            @if (auth()->id() === $chatRoom->item->buyer_id && !$hasRated)
            <div class="complete-btn-container">
                <button id="complete-transaction-btn" class="complete-btn">取引を完了する</button>
            </div>
            @endif

            <hr>

            <div class="item-info">
                <a href="{{ route('purchase.index', ['item_id' => $chatRoom->item->id]) }}">
                    <img src="{{ $chatRoom->item->image_url }}" alt="商品画像" style="cursor: pointer;">
                </a>
                <div class="details">
                    <h2>{{ $chatRoom->item->name }}</h2>
                    <p>{{ number_format($chatRoom->item->price) }}円</p>
                </div>
            </div>


            <hr>

            <div class="messages">
                @foreach ($messages as $msg)
                <div class="message {{ $msg->sender_id === auth()->id() ? 'mine' : 'their' }}">
                    <div class="content-header" style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">

                        <img
                            src="{{ $msg->sender->profile_photo 
                ? asset('storage/' . $msg->sender->profile_photo) 
                : asset('images/default-profile.png') }}"
                            alt="プロフィール画像"
                            style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">

                        <strong>{{ $msg->sender->name }}</strong>
                    </div>

                    <div class="content">
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
                    <textarea id="chat-content" name="content" placeholder="取引メッセージを記入してください">{{ old('content') }}</textarea>

                    <label for="image-upload" class="image-upload-btn">画像を追加</label>
                    <input type="file" name="image" id="image-upload" accept="image/jpeg,image/png" hidden onchange="updateFileName(this)">
                    <span id="image-status" class="upload-status"></span>

                    <button type="submit" class="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div id="rating-modal" class="modal hidden">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <h3>取引が完了しました。</h3>
            <p>今回の取引相手はどうでしたか？</p>

            <form method="POST" action="{{ route('ratings.store') }}">
                @csrf
                <input type="hidden" name="to_user_id" value="{{ $otherUserId }}">
                <input type="hidden" name="item_id" value="{{ $chatRoom->item->id }}">

                <div class="star-rating">
                    @for ($i = 5; $i >= 1; $i--)
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
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('complete-transaction-btn');
            const modal = document.getElementById('rating-modal');

            if (btn && modal) {
                btn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                });
            }

            @if(auth() - > id() === $chatRoom - > item - > seller_id && $chatRoom - > item - > buyer_id && !$hasRated)
            if (modal) {
                modal.classList.remove('hidden');
            }
            @endif


            const textarea = document.getElementById('chat-content');
            const storageKey = 'chatContent_{{ $chatRoom->id }}';

            const savedContent = localStorage.getItem(storageKey);
            if (savedContent && !textarea.value) {
                textarea.value = savedContent;
            }

            textarea.addEventListener('input', function() {
                localStorage.setItem(storageKey, textarea.value);
            });

            textarea.closest('form').addEventListener('submit', function() {
                localStorage.removeItem(storageKey);
            });
        });

        function updateFileName(input) {
            const status = document.getElementById('image-status');
            if (input.files.length > 0) {
                status.textContent = "（追加済み）";
            } else {
                status.textContent = "";
            }
        }
    </script>
</body>

</html>