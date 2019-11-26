<?php

namespace App\Http\Controllers;

use App\PluginId;
use App\Token;
use Illuminate\Http\Request;

class PluginsController extends Controller
{
    public function is_user_plugin_get(Request $request) {
        $user_id = $request->input('user_id');
        $token = $request->input('token');
        if (!$this->validate_token($token)) {
            $response = array('status' => 'failure', 'error' => 'Invalid Token');
            return response()->json($response, 200);
        }
        if ($user_id || $user_id != '0') {
            $user = PluginId::where('user_id',$user_id)->first();
            if(isset($user) && $token->count()){
                $result = $user->count();
            }else{
                $result = 0;
            }
            if ($result > 0) {
                $response = array('status' => 'is_enabled', 'message' => 'plugin id found');
                return response()->json($response, 200);
            } else {
                $response = array('status' => 'not', 'message' => 'plugin id found');
                return response()->json($response, 200);
            }
        } else {
            $response = array('status' => 'failure', 'error' => 'invalid parameters');
            return response()->json($response, 200);
        }
    }
    public function validate_token($token){
        $token = Token::where('token',$token)->first();
        if(isset($token) && $token->count()){
            $result = $token->count();
        }else{
            $result = 0;
        }
        if($result > 0){
            return true;
        }
        return false;
    }

}
