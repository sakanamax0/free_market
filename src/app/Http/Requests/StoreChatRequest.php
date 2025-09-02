<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatRequest extends FormRequest
{
    public function rules(): array
{
    return [
        'content' => 'nullable|required_without:image|string|max:400', 
        'image'   => 'nullable|required_without:content|image|mimes:jpeg,png|max:2048',
    ];
}


}
