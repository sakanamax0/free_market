<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品出品画面</title>
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
    <style>
        /* ボタンの通常状態 */
        .category-button {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* ボタンの選択状態 */
        .category-button.selected {
            background-color: #007bff;
            color: #fff;
            border-color: #0056b3;
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
        <h1>商品の出品</h1>
        <form action="/sell" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="image">商品画像</label>
                <div class="image-upload">
                    <input type="file" id="image" name="image">
                </div>
            </div>
            <div class="form-group">
                <h2>商品の詳細</h2>
                <div class="categories">
                    <span>カテゴリ</span>
                    <div class="tags">
                        <!-- カテゴリーボタン -->
                        @php
                            // 以前に選択されたカテゴリがあればそのクラスを付与する
                            $selectedCategories = old('selected_category') ? explode(',', old('selected_category')) : [];
                        @endphp
                        @foreach(['ファッション', '家具', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'] as $category)
                            <button type="button" class="category-button {{ in_array($category, $selectedCategories) ? 'selected' : '' }}" aria-pressed="{{ in_array($category, $selectedCategories) ? 'true' : 'false' }}">{{ $category }}</button>
                        @endforeach
                    </div>
                </div>
                <input type="hidden" name="selected_category" id="selected-category" value="{{ implode(',', $selectedCategories) }}">
            </div>
            <div class="form-group">
                <label for="condition">商品の状態</label>
                <select id="condition" name="condition">
                    <option value="" disabled selected>選択してください</option>
                    <option value="1">良好</option>
                    <option value="2">目立った傷や汚れなし</option>
                    <option value="3">やや傷や汚れあり</option>
                    <option value="4">傷や汚れあり</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">商品名</label>
                <input type="text" id="name" name="name" placeholder="商品名を入力してください" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="description">商品の説明</label>
                <textarea id="description" name="description" placeholder="商品の説明を入力してください">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="price">販売価格</label>
                <div class="price-input">
                    <span>¥</span>
                    <input type="number" id="price" name="price" placeholder="販売価格を入力してください" value="{{ old('price') }}">
                </div>
            </div>
            <button type="submit" class="submit-button">出品する</button>
        </form>
    </main>
    <script>
        // 全てのカテゴリーボタンを取得
        const categoryButtons = document.querySelectorAll('.category-button');
        const hiddenInput = document.getElementById('selected-category');

        // 選択されたカテゴリーを保持する配列
        let selectedCategories = @json($selectedCategories);

        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                const category = button.textContent.trim();

                if (button.classList.contains('selected')) {
                    // 選択を解除
                    button.classList.remove('selected');
                    button.setAttribute('aria-pressed', 'false');
                    // 配列から削除
                    selectedCategories = selectedCategories.filter(item => item !== category);
                } else {
                    // 選択を追加
                    button.classList.add('selected');
                    button.setAttribute('aria-pressed', 'true');
                    // 配列に追加
                    selectedCategories.push(category);
                }

                // 配列をhidden inputに格納（カンマ区切りにする）
                hiddenInput.value = selectedCategories.join(',');
            });
        });
    </script>
    <script>
    // 商品登録後のポップアップとリダイレクト
    @if(session('success'))
        alert("{{ session('success') }}");
        setTimeout(function() {
            window.location.href = "{{ route('index') }}";
        }, 2000);  // 2秒後に遷移
    @endif
</script>

</body>
</html>
