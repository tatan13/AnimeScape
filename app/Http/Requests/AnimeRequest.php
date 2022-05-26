<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnimeRequest extends FormRequest
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
            'furigana'  => 'nullable|string',
            'year'  => 'required|integer',
            'coor' => 'required|integer',
            'number_of_episode'  => 'nullable|integer',
            'public_url'  => 'nullable|string',
            'twitter' => 'nullable|string',
            'hash_tag' => 'nullable|string',
            'company1' => 'nullable|string',
            'company2' => 'nullable|string',
            'company3' => 'nullable|string',
            'city_name'  => 'nullable|string',
            'media_category'  => 'nullable|integer',
            'summary'  => 'nullable|string',
            'd_anime_store_id'  => 'nullable|string',
            'amazon_prime_video_id'  => 'nullable|string',
            'fod_id'  => 'nullable|string',
            'unext_id'  => 'nullable|string',
            'abema_id'  => 'nullable|string',
            'disney_plus_id'  => 'nullable|string',
            'remark' => 'max:400|string|nullable',
        ];
    }

    /**
     * アニメの基本情報変更申請のバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
            'title.required' => 'タイトルを入力してください。',
            'remark.max' => '事由は400文字以下で入力してください。',
            'remark.string' => '事由には文字列を入力してください。',
        ];
    }
}
