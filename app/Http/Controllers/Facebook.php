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

        $User = User::with("social")->find(1);

        if ($User['social']->isEmpty()) {
            return 'empty';
        }
        else {
            return 'fill';
        }


        return $isExist;
    }
}
