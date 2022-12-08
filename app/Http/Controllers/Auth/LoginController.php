<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログインに使用するusernameをnameに変更
     * @return string
     */
    public function username()
    {
        return 'name'; //ユーザー名
    }

    /**
     * ログアウト後のリダイレクト先を指定
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loggedOut(\Illuminate\Http\Request $request)
    {
        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * remember meを実装
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function authenticated(\Illuminate\Http\Request $request)
    {
        $params = $request->all();
        if (Auth::attempt(['name' => $params['name'], 'password' => $params['password']])) {
            if (isset($params['remember-me']) && $params['remember-me'] === 'on') {
                Auth::attempt(['name' => $params['name'], 'password' => $params['password']], true);
            } else {
                Auth::attempt(['name' => $params['name'], 'password' => $params['password']], false);
            }
        }
    }
}
