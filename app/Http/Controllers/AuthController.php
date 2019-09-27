<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use JWTAuth;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        auth()->setDefaultDriver('api');
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $rules = array(
            'password' => 'required',
            'login' => 'required',
        );
        $messages = array(
            'password.required' => 'Password is required.',
            'login.required' => 'Login Id is required.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $returnData['status'] = 'failure';
            $returnData['message'] = $messages[0];
            return response()->json($returnData);
        }

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $login]);
        $credentials = $request->only($field, 'password');

        if (Auth::attempt($credentials)) {

            $user = User::with('profile')->find(Auth::id());

            if ($token = JWTAuth::fromUser($user)) {
                $userProfile = $user->toArray();
                $user_info = array();
                $user_info['user_id'] = $userProfile['id'];
                $user_info['profile_picture'] = asset('images/profile_pictures/' . $userProfile['profile']['profile_picture']);
                $user_info['email'] = $userProfile['email'];
                $user_info['username'] = $userProfile['username'];
                $user_info['auth_key'] = $userProfile['profile']['auth_key'];
                $user_info['token'] = $token;

                $returnData['status'] = 'success';
                $returnData['user_info'] = $user_info;

                return response()->json($returnData, 200);
            } else {
                return response()->json(['status' => 'failure', 'message' => 'Invalid Credentials'], 401);
            }
        } else {
            return response()->json(['status' => 'failure', 'message' => 'Invalid Credentials'], 401);
        }
    }

    /**
     * Gets the data for signup user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $rules = array(
            'email' => 'unique:users,email|email|required',
            'username' => 'unique:users,username|required',
            'password' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0],
            );
            return response()->json($response);
        } else {

            $username = $request->input('username');
            $email = $request->input('email');
            $password = $request->input('password');
            $profile_picture = $request->input('profile_picture');
            $name = $request->input('name');

            $name = !empty($name) ? $name : ucwords($username);

            //  Saving User Data
            $user = new User();
            $user->name = $name;
            $user->username = $username;
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->save();

            //  Upload Profile Picture if Exists
            $imageName = $this->handle_base_64_profile_picture($user, $profile_picture);

            //  Saving User Profile
            $profile = new UserProfile();
            $profile->email = $email;
            $profile->auth_key = bcrypt($username);

            if (!empty($imageName)) {
                $profile->profile_picture = $imageName;
            }

            $user->profile()->save($profile);

            //  Logging In User and Make Response Array
            $token = auth()->attempt(['username' => $username, 'password' => $password]);
            $user_info = array();
            $user_info['user_id'] = $user->id;
            $user_info['profile_picture'] = asset("images/profile_pictures/" . $profile->profile_picture);
            $user_info['email'] = $user->email;
            $user_info['username'] = $user->username;
            $user_info['auth_key'] = $profile->auth_key;
            $user_info['token'] = $token;
            $user_info['join_date'] = $user->created_at;

            $returnData['status'] = 'success';
            $returnData['user_info'] = $user_info;
            return response()->json($returnData, 200);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the User Profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfile(Request $request)
    {
        $userProfile = User::with(['profile', 'social'])->where('id', Auth::id())->first()->toArray();
        $user_info = array();
        $user_info['user_id'] = $userProfile['id'];
        $user_info['profile_picture'] = asset("images/profile_pictures/" . $userProfile['profile']['profile_picture']);
        $user_info['email'] = $userProfile['email'];
        $user_info['username'] = $userProfile['username'];
        $user_info['auth_key'] = $userProfile['profile']['auth_key'];
        $user_info['screen_name'] = $userProfile['profile']['screen_name'];
        $user_info['join_date'] = $userProfile['created_at'];

        $returnData['status'] = 'success';
        $returnData['profile_info'] = $user_info;

        return response()->json($returnData, 200);
    }

    /**
     * Edit User Profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUserProfile(Request $request)
    {
        $user_id = $request->input('user_id');
        $rules = array(
            'email' => 'unique:users,email,' . $user_id,
            'username' => 'unique:users,username,' . $user_id,
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0],
            );
            return response()->json($response);
        }
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $profile_picture = $request->input('profile_picture');

        //  Saving User Data
        $user = User::find(Auth::id());
        if (!empty($username)) {
            $user->username = $username;
        }
        if (!empty($email)) {
            $user->email = $email;
        }
        if (!empty($password)) {
            $user->password = bcrypt($password);
        }
        $user->save();

        $update_array = array();
        //  Upload Profile Picture if Exists

        $imageName = $this->handle_base_64_profile_picture($user, $profile_picture);

        if (!empty($imageName)) {
            $update_array['profile_picture'] = $imageName;
        }

        if (!empty($email)) {
            $update_array['email'] = $email;
        }
        $user->profile()->update($update_array);

        $profile = User::with('profile')->where('id', Auth::id())->first()->toArray();
        $user_info = array();
        $user_info['user_id'] = $profile['id'];
        $user_info['profile_picture'] = asset("images/profile_pictures/" . $profile['profile']['profile_picture']);
        $user_info['email'] = $profile['email'];
        $user_info['username'] = $profile['username'];
        $user_info['auth_key'] = $profile['profile']['auth_key'];

        $returnData['status'] = 'success';
        $returnData['profile_info'] = $user_info;

        return response()->json($returnData, 200);
    }

    private function handle_base_64_profile_picture($user, $profile_picture)
    {
        $imageName = '';
        if (!empty($profile_picture)) {
            $pos = strpos($profile_picture, ';');
            $type = explode(':image/', substr($profile_picture, 0, $pos))[1];

            $data_replace = 'data:image/' . $type . ';base64,';
            $image = str_replace($data_replace, '', $profile_picture);
            $image = str_replace(' ', '+', $image);
            $imageName = 'profile_picture_' . $user->id . '.' . $type;
            File::put(public_path('images' . DIRECTORY_SEPARATOR . 'profile_pictures' . DIRECTORY_SEPARATOR . $imageName), base64_decode($image));
        }

        return $imageName;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
