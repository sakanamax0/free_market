<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入</title>
    <style>
        /* 必要に応じてスタイルを追加 */
    </style>
    <script src="https://js.stripe.com/v3/"></script> <!-- Stripe.jsを読み込む -->
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
        <div class="image">
            <img src="{{ $item->img_url }}" alt="{{ $item->name }}" width="300">
        </div>
        <div>
            <h2>{{ $item->name }}</h2>
            <p>¥{{ number_format($item->price) }}</p>
            
            @if ($item->sold_out)
                <p style="color: red; font-weight: bold;">この商品は売り切れです。</p>
            @endif
        </div>
    </div>

    <div>
        <h3>支払い方法</h3>
        <select id="payment-method">
            <option value="" disabled selected>支払い方法を選択してください</option>
            <option value="コンビニ払い">コンビニ払い</option>
            <option value="カード払い">カード払い</option>
        </select>
    </div>

    <div> 
        <h3>配送先</h3> 
        @if ($address && $address->zipcode && $address->details) 
            <p>〒{{ $address->zipcode }}</p> 
            <p>{{ $address->details }}</p> 
            @if ($address->building) 
                <p>{{ $address->building }}</p> 
            @endif 
        @else 
            <p>配送先情報がありません。</p> 
        @endif 
        <a href="{{ route('address.edit', $item->id) }}" class="button-link">変更する</a> 
    </div> 

    <div class="sidebar">
        <h3>商品代金の内訳</h3>
        <p>商品代金: ¥{{ number_format($item->price) }}</p>
        <p>支払い方法: <span id="selected-method">未選択</span></p>
        
        <!-- カード情報入力欄 -->
        <div id="card-element"></div>
        <div id="card-errors" role="alert"></div>

        <!-- 売り切れの商品に対して購入ボタンを無効化 -->
        @if ($item->sold_out)
            <button class="button-red" id="submit-button" disabled>購入できません</button>
        @else
            <button class="button-red" id="submit-button">購入する</button>
        @endif
    </div>

    <script>
        const stripe = Stripe('{{ env('STRIPE_PUBLIC_KEY') }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const paymentMethod = document.getElementById('payment-method');
        const selectedMethod = document.getElementById('selected-method');

        paymentMethod.addEventListener('change', () => {
            selectedMethod.textContent = paymentMethod.value;
        });

        document.getElementById('submit-button').addEventListener('click', async (event) => {
            event.preventDefault();

            const method = paymentMethod.value;
            if (!method) {
                alert('支払い方法を選択してください。');
                return;
            }

            // コンビニ支払いを選択した場合、Stripe決済画面に遷移しない
            if (method === 'コンビニ払い') {
                alert('コンビニ支払いが選択されました。Stripe決済は使用しません。');
                
                // コンビニ支払いのダミー処理
                fetch('{{ route("purchase.complete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        item_id: '{{ $item->id }}',
                        payment_method: method,
                        stripe_token: 'dummy_token' // ダミートークンを送信
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('購入が完了しました！（コンビニ払い）');
                        window.location.href = '{{ route("index") }}'; // index.blade.php に遷移
                    } else {
                        alert('購入に失敗しました。もう一度お試しください。');
                    }
                });

                return; // コンビニ支払いの場合はここで処理を終了
            }

            // Stripeトークンを作成（カード支払いの場合）
            const {token, error} = await stripe.createToken(card);
            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                fetch('{{ route("purchase.complete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        item_id: '{{ $item->id }}',
                        payment_method: method,
                        stripe_token: token.id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('購入が完了しました！（カード払い）');
                        window.location.href = '{{ route("index") }}'; // index.blade.php に遷移
                    } else {
                        alert('購入に失敗しました。もう一度お試しください。');
                    }
                });
            }
        });
    </script>
</body>
</html>
