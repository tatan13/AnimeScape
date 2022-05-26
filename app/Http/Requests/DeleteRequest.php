<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
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
            'remark' => 'max:400|string|nullable',
        ];
    }

    /**
     * 削除申請フォームのバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
          'remark.max' => '削除事由は400文字以下で入力してください。',
          'remark.string' => '削除事由には文字列を入力してください。',
        ];
    }
}
