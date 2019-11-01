<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use App\UsersCI;
use App\UserSocial;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Socialite;

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
    protected $redirectTo = '/dashboard';

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->username = $this->findUsername();
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        //Authenticate user from Old DB
        $old_user = UsersCI::where('email', $request->input('email'))->where('password', md5($request->input('password')))->where('synced_to_new_db', 0)->first();

        if (!is_null($old_user)) {
            $new_user = User::where('email', $request->input('email'))->orWhere('username', $request->input('login'))->first();
            if (!is_null($new_user)) {
                $new_user->password = bcrypt($request->input('passowrd'));
                $new_user->save();

                $old_user->synced_to_new_db = 1;
                $old_user->save();
            }
        }

        //Authenticate User from New DB
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // dd($user);
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($request->user()->hasRole(SUPER_ADMIN_ROLE_ID)) {
            return redirect()->route('admin.dashboard');
        } else if ($request->user()->hasRole(HAPITY_USER_ROLE_ID)) {
            return redirect()->route('user.dashboard');
        }
    }

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {

        switch ($provider) {
            case 'facebook':
                try {
                    $user = Socialite::driver($provider)->user();

                    if (!is_null($user)) {
                        $fb_user = $user->user;

                        $user_name_info = explode(' ', $fb_user['name']);

                        $first_name = '';
                        $last_name = '';

                        if (!empty($user_name_info)) {
                            $first_name = count($user_name_info) > 0 ? $user_name_info[0] : "";
                            $last_name = count($user_name_info) > 1 ? $user_name_info[1] : "";
                        }

                        if (!empty($fb_user)) {
                            $local_user = User::where('email', $fb_user['email'])->with(['profile', 'social'])->first();

                            if (is_null($local_user)) {
                                $new_user = new User();
                                $new_user->email = $fb_user['email'];
                                $new_user->username = strtolower(str_replace(' ', '_', $fb_user['name'])) . '_' . time();
                                $new_user->password = bcrypt('h@p!ty_soc!@l_signup');
                                $new_user->save();

                                $new_user->roles()->attach(HAPITY_USER_ROLE_ID);

                                $new_user_profile = new UserProfile();
                                $new_user_profile->user_id = $new_user->id;
                                $new_user_profile->first_name = $first_name;
                                $new_user_profile->last_name = $last_name;
                                $new_user_profile->full_name = $fb_user['name'];
                                $new_user_profile->screen_name = $fb_user['name'];
                                $new_user_profile->email = $new_user->email;
                                $new_user_profile->screen_name = $new_user->email;
                                $new_user_profile->auth_key = bcrypt($new_user->username);
                                $new_user_profile->save();

                                $new_user_social = new UserSocial();
                                $new_user_social->user_id = $new_user->id;
                                $new_user_social->social_id = $fb_user['id'];
                                $new_user_social->email = $new_user->email;
                                $new_user_social->platform = $provider;
                                $new_user_social->save();

                                Auth::login($new_user);

                                return redirect()->route('user.dashboard');

                            } else {
                                $user_existing_social = UserSocial::where('social_id', $fb_user['id'])->where('user_id', $local_user->id)->first();

                                if (is_null($user_existing_social)) {
                                    $new_user_social = new UserSocial();
                                    $new_user_social->user_id = $local_user->id;
                                    $new_user_social->social_id = $fb_user['id'];
                                    $new_user_social->email = $new_user->email;
                                    $new_user_social->platform = $provider;
                                    $new_user_social->save();
                                }

                                Auth::login($local_user);

                                return redirect()->route('user.dashboard');

                            }

                        }
                    }
                } catch (Exception $e) {
                    Log::debug($provider . ' login failed: ' . $e->getMessage());
                }
                break;
            case 'twitter':
                break;
        }

        // $user->token;
    }
}
