<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
     * @return array<string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'tag_group_id' => 'required|integer',
            'spoiler' => 'bool',
            'explanation' => 'max:400|string|nullable',
        ];
    }

    /**
     * タグフォームのバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
            'name.required' => '名前を入力してください。',
            'tag_group_id.required' => 'タググループの入力が不正です。',
            'tag_group_id.integer' => 'タググループの入力が不正です。',
            'explanation.max' => '説明は400文字以下で入力してください。',
            'explanation.string' => '説明は文字列を入力してください。',
        ];
    }
}
