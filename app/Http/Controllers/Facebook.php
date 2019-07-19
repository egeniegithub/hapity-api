<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;

class Facebook extends Controller
{
    //
    function facebook_login(Request $request) {
       
        $rules = array(
            'facebook_id' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email',
            'profile_picture' => 'required',
        );
        $messages = array(
            'facebook_id.required' => 'Facebook id is required.',
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

        $checkUserExist = User::with("social")->find(1);
        
        if ($checkUserExist['social']->isEmpty()) {
            // user not register to facebook social account
            $user = new User();
            $user->email = $input['email'];
        }
        else {
           
        }


        return $isExist;
    }
}
