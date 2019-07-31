<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;
use App\UserProfile;
use App\UserSocial;

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
            'email.unique' => 'Email is required.',
            'profile_picture.required' => 'Password is required.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json($messages);
        }
        

        $input = $request->all();

        $checkUserSocialAccountExist = UserSocial::with('user')
        ->where('social_id', '=', $input['twitter_id'])
        ->where('email', $input['email'])
        ->first();
        
        if ($checkUserSocialAccountExist && $checkUserSocialAccountExist['platform'] == "twitter") {
            // user register to twitter social account
            $userProfileData = $checkUserSocialAccountExist->user->profile()->get()->first();
            
            $response['status'] = "success";
            $response['user_info']['user_id'] = $checkUserSocialAccountExist->user['id'];
            $response['user_info']['profile_picture'] = $userProfileData['profile_picture'];
            $response['user_info']['email'] = $checkUserSocialAccountExist->user['email'];
            $response['user_info']['username'] = $checkUserSocialAccountExist->user['username'];
            $response['user_info']['login_type'] = $checkUserSocialAccountExist['platform'];
            $response['user_info']['social_id'] = $checkUserSocialAccountExist['social_id'];
            $response['user_info']['join_date'] = date('Y-m-d',strtotime($checkUserSocialAccountExist->user['created_at']));
            $response['user_info']['auth_key'] = $userProfileData['auth_key'];
            $response['user_info']['token'] = "";
        }
        else if ($checkUserSocialAccountExist && $checkUserSocialAccountExist['platform'] != "twitter") {
            // user not register to twitter social account
            $userProfileData = $checkUserSocialAccountExist->user->profile()->get()->first();

            $UserSocial = new UserSocial();
            $UserSocial->user_id = $checkUserSocialAccountExist->user['id'];
            $UserSocial->social_id = $input['twitter_id'];
            $UserSocial->email = $input['email'];
            $UserSocial->platform = "twitter";
            $UserSocial->save();

            $response['status'] = "success";
            $response['user_info']['user_id'] = $checkUserSocialAccountExist->user['id'];
            $response['user_info']['profile_picture'] = $userProfileData['profile_picture'];
            $response['user_info']['email'] = $checkUserSocialAccountExist->user['email'];
            $response['user_info']['username'] = $checkUserSocialAccountExist->user['username'];
            $response['user_info']['login_type'] = $UserSocial->platform;
            $response['user_info']['social_id'] = $UserSocial->social_id;
            $response['user_info']['join_date'] = date('Y-m-d',strtotime($checkUserSocialAccountExist->user['created_at']));
            $response['user_info']['auth_key'] = $userProfileData['auth_key'];
            $response['user_info']['token'] = "";
        }
        else {
            // user not register to social account
            $User = new User();
            $User->email = $input['email'];
            $User->username = $input['username'];
            $User->password = Hash::make(rand());
            $User->save();

            $UserProfile = new UserProfile();
            $UserProfile->user_id = $User->id;
            $UserProfile->email = $input['email'];
            $UserProfile->profile_picture = $input['profile_picture'];
            $UserProfile->save();

            $UserSocial = new UserSocial();
            $UserSocial->user_id = $User->id;
            $UserSocial->social_id = $input['twitter_id'];
            $UserSocial->email = $input['email'];
            $UserSocial->platform = "facebook";
            $UserSocial->save();

            $response['status'] = "success";
            $response['user_info']['user_id'] = $User->id;
            $response['user_info']['profile_picture'] = $UserProfile->profile_picture;
            $response['user_info']['email'] = $User->email;
            $response['user_info']['username'] = $User->username;
            $response['user_info']['login_type'] = $UserSocial->platform;
            $response['user_info']['social_id'] = $UserSocial->social_id;
            $response['user_info']['join_date'] = date('Y-m-d',strtotime($User->created_at));
            $response['user_info']['auth_key'] = $User->auth_key;
            $response['user_info']['token'] = "";
        }

        return response()->json($response);
    }
}
