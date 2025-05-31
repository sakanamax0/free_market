<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入手続き</title>
    <link rel="stylesheet" href="{{ asset('/css/purchase.css') }}">
</head>
<body>
    @include('components.header')

    <div class="container">
        <form class="buy" action="{{ route('purchase.purchase', ['item_id' => $item->id]) }}" method="post">
            <div class="buy__left">
                <div class="item">
                    <div class="item__img">
                        <img src="{{ \Storage::url($item->img_url) }}" alt="">
                    </div>
                    <div class="item__info">
                        <h3 class="item__name">{{ $item->name }}</h3>
                        <p class="item__price">¥ {{ number_format($item->price) }}</p>
                    </div>
                </div>

                <div class="purchases">
                    <div class="purchase">
                        <div class="purchase__flex">
                            <h3 class="purchase__title">支払い方法</h3>
                        </div>
                        <select class="purchase__value" id="payment" name="payment_method" required>
                            <option value="konbini">コンビニ払い</option>
                            <option value="card">クレジットカード払い</option>
                        </select>
                    </div>

                    <div class="purchase">
                        <div class="purchase__flex">
                            <h3 class="purchase__title">配送先</h3>
                            <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}">
                                <button type="button">変更する</button>
                            </a>
                        </div>
                        <div class="purchase__value">
                            <label>〒 <input class="input_destination" name="destination_postcode" value="{{ $user->profile->postcode }}" readonly></label><br>
                            <input class="input_destination" name="destination_address" value="{{ $user->profile->address }}" readonly><br>
                            @if (!empty($user->profile->building))
                            <input class="input_destination" name="destination_building" value="{{ $user->profile->building }}" readonly>
                            @else
                            <input class="input_destination" name="destination_building" placeholder="建物名（任意）" readonly>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="buy__right">
                <div class="buy__info">
                    <table>
                        <tr>
                            <th class="table__header">商品代金</th>
                            <td id="item__price" class="table__data" value="{{ $item->price }}">¥ {{ number_format($item->price) }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">支払い方法</th>
                            <td id="pay_confirm" class="table__data">
                                {{ old('payment_method') === 'card' ? 'クレジットカード払い' : 'コンビニ払い' }}
                            </td>
                        </tr>
                    </table>
                </div>

                @csrf
                @if ($item->sold())
                    <button class="btn disable" disabled>売り切れました</button>
                @elseif ($item->mine())
                    <button class="btn disable" disabled>購入できません</button>
                @else
                    <button type="submit" class="btn">購入する</button>
                @endif
            </div>
        </form>
    </div>

    <script>
        const paymentSelect = document.getElementById('payment');
        const paymentDisplay = document.getElementById('pay_confirm');

        paymentSelect.addEventListener('change', () => {
            const selected = paymentSelect.value === 'card' ? 'クレジットカード払い' : 'コンビニ払い';
            paymentDisplay.textContent = selected;
        });
    </script>
</body>
</html>
