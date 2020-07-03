<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /** @var string $redirectTo Where to redirect users after registration. */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void Returns nothing.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data The request data.
     *
     * @return \Illuminate\Contracts\Validation\Validator Returns the validator.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'min:3', 'max:16', 'alpha_dash', 'unique:users'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data The validated request data.
     *
     * @return \App\User Returns the authenticated user.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $this->logIpAddress($user);
        $user->attachRole('user');
        return $user;
    }

    /**
     * Log the users ip address upon registration.
     *
     * @param \App\User $user The authenticated user.
     *
     * @return void Returns nothing.
     */
    protected function logIpAddress(User $user)
    {
        $user->register_ip_address = request()->ip();
        $user->last_login_ip_address = request()->ip();
        $user->save();
    }
}
