<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\UserProfile;
use App\UserSocial;
use App\Broadcast;

class BroadcastController extends Controller
{
    //
    public function __construct()
    {
        auth()->setDefaultDriver('api');
        $this->middleware('auth:api', ['except' => ['uploadbroadcast', 'editBroadcast', 'deleteBroadcast']]);
    }

    function uploadbroadcast (Request $request) {
        $input = $request->all();

        $rules = array(
            'title' => 'required',
            'geo_location' => 'required',
            'user_id' => 'required',
            'stream_url' => 'required'
        );
        $messages = array(
            'title.required' => 'Title is required.',
            'geo_location.required' => 'Geo location is required.',
            'user_id.required' => 'User ID is required.',
            'stream_url.required' => 'Stream URL is required.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json($messages);
        }

        echo '<pre>';
        print_r($input);
        die;

    }

    function editBroadcast (Request $request) {
        $input = $request->all();

        echo '<pre>';
        print_r($input);
        die;

    }
    

    //params - token, user_id, stream_id, stream_url
    function deleteBroadcast (Request $request) {
        $input = $request->all();

        $rules = array(
            'token' => 'required',
            'user_id' => 'required',
            'stream_id' => 'required'
        );
        $messages = array(
            'token.required' => 'Token is required.',
            'user_id.required' => 'Useer ID is required.',
            'stream_id.required' => 'Stream ID is required.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json($messages);
        }

        if (! isset($input['stream_url']) || empty($input['stream_url'])) {
            $streamURL = Broadcast::where('id', 'like', '%'. $input['stream_id'] . '%')->first();
        }

        $streamURL = Broadcast::where('user_id', $input['user_id'])->where('id', 'like', '%'. $input['stream_id'] . '%')->delete();

        $response['status'] = "success";
        $response['response'] = "deletebroadcast";
        $response['message'] = "deleted successfully";

        return response()->json($response);

        echo '<pre>';
        print_r($input);
        die;
    }

    function getAllBroadcastsforUser (Request $request) {
        $input = $request->all();

        $rules = array(
//            'token' => 'required',
            'user_id' => 'required',
        );
        $messages = array(
//            'token.required' => 'Token is required.',
            'user_id.required' => 'User ID is required.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json($messages);
        }
        
        $allUserBroadcast = Broadcast::with('user')->where('user_id', $input['user_id'])->get();

        $response['status'] = "success";
        $response['broadcast'] = [];

        foreach ($allUserBroadcast as $key => $broadcast) {
            $profilePicToGet = $broadcast['user']->profile()->first();

            $broadcastObj['id'] = $broadcast['id'];
            $broadcastObj['geo_location'] = $broadcast['geo_location'];
            $broadcastObj['filename'] = $broadcast['filename'];
            $broadcastObj['title'] = $broadcast['title'];
            $broadcastObj['description'] = $broadcast['description'];
            $broadcastObj['is_sensitive'] = $broadcast['is_sensitive'];
            $broadcastObj['stream_url'] = $broadcast['stream_url'];
            $broadcastObj['status'] = $broadcast['status'];
            $broadcastObj['broadcast_image'] = $broadcast['broadcast_image'];
            $broadcastObj['share_url'] = $broadcast['share_url'];
            $broadcastObj['username'] = $broadcast['user']->username;
            $broadcastObj['user_id'] = $broadcast['user']->id;
            $broadcastObj['profile_picture'] = $profilePicToGet['profile_picture'];
            array_push($response['broadcast'], (object)$broadcastObj);
        }

        // remove block user need to be implemented here

        return response()->json($response);

    }
}
