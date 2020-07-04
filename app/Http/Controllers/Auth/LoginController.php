<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
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

    use AuthenticatesUsers;

    /** @var string $redirectTo Where to redirect users after login. */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void Returns nothing.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param mixed                    $user    The authenticated user.
     *
     * @return mixed Returns the response.
     */
    protected function authenticated(Request $request, $user)
    {
        $user->logIp();
    }

    /**
     * Get the field name to use during the login process.
     *
     * @return string Returns the field name.
     */
    public function username()
    {
        return 'username';
    }
}
