<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewsRequest extends FormRequest
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
            'anime_id.*' => 'required|integer',
            'score.*' => 'integer|between:0,100|nullable',
            'one_word_comment.*' => 'max:400|string|nullable',
            'will_watch.*' => 'integer|between:0,3|nullable',
            'watch.*' => 'bool',
            'now_watch.*' => 'bool',
            'give_up.*' => 'bool',
            'number_of_interesting_episode.*' => 'integer|min:0|nullable',
            'before_score.*' => 'integer|between:0,100|nullable',
            'before_comment.*' => 'max:400|string|nullable',
            'number_of_watched_episode.*' => 'integer|min:0|nullable',
        ];
    }

    /**
     * 得点フォームのバリデーションメッセージ
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
          'anime_id.*.integer' => '不正な入力です。',
          'anime_id.*.required' => '不正な入力です。',
          'score.*.integer' => '得点は整数で入力してください。',
          'score.*.between' => '得点は0～100点で入力してください。',
          'before_score.*.integer' => '視聴完了前得点は整数で入力してください。',
          'before_score.*.between' => '視聴完了前得点は0～100点で入力してください。',
          'one_word_comment.*.max' => '一言感想は400文字以下で入力してください。',
          'one_word_comment.*.string' => '一言感想には文字列を入力してください。',
          'before_comment.*.max' => '視聴完了前一言感想は400文字以下で入力してください。',
          'before_comment.*.string' => '視聴完了前一言感想には文字列を入力してください。',
          'number_of_interesting_episode.*.integer' => '面白さがわかる話数は整数で入力してください。',
          'number_of_interesting_episode.*.min' => '面白さがわかる話数は0以上で入力してください。',
          'number_of_watched_episode.*.integer' => '視聴話数は整数で入力してください。',
          'number_of_watched_episode.*.min' => '視聴話数は0以上で入力してください。',
        ];
    }
}
