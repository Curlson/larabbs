<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        ];
    }
    
    public function attributes()
    {
        return [
            'name' => '用户名',
            'email' => '邮箱',
            'introduction' => '个人简介',
        ];
    }
}
