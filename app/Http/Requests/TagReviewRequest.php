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
            'anime_id.*' => 'integer|nullable',
            'score.*' => 'integer|between:0,100|nullable',
            'comment.*' => 'max:400|string|nullable',
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
            'anime_id.*.integer' => 'アニメIDは自然数を入力してください。',
            'score.*.integer' => '得点は整数で入力してください。',
            'score.*.between' => '得点は0～100点で入力してください。',
            'comment.*.max' => 'コメントは400文字以下で入力してください。',
            'comment.*.string' => 'コメントは文字列を入力してください。',
        ];
    }
}
