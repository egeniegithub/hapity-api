<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;
use App\UserProfile;
use App\UserSocial;

class FacebookController extends Controller
{
    //

    public function __construct()
    {
        auth()->setDefaultDriver('api');
        $this->middleware('auth:api', ['except' => ['facebook_login']]);
    }
    
    function facebook_login(Request $request) {
       
        $rules = array(
            'facebook_id' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'profile_picture' => 'required',
        );
        $messages = array(
            'facebook_id.required' => 'Facebook id is required.',
            'username.required' => 'Username already registered.',
            'email.required' => 'Email is required.',
            'profile_picture.required' => 'Profile Picture is required.',
        );

        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->messages();
            return response()->json($messages);
        }

        $input = $request->all();

        $checkUserSocialAccountExist = User::with('social')
//        ->where('social_id', '=', $input['facebook_id'])
        ->where('email', $input['email'])
        ->first();

        if(empty($checkUserSocialAccountExist)){
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
            $UserSocial->social_id = $input['facebook_id'];
            $UserSocial->email = $input['email'];
            $UserSocial->platform = "facebook";
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

        } else if(!empty($checkUserSocialAccountExist['social']->toArray()) && $checkUserSocialAccountExist['social'][0]['platform'] == "facebook"){
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
            $UserSocial->social_id = $input['facebook_id'];
            $UserSocial->email = $input['email'];
            $UserSocial->platform = "facebook";
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
        
//        if ($checkUserSocialAccountExist && $checkUserSocialAccountExist['platform'] == "facebook") {
//            // user register to facebook social account
//            $userProfileData = $checkUserSocialAccountExist->user->profile()->get()->first();
//
//            $response['status'] = "success";
//            $response['user_info']['user_id'] = $checkUserSocialAccountExist->user['id'];
//            $response['user_info']['profile_picture'] = $userProfileData['profile_picture'];
//            $response['user_info']['email'] = $checkUserSocialAccountExist->user['email'];
//            $response['user_info']['username'] = $checkUserSocialAccountExist->user['username'];
//            $response['user_info']['login_type'] = $checkUserSocialAccountExist['platform'];
//            $response['user_info']['social_id'] = $checkUserSocialAccountExist['social_id'];
//            $response['user_info']['join_date'] = date('Y-m-d',strtotime($checkUserSocialAccountExist->user['created_at']));
//            $response['user_info']['auth_key'] = $userProfileData['auth_key'];
//            $response['user_info']['token'] = "";
//        }
//        else if ($checkUserSocialAccountExist && $checkUserSocialAccountExist['platform'] != "facebook") {
//            // user not register to facebook social account
//            $userProfileData = $checkUserSocialAccountExist->user->profile()->get()->first();
//
//            $UserSocial = new UserSocial();
//            $UserSocial->user_id = $checkUserSocialAccountExist->user['id'];
//            $UserSocial->social_id = $input['facebook_id'];
//            $UserSocial->email = $input['email'];
//            $UserSocial->platform = "facebook";
//            $UserSocial->save();
//
//            $response['status'] = "success";
//            $response['user_info']['user_id'] = $checkUserSocialAccountExist->user['id'];
//            $response['user_info']['profile_picture'] = $userProfileData['profile_picture'];
//            $response['user_info']['email'] = $checkUserSocialAccountExist->user['email'];
//            $response['user_info']['username'] = $checkUserSocialAccountExist->user['username'];
//            $response['user_info']['login_type'] = $UserSocial->platform;
//            $response['user_info']['social_id'] = $UserSocial->social_id;
//            $response['user_info']['join_date'] = date('Y-m-d',strtotime($checkUserSocialAccountExist->user['created_at']));
//            $response['user_info']['auth_key'] = $userProfileData['auth_key'];
//            $response['user_info']['token'] = "";
//        }
//        else {
//            // user not register to social account
//            $User = new User();
//            $User->email = $input['email'];
//            $User->username = $input['username'];
//            $User->password = Hash::make(rand());
//            $User->save();
//
//            $UserProfile = new UserProfile();
//            $UserProfile->user_id = $User->id;
//            $UserProfile->email = $input['email'];
//            $UserProfile->auth_key = bcrypt($input['username']);
//            $UserProfile->profile_picture = $input['profile_picture'];
//            $UserProfile->save();
//
//            $UserSocial = new UserSocial();
//            $UserSocial->user_id = $User->id;
//            $UserSocial->social_id = $input['facebook_id'];
//            $UserSocial->email = $input['email'];
//            $UserSocial->platform = "facebook";
//            $UserSocial->save();
//
//            $response['status'] = "success";
//            $response['user_info']['user_id'] = $User->id;
//            $response['user_info']['profile_picture'] = $UserProfile->profile_picture;
//            $response['user_info']['email'] = $User->email;
//            $response['user_info']['username'] = $User->username;
//            $response['user_info']['login_type'] = $UserSocial->platform;
//            $response['user_info']['social_id'] = $UserSocial->social_id;
//            $response['user_info']['join_date'] = date('Y-m-d',strtotime($User->created_at));
//            $response['user_info']['auth_key'] = $User->auth_key;
//            $response['user_info']['token'] = "";
//        }

        return response()->json($response);
    }
    
}
