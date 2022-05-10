<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModifyAnimeRequest extends FormRequest
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
            'title' => 'required|string',
            'title_short'  => 'nullable|string',
            'year'  => 'required|integer',
            'coor' => 'required|integer',
            'public_url'  => 'nullable|string',
            'twitter' => 'nullable|string',
            'hash_tag' => 'nullable|string',
            'sequel'  => 'nullable|integer',
            'company'  => 'nullable|string',
            'city_name'  => 'nullable|string',
        ];
    }

    /**
     * アニメの基本情報修正申請のバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
            'title.required' => 'タイトルを入力してください。',
        ];
    }
}
