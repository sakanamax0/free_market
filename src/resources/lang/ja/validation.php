<?php

return [

    'required' => ':attribute は必須項目です。',
    'string' => ':attribute は文字列で入力してください。',
    'max' => [
        'string' => ':attributeは:max文字以内で入力してください。',
        'file'   => ':attribute は :max KB以内でアップロードしてください。',
    ],
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'email' => ':attribute には有効なメールアドレスを指定してください。',
    'confirmed' => ':attribute と確認用パスワードが一致しません。',

    
    'required_without' => ':attribute を入力してください。',
    'image'  => ':attribute は画像ファイルを指定してください。',
    'mimes'  => ':attribute は :values 形式でアップロードしてください。',

   
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

        'content' => '本文',
        'image'   => '画像',

        'to_user_id' => '評価対象ユーザー',
        'item_id' => '商品',
        'score' => '評価スコア',
    ],

   
    'custom' => [
        'content' => [
            'required_without' => '本文を入力してください',
            'max' => '本文は400文字以内で入力してください',
        ],
        'image' => [
            'required_without' => '画像をアップロードしてください',
            
            'mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            
            'image' => '画像はPNGまたはJPEGのみアップロードしてください',
        ],
    ],

];
