<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigRequest extends FormRequest
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
            'email' => 'unique:users,email|email|string|nullable',
            'one_comment' => 'max:400|string|nullable',
            'twitter' => 'nullable|string',
            'birth' => 'integer|nullable',
            'sex' => 'nullable|integer',
        ];
    }

    /**
     * ユーザー情報変更フォームのバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
          'email.email' => '有効なメールアドレスを指定してください。',
          'email.unique' => 'このメールアドレスは既に登録されています。',
          'email.string' => 'メールアドレスには文字列を入力してください。',
          'one_comment.string' => '一言には文字列を入力してください。',
          'one_comment.max' => '一言は400文字以内で入力してください。',
          'birth.integer' => '整数で入力してください。',
        ];
    }
}
