<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Validator;
use App\User;
use App\UserProfile;

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
            'username' => 'required'
        );
        $messages = array(
            'password.required' => 'Password is required.',
            'username.required' => 'Username is required.'
        );

        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->messages()->all();
            $returnData['status'] = 'failure';
            $returnData['message'] = $messages[0];
            return response()->json($returnData);
        }

        $credentials = request(['username', 'password']);

        if(filter_var($credentials['username'], FILTER_VALIDATE_EMAIL)) {
            $loginRequest['email'] = $credentials['username'];
            $loginRequest['password'] = $credentials['password'];

        } else {
            $loginRequest = $credentials;
        }
        
        if ($token = auth()->attempt($loginRequest)) {
            $userProfile = User::with('profile')->where('id', Auth::id())->first()->toArray();
            $user_info = array();
            $user_info['user_id'] = $userProfile['id'];
            $user_info['profile_picture'] = $userProfile['profile']['profile_picture'];
            $user_info['email'] = $userProfile['email'];
            $user_info['username'] = $userProfile['username'];
            $user_info['auth_key'] = $userProfile['profile']['auth_key'];
            $user_info['token'] = $token;
//            $user_info['expires_in'] = auth()->factory()->getTTL() * 60;

            $returnData['status'] = 'success';
            $returnData['user_info'] = $user_info;
    
            return response()->json($returnData, 200);
        }
        else {
            return response()->json(['status' => 'failure','message' => 'Invalid Credentials'], 401);
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
            'email'  => 'unique:users,email|email|required',
            'username' => 'unique:users,username|required',
            'password' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0]
            );
            return response()->json($response);
        }else{

            $username        = $request->input('username');
            $email           = $request->input('email');
            $password        = $request->input('password');
            $profile_picture = $request->input('profile_picture');

            //  Saving User Data
            $user            = new User();
            $user->username  = $username;
            $user->email     = $email;
            $user->password  = bcrypt($password);
            $user->save();

            //  Upload Profile Picture if Exists
            if(empty($profile_picture)){
                $imageName = 'null.png';
            }else{
                $pos  = strpos($profile_picture, ';');
                $type = explode(':image/', substr($profile_picture, 0, $pos))[1];

                $data_replace = 'data:image/'.$type.';base64,';
                $image = str_replace($data_replace, '', $profile_picture);
                $image = str_replace(' ', '+', $image);
                $imageName = Str::random(6).'_'.now()->timestamp.'.'.$type;
                \File::put(storage_path('app\public'). "\'" . $imageName, base64_decode($image));
            }
//            file_put_contents( public_path($imageName), base64_decode($image));
            //  Saving User Profile
            $profile         = new UserProfile();
            $profile->email  = $email;
            $profile->auth_key  = bcrypt($username);
            $profile->profile_picture  = $imageName;
            $user->profile()->save($profile);

            //  Logging In User and Make Response Array
            $token = auth()->attempt(['username' => $username, 'password' => $password]);
            $user_info = array();
            $user_info['user_id'] = $user->id;
            $user_info['profile_picture'] = asset("storage/'".$profile->profile_picture);
            $user_info['email'] = $user->email;
            $user_info['username'] = $user->username;
            $user_info['auth_key'] = $profile->auth_key;
            $user_info['token'] = $token;

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
        $userProfile = User::with('profile')->where('id', Auth::id())->first()->toArray();
        $user_info = array();
        $user_info['user_id'] = $userProfile['id'];
        $user_info['profile_picture'] = asset("storage/'".$userProfile['profile']['profile_picture']);
        $user_info['email'] = $userProfile['email'];
        $user_info['username'] = $userProfile['username'];
        $user_info['auth_key'] = $userProfile['profile']['auth_key'];

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
        $user_id        = $request->input('user_id');
        $rules = array(
            'email'  => 'email|unique:users,email,'.$user_id,
            'username' => 'unique:users,username,'.$user_id
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0]
            );
            return response()->json($response);
        }
        $username        = $request->input('username');
        $email           = $request->input('email');
        $password        = $request->input('password');
        $profile_picture = $request->input('profile_picture');

        //  Saving User Data
        $user = User::find(Auth::id());
        $user->username  = $username;
        $user->email     = $email;
        if(!empty($password)) {
            $user->password = bcrypt($password);
        }
        $user->save();

        $update_array = array();
        //  Upload Profile Picture if Exists
        if(!empty($profile_picture)){
            $pos  = strpos($profile_picture, ';');
            $type = explode(':image/', substr($profile_picture, 0, $pos))[1];

            $data_replace = 'data:image/'.$type.';base64,';
            $image = str_replace($data_replace, '', $profile_picture);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(6).'_'.now()->timestamp.'.'.$type;
            \File::put(storage_path('app\public'). "\'" . $imageName, base64_decode($image));
            $update_array['profile_picture'] = $imageName;
        }
        $update_array['email'] = $email;
        $user->profile()->update($update_array);
        $profile = UserProfile::select('profile_picture','auth_key')->where(['user_id' => Auth::id()])->get()->toArray();
        $user_info = array();
        $user_info['user_id'] = $user->id;
        $user_info['profile_picture'] = asset("storage/'".$profile[0]['profile_picture']);
        $user_info['email'] = $user->email;
        $user_info['username'] = $user->username;
        $user_info['auth_key'] = $profile[0]['auth_key'];

        $returnData['status'] = 'success';
        $returnData['profile_info'] = $user_info;

        return response()->json($returnData, 200);
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
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
