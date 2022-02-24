<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactForm extends FormRequest
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
            'comment' => 'required|string',
            'auth' => 'regex:/にんしょう/',
        ];
    }

    public function messages()
    {
        return [
          'comment.required' => '要望内容を入力してください。',
          'comment.string' => '要望内容には文字列を入力してください。',
          'auth.regex' => '「にんしょう」と入力してください。'
        ];
    }
}
