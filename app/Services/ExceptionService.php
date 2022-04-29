<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ExceptionService
{
    /**
     * ルートユーザーでなければ404ページを表示
     *
     * @return void
     */
    public function render404IfNotRootUser()
    {
        if (Auth::user()->name != "root") {
            abort(404);
        }
    }
}
