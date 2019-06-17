<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TopicStoreRequest extends FormRequest
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
    
    
    public function rules()
    {
        return [
            'title' => 'required|string|min:2',
            'body' => 'required|string|min:3',
            'category_id' => ['required', 'numeric', Rule::exists('categories', 'id')],
        ];
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'body' => '博文内容',
            'category_id' => '分类',
        ];
    }
}
