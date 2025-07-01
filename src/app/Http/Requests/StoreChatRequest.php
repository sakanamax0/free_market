<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => 'nullable|required_without:image|string|max:1000',
            'image'   => 'nullable|required_without:content|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => '本文を入力してください',
            'content.max' => '本文は400文字以内で入力してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
