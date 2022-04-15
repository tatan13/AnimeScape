<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cast;
use App\Models\User;
use App\Services\ExceptionService;
use Illuminate\Support\Facades\Auth;

class CastController extends Controller
{
    private $exceptionService;

    public function __construct(ExceptionService $exceptionService)
    {
        $this->exceptionService = $exceptionService;
    }

    /**
     * 声優の情報を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cast = Cast::find($id);

        $this->exceptionService->render404IfNotExist($cast);

        $act_animes = $cast->actAnimes;

        return view('cast', [
            'cast' => $cast,
            'act_animes' => $act_animes,
        ]);
    }

    /**
     * 声優のお気に入り登録処理
     *
     * @param int $id
     * @return void
     */
    public function like($id)
    {
        $cast = Cast::find($id);

        $this->exceptionService->render404IfNotExist($cast);

        if (Auth::check()) {
            $auth_user = Auth::user();
            if (!$auth_user->isLikeCast($id)) {
                $auth_user->likeCasts()->attach($id);
            }
        }
    }

    /**
     * 声優のお気に入り解除処理
     *
     * @param int $id
     * @return void
     */
    public function unlike($id)
    {
        $cast = Cast::find($id);

        $this->exceptionService->render404IfNotExist($cast);

        if (Auth::check()) {
            $auth_user = Auth::user();
            if ($auth_user->isLikeCast($id)) {
                $auth_user->likeCasts()->detach($id);
            }
        }
    }
}
