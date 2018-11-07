<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        if ($this->attemptLogin($request)) {
            $user = Auth::guard()->user();
            $user->generateToken();
            return success([
                'token' => $user->token,
                'role' => $user->role
            ]);
        }
        return unauthorized('invalid login');
    }

    public function logout(Request $request)
    {
        $user = User::get();
        if (!$user) return unauthorized();
        $user->resetToken();
        return success([
            'message' => 'logout success'
        ]);
    }
}
