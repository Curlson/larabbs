<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','between:3,25',' regex:/^[A-Za-z0-9\-\_]+$/', ],
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=208,min_height=208'
        ];
    }
    
    public function attributes()
    {
        return [
            'name' => '用户名',
            'email' => '邮箱',
            'introduction' => '个人简介',
            'avatar' => '头像',
        ];
    }
}
