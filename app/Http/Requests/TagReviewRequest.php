<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagReviewRequest extends FormRequest
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
            'name.*' => 'string|nullable',
            'score.*' => 'integer|between:0,100|nullable',
            'comment.*' => 'max:400|string|nullable',
            'tag_group_id.*' => 'required|integer'
        ];
    }

    /**
     * タグレビューフォームのバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
            'name.*.string' => 'タグ名は文字列を入力してください。',
            'score.*.integer' => '得点は整数で入力してください。',
            'score.*.between' => '得点は0～100点で入力してください。',
            'comment.*.max' => 'コメントは400文字以下で入力してください。',
            'comment.*.string' => 'コメントは文字列を入力してください。',
            'tag_group_id.*.required' => 'タググループの入力が不正です。',
            'tag_group_id.*.integer' => 'タググループの入力が不正です。',
        ];
    }
}
