<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    

    public function login(Request $request)
    {
        $credentials = request(['username', 'password']);

        $rules = [
            'username'    => 'required',
            'password' => 'required',
        ];
        $messages = [
            '*.required' => 'This is required field.'
        ];
        $this->validate($request, $rules, $messages);

        if(filter_var($credentials['username'], FILTER_VALIDATE_EMAIL)) {
            $loginRequest['email'] = $credentials['username'];
            $loginRequest['password'] = $credentials['password'];

        } else {
            $loginRequest = $credentials;
        }
        
        if (auth()->attempt($loginRequest)) {
            
            return redirect()->intended($this->redirectPath());
        }

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'username' => 'These credentials do not match our records.',
            ]);
    } 

}
