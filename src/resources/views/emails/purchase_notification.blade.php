@component('mail::message')
# あなたの商品が購入されました

**商品名**: {{ $item->name }}

{{ $buyerName }}さんが、あなたの商品を購入しました。

---

**該当商品の購入者チャット画面へアクセスして、商品購入者の評価をしてください。**

@component('mail::button', ['url' => route('chatroom.show', $item->id)])
購入者チャット画面へ
@endcomponent

いつもご利用ありがとうございます。  
{{ config('app.name') }}
@endcomponent
