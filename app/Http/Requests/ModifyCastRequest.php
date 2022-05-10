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
        ];
    }

    /**
     * 声優の情報修正申請のバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
            'name.required' => '名前を入力してください。',
        ];
    }
}
