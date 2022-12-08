<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
     * @return array<string, array<int, \Illuminate\Validation\Rules\Unique|string>|string>
     */
    public function rules()
    {
        return [
            'name' => [Rule::unique('users')->ignore(Auth::id()), 'string','required'],
            'email' => [Rule::unique('users')->ignore(Auth::id()),'email','string','nullable'],
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
          'name.required' => 'ユーザー名を入力してください。',
          'name.unique' => 'このユーザー名は既に登録されています。',
          'name.string' => 'ユーザー名には文字列を入力してください。',
          'email.email' => '有効なメールアドレスを指定してください。',
          'email.unique' => 'このメールアドレスは既に登録されています。',
          'email.string' => 'メールアドレスには文字列を入力してください。',
          'one_comment.string' => '一言には文字列を入力してください。',
          'one_comment.max' => '一言は400文字以内で入力してください。',
          'birth.integer' => '整数で入力してください。',
        ];
    }
}
