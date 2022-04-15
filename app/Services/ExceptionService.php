<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ExceptionService
{

    /**
     * 存在しなかったら404ページを表示
     * 
     * @template T
     * @param T $data
     * @return void
     */
    public function render404IfNotExist($data)
    {
        if (!isset($data)) {
            abort(404);
        }
    }

    /**
     * ルートユーザーでなければ404ページを表示
     * 
     * @return void
     */
    public function render404IfNotRootUser()
    {
        if (Auth::user()->uid != "root") {
            abort(404);
        }
    }
}