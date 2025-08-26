<?php

return [
    'required' => ':attribute は必須項目です。',
    'email' => ':attribute には有効なメールアドレスを指定してください。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'confirmed' => ':attribute と確認用パスワードが一致しません。',

    'attributes' => [
        'username' => 'ユーザー名',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => '確認用パスワード',

        'name' => '名前',
        'zipcode' => '郵便番号',
        'details' => '住所詳細',
        'building' => '建物名',
        'profile_photo' => 'プロフィール画像',

        'to_user_id' => '評価対象ユーザー',
        'item_id' => '商品',
        'score' => '評価スコア',
    ],
];
