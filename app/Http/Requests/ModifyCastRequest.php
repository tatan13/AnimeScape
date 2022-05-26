<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModifyCastRequest extends FormRequest
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
            'furigana' => 'nullable|string',
            'sex' => 'nullable|integer',
            'office' => 'nullable|string',
            'url' => 'nullable|string',
            'twitter' => 'nullable|string',
            'blog' => 'nullable|string',
            'remark' => 'max:400|string|nullable',
        ];
    }

    /**
     * 声優の情報変更申請のバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
            'name.required' => '名前を入力してください。',
            'remark.max' => '変更事由は400文字以下で入力してください。',
            'remark.string' => '変更事由には文字列を入力してください。',
        ];
    }
}
