<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'contents' => 'required|min:2',
        ];
    }

    public function attributes()
    {
        return [
            'content' => '评论内容',
        ];
    }
}
