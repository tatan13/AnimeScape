<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'score' => 'integer|between:0,100|nullable',
            'one_word_comment' => 'max:400|string|nullable',
            'long_word_comment' => 'string|nullable',
            'will_watch' => 'integer|min:0|nullable',
            'watch' => 'bool',
            'spoiler' => 'bool',
            'give_up' => 'bool',
            'now_watch' => 'bool',
            'number_of_interesting_episode' => 'integer|nullable',
            'before_score' => 'integer|between:0,100|nullable',
            'before_comment' => 'max:400|string|nullable',
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
          'score.integer' => '得点は整数で入力してください。',
          'score.between' => '得点は0～100点で入力してください。',
          'one_word_comment.max' => '一言感想は400文字以下で入力してください。',
          'one_word_comment.string' => '一言感想には文字列を入力してください。',
          'long_word_comment.string' => '長文感想には文字列を入力してください。',
          'number_of_interesting_episode.integer' => '面白さがわかる話数は整数で入力してください。',
          'number_of_interesting_episode.min' => '面白さがわかる話数は0以上で入力してください。',
          'before_score.integer' => '視聴完了前得点は整数で入力してください。',
          'before_score.between' => '視聴完了前得点は0～100点で入力してください。',
          'before_comment.max' => '視聴完了前一言感想は400文字以下で入力してください。',
          'before_comment.string' => '視聴完了前一言感想には文字列を入力してください。',
        ];
    }
}
