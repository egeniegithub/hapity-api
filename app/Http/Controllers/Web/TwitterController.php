<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\UserProfile;
use App\UserSocial;
use Illuminate\Validation\Validator;

class TwitterController extends Controller
{
     //

    public function __construct()
    {
        auth()->setDefaultDriver('api');
        $this->middleware('auth:api', ['except' => ['twitter_login']]);
    }
    
    function twitter_login(Request $request) {
       
        $rules = array(
            'twitter_id' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'profile_picture' => 'required',
        );
        $messages = array(
            'twitter_id.required' => 'Twitter id is required.',
            'username.required' => 'Username already registered.',
            'email.required' => 'Email is required.',
            'profile_picture.required' => 'Profile Picture is required.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json($messages);
        }
        

        $input = $request->all();

        $checkUserSocialAccountExist = User::with('social')
//        ->where('social_id', '=', $input['twitter_id'])
        ->where('email', $input['email'])
        ->first();

        if(empty($checkUserSocialAccountExist->toArray())){
            // User Not Register
            $User = new User();
            $User->email = $input['email'];
            $User->username = $input['username'];
            $User->password = bcrypt($input['username']);
            $User->save();

            $UserProfile = new UserProfile();
            $UserProfile->user_id = $User->id;
            $UserProfile->email = $input['email'];
            $UserProfile->auth_key = bcrypt($input['username']);
            $UserProfile->profile_picture = $input['profile_picture'];
            $UserProfile->save();

            $UserSocial = new UserSocial();
            $UserSocial->user_id = $User->id;
            $UserSocial->social_id = $input['twitter_id'];
            $UserSocial->email = $input['email'];
            $UserSocial->platform = "twitter";
            $UserSocial->save();
            $token = auth()->fromUser($User);
            $response['status'] = "success";
            $response['user_info']['user_id'] = $User->id;
            $response['user_info']['profile_picture'] = $UserProfile->profile_picture;
            $response['user_info']['email'] = $User->email;
            $response['user_info']['username'] = $User->username;
            $response['user_info']['login_type'] = $UserSocial->platform;
            $response['user_info']['social_id'] = $UserSocial->social_id;
            $response['user_info']['join_date'] = date('Y-m-d',strtotime($User->created_at));
            $response['user_info']['auth_key'] = $User->auth_key;
            $response['user_info']['token'] = $token;

        } else if(!empty($checkUserSocialAccountExist['social']->toArray()) && $checkUserSocialAccountExist['social'][0]['platform'] == "twitter"){
            if(!empty($input['profile_picture'])){
                $user = User::find($checkUserSocialAccountExist['id']);
                $user->profile()->update(['profile_picture' => $input['profile_picture']]);
            }
            $userProfileData = $checkUserSocialAccountExist->profile()->get()->first();
            $token = auth()->fromUser($checkUserSocialAccountExist);
            $response['status'] = "success";
            $response['user_info']['user_id'] = $checkUserSocialAccountExist['id'];
            $response['user_info']['profile_picture'] = $userProfileData['profile_picture'];
            $response['user_info']['email'] = $checkUserSocialAccountExist['email'];
            $response['user_info']['username'] = $checkUserSocialAccountExist['username'];
            $response['user_info']['login_type'] = $checkUserSocialAccountExist['social'][0]['platform'];
            $response['user_info']['social_id'] = $checkUserSocialAccountExist['social'][0]['social_id'];
            $response['user_info']['join_date'] = date('Y-m-d',strtotime($checkUserSocialAccountExist['created_at']));
            $response['user_info']['auth_key'] = $userProfileData['auth_key'];
            $response['user_info']['token'] = $token;

        } else{

            $UserSocial = new UserSocial();
            $UserSocial->user_id = $checkUserSocialAccountExist['id'];
            $UserSocial->social_id = $input['twitter_id'];
            $UserSocial->email = $input['email'];
            $UserSocial->platform = "twitter";
            $UserSocial->save();

            if(!empty($input['profile_picture'])){
                $user = User::find($checkUserSocialAccountExist['id']);
                $user->profile()->update(['profile_picture' => $input['profile_picture']]);
            }

            $userProfileData = $checkUserSocialAccountExist->profile()->get()->first();
            $token = auth()->fromUser($checkUserSocialAccountExist);
            $response['status'] = "success";
            $response['user_info']['user_id'] = $checkUserSocialAccountExist['id'];
            $response['user_info']['profile_picture'] = $userProfileData['profile_picture'];
            $response['user_info']['email'] = $checkUserSocialAccountExist['email'];
            $response['user_info']['username'] = $checkUserSocialAccountExist['username'];
            $response['user_info']['login_type'] = $UserSocial->platform;
            $response['user_info']['social_id'] = $UserSocial->social_id;
            $response['user_info']['join_date'] = date('Y-m-d',strtotime($checkUserSocialAccountExist['created_at']));
            $response['user_info']['auth_key'] = $userProfileData['auth_key'];
            $response['user_info']['token'] = $token;
        }

        return response()->json($response);
    }
}
