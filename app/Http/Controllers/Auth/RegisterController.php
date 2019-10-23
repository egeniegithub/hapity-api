<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Token;
use App\User;
use App\UserProfile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'agree_terms_and_conditions' => 'required',
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $agree_terms_and_conditions = isset($data['agree_terms_and_conditions']) && !empty($data['agree_terms_and_conditions'])  ? 1 : 0;

        $user = new User();
        $user->name = $data['username'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->agree_terms_and_conditions = $agree_terms_and_conditions;
        $user->save();

        $user->roles()->attach(HAPITY_USER_ROLE_ID);
        
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => $data['username'],
            'full_name' => $data['username'],
            'email' => $data['email'],
            'auth_key' => md5($data['username']),
        ]);

        
        $email = $user->email;
        $data = array(
            'name' => $user->username,
            'email' => $user->email,
        );
        Mail::send('emails/welcome', ['data' => $data], function ($message) use ($email) {
            $message->to($email,'chris@hapity.com')->subject('Welcome');
        });

        return $user;
    }
}
