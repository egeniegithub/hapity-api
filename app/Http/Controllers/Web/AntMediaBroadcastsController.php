<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\Http\Helpers\PluginFunctions;
use App\MetaInfo;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AntMediaBroadcastsController extends Controller
{
    public function index(Request $request)
    {
        $view_data = [];

        $user = User::with(['profile', 'plugins'])->where('id', Auth::id())->first()->toArray();
        $view_data['user'] = $user;

        $broadcasts = Broadcast::with(['user'])->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        foreach ($broadcasts as $key => $broadcast) {
            $wowza_path = base_path("antmedia_store/wowza" . DIRECTORY_SEPARATOR . $broadcast->filename);
            if($broadcast->is_antmedia){
                if($broadcast->status == "online"){
                    $videoUrl = $broadcast->stream_url."_720p.mp4";
                    if(url_exists($videoUrl)) {
                        $broadcast->status = "offline";
                        $broadcast->save();
                    }
                }
                $broadcasts[$key]['file_exists'] = true;
            }else{
                if(file_exists($wowza_path)){
                    //$broadcst = check_file_exist($broadcast,$wowza_path);
                    $broadcasts[$key] = $broadcast;
                    $broadcasts[$key]['file_exists'] = true;
                }
            }
        }

        $view_data['broadcasts'] = $broadcasts;

        return view('ant_media_broadcasts.index', $view_data);
    }

    public function create(Request $request)
    {
        $view_data = [];

        return view('ant_media_broadcasts.create', $view_data);
    }

    public function edit(Request $request, $id)
    {
        $view_data = [];

        $broadcast = Broadcast::where('user_id', Auth::id())->where('id', $id)->first();
        $view_data['broadcast'] = $broadcast;

        return view('ant_media_broadcasts.edit', $view_data);
    }

    public function view(Request $request)
    {

    }

    public function upload(Request $request)
    {
        $view_data = [];

        return view('ant_media_broadcasts.upload', $view_data);
    }

    public function delete(Request $request)
    {
        $broadcast = Broadcast::where('id', $request->input('broadcast_id'))->where('user_id', Auth::id())->first();

        if (!is_null($broadcast) && $broadcast->id > 0) {
            $broadcast->delete();
            return back()->with('message_success', 'Broadcast Successfully Deleted!');
        }

        return back()->with('message_error', 'Broadcast Could Not Be Deleted!');

    }

    public function ajax_responder(Request $request)
    {
        $perform_action = $request->input('perform_action');

        switch ($perform_action) {
            case 'start_broadcast':
                $broadcast = new Broadcast();
                $broadcast->user_id = Auth::id();
                $broadcast->title = $request->input('broadcast_title');
                $broadcast->description = $request->input('broadcast_description');
                $broadcast->broadcast_image = $request->input('broadcast_image_name');
                $broadcast->status = 'online';
                $broadcast->timestamp = date('Y-m-d H:i:s');
                $broadcast->filename = $request->input('stream_name') . '_720p.mp4';
                $broadcast->video_name = $request->input('stream_name') . '_720p.mp4';
                $broadcast->stream_url = ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APP .'/streams/' . $request->input('stream_name');
                $broadcast->share_url = '';
                $broadcast->is_antmedia = 1;
                $broadcast->resolution = '720p';
                $broadcast_type = "Browser";
                if(!empty($request->input('broadcast_type'))){
                    $broadcast->broadcast_type = $request->input('broadcast_type');
                    if($request->input('broadcast_type') == "OBS")
                        $broadcast_type = "OBS";

                    if($request->input('stream_to_youtube') == "yes"){
                        $client = new Client();
                        $url = ANT_MEDIA_SERVER_STAGING_URL.WEBRTC_APP."/rest/v2/broadcasts/create";
                        $client = new Client([
                            'headers' => [ 'Content-Type' => 'application/json' ]
                        ]);
                        $stdClass = new \stdClass();
                        $stdClass->name = $request->input('broadcast_title');
                        $stdClass->streamId = $request->input('stream_name');
                        $response = $client->post($url,
                            [
                                'body' => json_encode($stdClass),
                                'headers' => [
                                    'Content-Type' => 'application/json',
                                ]
                            ]);
                    }
                }

                $broadcast->error_log = $request->input('error_log');
                $broadcast->save();

                $broadcast->share_url = route('broadcast.view', [$broadcast->id]);
                $broadcast->save();
                $metainfo = new MetaInfo();
                $metainfo->meta_info = '{"brand":"'.$request->input('meta_info').'","deviceType":"'.$broadcast_type.'"}';
                $metainfo->endpoint_url =  !is_null($request->fullUrl()) ? $request->fullUrl() : '';
                $metainfo->broadcast_id =  $broadcast->id;
                $metainfo->user_id =  Auth::id();
                $metainfo->time_stamp = time();
                $metainfo->save();
                if ((Auth::user()->hasPlugin(Auth::user()->id) && isset($request->post_plugin) && $request->post_plugin == 'true') && (isset($broadcast->id) && !empty($broadcast->id))) {
                    $broadcast_image = !empty($broadcast->broadcast_image) ? $broadcast->broadcast_image : '';
                    $plugin = new PluginFunctions();
                    $plugin->make_plugin_call($broadcast->id, $broadcast_image);
                }

                echo json_encode(['status' => 'success', 'broadcast_id' => $broadcast->id]);

                break;
            case 'update_broadcast':
                $broadcast_id = $request->input('broadcast_id');
                $update_as = $request->input('update_as');

                $broadcast = Broadcast::where('id', $broadcast_id)->where('user_id', Auth::id())->first();

                $broadcast_video = $update_as == 'uploaded' ? $request->input('broadcast_video_name') : $request->input('stream_name') . '.mp4';

                if (!is_null($broadcast) && $broadcast->id > 0) {
                    $broadcast->user_id = Auth::id();
                    $broadcast->title = $request->input('broadcast_title');
                    $broadcast->description = $request->input('broadcast_description');
                    $broadcast->broadcast_image = $request->input('broadcast_image_name');
                    $broadcast->status = 'offline';
                    $broadcast->timestamp = date('Y-m-d H:i:s');
                    $broadcast->filename = $broadcast_video;
                    $broadcast->video_name = $broadcast_video;
                    $broadcast->stream_url = ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APP .'/streams/' . pathinfo($broadcast_video, PATHINFO_FILENAME);
                    $broadcast->is_antmedia = 1;
                    if(!empty($request->input('broadcast_type')))
                        $broadcast->broadcast_type = $request->input('broadcast_type');
                    $broadcast->save();

                    if (Auth::user()->hasPlugin(Auth::user()->id)) {
                        $plugin = new PluginFunctions();
                        $plugin->make_plugin_call_edit($broadcast->id);
                        // $result = json_encode($response, true);
                    }

                    echo json_encode(['status' => 'success', 'broadcast_id' => $broadcast->id]);
                    exit();
                }

                echo json_encode(['status' => 'failed', 'broadcast_id' => $broadcast_id]);
                exit();
                break;

            case 'set_broadcast_as_offline':
                $broadcast_id = $request->input('broadcast_id');
                $broadcast = Broadcast::where('user_id', Auth::id())->where('id', $broadcast_id)->first();

                if (!is_null($broadcast) && $broadcast->id > 0) {
                    $broadcast->status = 'offline';
                    $broadcast->save();

                    if (Auth::user()->hasPlugin(Auth::user()->id)) {
                        $plugin = new PluginFunctions();
                        $plugin->make_plugin_call_edit($broadcast->id);
                        // $result = json_encode($response, true);
                    }
                }

                echo json_encode(['status' => 'success', 'broadcast_id' => $broadcast_id]);
                exit();
                break;
            case 'set_error_log':
                $broadcast_id = $request->input('broadcast_id');
                $broadcast = Broadcast::where('user_id', Auth::id())->where('id', $broadcast_id)->first();

                if (!is_null($broadcast) && $broadcast->id > 0) {
                    $broadcast->error_log = $request->input('error_log');
                    $broadcast->save();
                }

                echo json_encode(['status' => 'success', 'broadcast_id' => $broadcast_id]);
                exit();
                break;


            case 'upload_broadcast':
                $update_as = $request->input('update_as');

                $broadcast_video = $update_as == 'uploaded' ? $request->input('broadcast_video_name') : $request->input('stream_name') . '.mp4';

                $broadcast = new Broadcast();
                $broadcast->user_id = Auth::id();
                $broadcast->title = $request->input('broadcast_title');
                $broadcast->description = $request->input('broadcast_description');
                $broadcast->broadcast_image = $request->input('broadcast_image_name');
                $broadcast->status = 'offline';
                $broadcast->timestamp = date('Y-m-d H:i:s');
                $broadcast->filename = $broadcast_video;
                $broadcast->video_name = !empty($broadcast_video) ? $broadcast_video : '';
                $broadcast->stream_url = AWS_S3_URL . $broadcast_video;
                $broadcast->share_url = '';
                $broadcast->is_antmedia = 1;
                $broadcast->is_s3 = 1;
                $broadcast->save();

                $broadcast->share_url = route('broadcast.view', [$broadcast->id]);
                $broadcast->save();
                $metainfo = new MetaInfo();
                $metainfo->meta_info = '{"brand":"'.$request->input('meta_info').'","deviceType":"Browser"}';
                $metainfo->endpoint_url =  !is_null($request->fullUrl()) ? $request->fullUrl() : '';
                $metainfo->broadcast_id =  $broadcast->id;
                $metainfo->user_id =  Auth::id();
                $metainfo->time_stamp = time();
                $metainfo->save();
                if ((Auth::user()->hasPlugin(Auth::user()->id) && isset($request->post_plugin) && $request->post_plugin == 'true') && (isset($broadcast->id) && !empty($broadcast->id))) {
                    $plugin = new PluginFunctions();
                    $result = $plugin->make_plugin_call_upload($broadcast->id);
                    if (!empty($result)) {
                        $broadcast->share_url = $result;
                        $broadcast->save();
                    }
                }
                $yt_msg = [];
                if($request->input('stream_to_youtube') == "yes"){
                    $yt_msg = $this->uploadVideoOnYoutube($broadcast);
                }
                $antmedia_path = base_path('antmedia_store');
                unlink($antmedia_path . DIRECTORY_SEPARATOR . $broadcast->video_name);
                echo json_encode(array_merge(['status' => 'success', 'broadcast_id' => $broadcast->id],$yt_msg));
                exit();

                break;
            case 'publish_on_youtube':
                $broadcast = Broadcast::find($request->input('broadcast_id'));
                $this->createBroadcastOnYoutube($broadcast);

                break;
        }

    }

    public function upload_image(Request $request)
    {

        $file_name = $this->handle_image_file_upload($request, Auth::id());

        echo $file_name;
    }

    public function upload_video(Request $request)
    {
        $file_info = handle_video_file_upload($request);

        echo $file_info['file_name'];
    }

    private function handle_image_file_upload($request, $user_id)
    {
        $image = $request->input('broadcast_image');

        $thumbnail_image = '';
        if ($request->hasFile('broadcast_image')) {
            $file = $request->file('broadcast_image');
            $ext = $file->getClientOriginalExtension();
            $thumbnail_image = md5(time()) . '.' . $ext;
            $path = public_path('images' . DIRECTORY_SEPARATOR . 'broadcasts' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR);

            if (!is_dir($path)) {
                mkdir($path);
            }

            $file->move($path, $thumbnail_image);

            $this->fix_image_orientation($path . $thumbnail_image);

            return $thumbnail_image;
        }

        if (!empty($image) && !is_null($image)) {
            $thumbnail_image = md5(time()) . '.jpg';

            $path = public_path('images' . DIRECTORY_SEPARATOR . 'broadcasts' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR);

            if (!is_dir($path)) {
                mkdir($path);
            }

            $base_64_data = $request->input('broadcast_image');

            $base_64_data = str_replace('datagea:im/jpeg;base64,', '', $base_64_data);
            $base_64_data = str_replace('data:image/png;base64,', '', $base_64_data);

            File::put($path . $thumbnail_image, base64_decode($base_64_data));

            $this->fix_image_orientation($path . $thumbnail_image);

            return $thumbnail_image;
        }

        return $thumbnail_image;
    }

    private function fix_image_orientation($image_absolute_path)
    {
        $image = Image::make($image_absolute_path);

        $image->orientate();

        unlink($image_absolute_path);

        $image->save($image_absolute_path);
    }


    public function view_broadcast($broadcast_id){

        $broadcast = Broadcast::with(['user'])->find($broadcast_id);
        if (!is_null($broadcast)) {

            //if($broadcast->is_antmedia){
                if($broadcast->status == "online"){
                    //$video_path = base_path('antmedia_store').'/'. pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.mp4';
                    $videoUrl = $broadcast->stream_url."_720p.mp4";
                    if(url_exists($videoUrl)) {
                        $broadcast->status = "offline";
                        $broadcast->save();
                        $broadcast->stream_url = $videoUrl;
                    }
                }
                return view('ant_media_broadcasts.view-broadcast', compact('broadcast'));
            // }else{
            //     return view('view-broadcast', compact('broadcast'));
            // }

        } else {
            return back();
        }
    }

    public function createOBSKey(Request $request)
    {
        $view_data = [];

        return view('ant_media_broadcasts.create_obs_broadcast', $view_data);
    }
    public function listOBSKeys(Request $request)
    {
        $broadcasts = Broadcast::with(['user'])
            ->where('user_id', Auth::user()->id)
            ->where('broadcast_type', "OBS")
            ->where('status', "online")
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('ant_media_broadcasts.list_obs_keys', ['broadcasts' => $broadcasts]);
    }

    private function createBroadcastOnYoutube($broadcast){
        $client = new \Google_Client();
        $client->setClientId(env('OAUTH2_CLIENT_ID'));
        $client->setClientSecret(env('OAUTH2_CLIENT_SECRET'));
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.upload'
            ]);
        $token_info = (Auth::user()->profile->youtube_auth_info);
        $client->setAccessToken($token_info);
        if($client->getAccessToken()){
            $youtube_stream_info =  "";
            $youtube_stream_log =  "";
            $youtube_error_code = 200;
            try {

                $youtube = new \Google_Service_YouTube($client);

                // Define the $liveStream object, which will be uploaded as the request body.
                $liveStream = new \Google_Service_YouTube_LiveStream();

                // Add 'cdn' object to the $liveStream object.
                $cdnSettings = new \Google_Service_YouTube_CdnSettings();
                $cdnSettings->setFrameRate('60fps');
                $cdnSettings->setIngestionType('rtmp');
                $cdnSettings->setResolution('1080p');
                $liveStream->setCdn($cdnSettings);

                // Add 'contentDetails' object to the $liveStream object.
                $liveStreamContentDetails = new \Google_Service_YouTube_LiveStreamContentDetails();
                $liveStreamContentDetails->setIsReusable(true);
                $liveStream->setContentDetails($liveStreamContentDetails);

                // Add 'snippet' object to the $liveStream object.
                $liveStreamSnippet = new \Google_Service_YouTube_LiveStreamSnippet();
                $liveStreamSnippet->setTitle($broadcast->title);
                $liveStream->setSnippet($liveStreamSnippet);

                $streamsResponse = $youtube->liveStreams->insert('snippet,cdn,contentDetails,status', $liveStream);

                $broadcastSnippet = new \Google_Service_YouTube_LiveBroadcastSnippet();
                $broadcastSnippet->setTitle($broadcast->title);
                $broadcastSnippet->setDescription($broadcast->description);

                $date = date('Y-m-d');
                $time = date('H:i:s');
                $datetime = $date."T".$time."Z";

                $broadcastSnippet->setScheduledStartTime($datetime);
                //$broadcastSnippet->setScheduledEndTime('2020-10-13T13:00:00.000Z');

                // Create an object for the liveBroadcast resource's status, and set the
                // broadcast's status to "private".
                $status = new \Google_Service_YouTube_LiveBroadcastStatus();
                $status->setLifeCycleStatus('live');
                $status->setPrivacyStatus('public');

                //content details
                $broadcastContentDetails = new \Google_Service_YouTube_LiveBroadcastContentDetails();
                $broadcastContentDetails->setBoundStreamId($streamsResponse['id']);
                $broadcastContentDetails->setEnableAutoStart(true);
                $broadcastContentDetails->setEnableAutoStop(true);

                // Create the API request that inserts the liveBroadcast resource.
                $broadcastInsert = new \Google_Service_YouTube_LiveBroadcast();
                $broadcastInsert->setSnippet($broadcastSnippet);
                $broadcastInsert->setStatus($status);
                $broadcastInsert->setContentDetails($broadcastContentDetails);
                $broadcastInsert->setKind('youtube#liveBroadcast');

                // Execute the request and return an object that contains information
                // about the new broadcast.
                $broadcastsResponse = $youtube->liveBroadcasts->insert('snippet,status,contentDetails',
                    $broadcastInsert, array());

// Bind the broadcast to the live stream.
                $bindBroadcastResponse = $youtube->liveBroadcasts->bind(
                    $broadcastsResponse['id'],'id,contentDetails',
                    array(
                        'streamId' => $streamsResponse['id'],
                    ));
                $youtube_stream_info = ['stream_url' => $streamsResponse['cdn']['ingestionInfo']['ingestionAddress']."/".$streamsResponse['cdn']['ingestionInfo']['streamName'], 'liveStream' =>  $streamsResponse, 'liveBroadcast' => $broadcastsResponse, 'bindBroadcast' => $bindBroadcastResponse];

            } catch (\Google_Service_Exception $e) {
                $youtube_stream_log = $e->getMessage();
                $youtube_error_code = $e->getCode();
            } catch (\Google_Exception $e) {
                $youtube_stream_log = $e->getMessage();
                $youtube_error_code = $e->getCode();
            } catch(\Exception $e){
                $youtube_stream_log = $e->getMessage();
                $youtube_error_code = $e->getCode();
            }
            $first_time = true;
            if($youtube_stream_log && $youtube_error_code == 401 && $first_time){
                $token_info = $client->refreshToken(json_decode($token_info)->refresh_token);
                if(!empty($token_info['access_token']) && !empty($token_info['refresh_token'])){
                    Auth::user()->profile->youtube_auth_info = json_encode($token_info);
                    Auth::user()->profile->save();
                    $first_time = false;
                    return $this->createBroadcastOnYoutube($broadcast);
                }
            }
            // else if($youtube_stream_log && $youtube_error_code == 400){
            //     Auth::user()->profile->youtube_auth_info = NULL;
            //     Auth::user()->profile->save();
            // }
            if(isset($youtube_stream_info['stream_url'])){
                $rtmp_endpoint = $youtube_stream_info['stream_url'];
                $broadcast->youtube_stream_info = json_encode($youtube_stream_info);
                $client = new Client();
                $stream_key = str_replace("_720p.mp4","",$broadcast->video_name);
                $resp = $client->request('POST',ANT_MEDIA_SERVER_STAGING_URL.WEBRTC_APP."/rest/v2/broadcasts/".$stream_key."/endpoint?rtmpUrl=".$rtmp_endpoint);
                echo json_encode(["status" => "success", "msg" => "Stream is live now on youtube as well"]);
            }else if($youtube_error_code == 401){
                echo json_encode(["yt_status" => "failed", "yt_msg" => "Video could not be uploaded on youtube because your youtube access has been revoked. Please connect youtube account in setting and try again"]);
                Auth::user()->profile->youtube_auth_info = NULL;
                Auth::user()->profile->save();
            }else if(!empty(json_decode($youtube_stream_log)->error->message)){
                $broadcast->youtube_stream_log = $youtube_stream_log;
                echo json_encode( ["status" => "failed", "msg" => "Stream cannot be posted on youtube because, ".json_decode($youtube_stream_log)->error->message ]);
            }else if(!empty(json_decode($youtube_stream_log)->error_description)){
                Auth::user()->profile->youtube_auth_info = NULL;
                Auth::user()->profile->save();
                echo json_encode( ["status" => "failed", "msg" => "Stream cannot be posted on youtube because, ".json_decode($youtube_stream_log)->error_description ]);

            }else if(!empty($youtube_stream_log)){
                $broadcast->youtube_stream_log = $youtube_stream_log;
                echo json_encode( ["status" => "failed", "msg" => "Stream cannot be posted on youtube because, ".$youtube_stream_log ]);
            }
            $broadcast->save();
        }
    }

    public function uploadVideoOnYoutube($broadcast, $access_token = null, $refresh_token = null){
        $client = new \Google_Client();
        $client->setClientId(env('OAUTH2_CLIENT_ID'));
        $client->setClientSecret(env('OAUTH2_CLIENT_SECRET'));
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.upload'
            ]);
        if($access_token)
            $token_info = $access_token;
        else
            $token_info = (Auth::user()->profile->youtube_auth_info);
        $client->setAccessToken($token_info);
        if($client->getAccessToken()){
            $youtube_stream_log =  "";
            $youtube_error_code = 200;
            $youtube_response_msg =  ["yt_status" => "success", "yt_msg" => "Video could not be uploaded to your youtube channel, try again"];
            try {

                // Define service object for making API requests.
                $service = new \Google_Service_YouTube($client);

                // Define the $video object, which will be uploaded as the request body.
                $video = new \Google_Service_YouTube_Video();

                // Add 'snippet' object to the $video object.
                $videoSnippet = new \Google_Service_YouTube_VideoSnippet();
                $videoSnippet->setTitle($broadcast->title);
                $videoSnippet->setDescription($broadcast->description);
                $video->setSnippet($videoSnippet);

                // Add 'status' object to the $video object.
                $videoStatus = new \Google_Service_YouTube_VideoStatus();
                $videoStatus->setPrivacyStatus('public');
                $video->setStatus($videoStatus);

                // TODO: For this request to work, you must replace "YOUR_FILE"
                //       with a pointer to the actual file you are uploading.
                //       The maximum file size for this operation is 137438953472.
                $response = $service->videos->insert(
                    'snippet,status',
                    $video,
                    array(
                        'data' => file_get_contents(base_path('antmedia_store').'/'. pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.mp4'),
                        'mimeType' => 'application/octet-stream',
                        'uploadType' => 'multipart'
                    )
                );
                $broadcast->youtube_stream_info = json_encode($response);
                $youtube_response_msg =  ["yt_status" => "success", "yt_msg" => "Video uploaded to your youtube channel as well"];
            } catch (\Google_Service_Exception $e) {
                $youtube_stream_log = $e->getMessage();
                $youtube_error_code = $e->getCode();

            } catch (\Google_Exception $e) {
                $youtube_stream_log = $e->getMessage();
                $youtube_error_code = $e->getCode();
            } catch(\Exception $e){
                $youtube_stream_log = $e->getMessage();
                $youtube_error_code = $e->getCode();
            }
            $first_time = true;
            if($youtube_stream_log && $youtube_error_code == 401 && $first_time){
                if($refresh_token)
                    $token_info = $client->refreshToken($refresh_token);
                else if(!empty(json_decode($token_info)->refresh_token))
                    $token_info = $client->refreshToken(json_decode($token_info)->refresh_token);
                if(!empty($token_info['access_token']) && !empty($token_info['refresh_token'])){
                    Auth::user()->profile->youtube_auth_info = json_encode($token_info);
                    Auth::user()->profile->save();
                    return $this->uploadVideoOnYoutube($broadcast);
                }if($refresh_token == NULL){
                    Auth::user()->profile->youtube_auth_info = NULL;
                    Auth::user()->profile->save();
                }
                $first_time = false;

            }

            if($youtube_error_code == 401){
                $youtube_response_msg = ["yt_status" => "failed", "yt_msg" => "Video could not be uploaded on youtube because your youtube access has been revoked. Please connect youtube account in setting and try again"];
                Auth::user()->profile->youtube_auth_info = NULL;
                Auth::user()->profile->save();
            }else if(!empty($youtube_stream_log)){
                $broadcast->youtube_stream_log = $youtube_stream_log;
                $error_log = json_decode($youtube_stream_log);
                if(isset($error_log->error->message)){
                    $youtube_response_msg = ["yt_status" => "failed", "yt_msg" => "Video could not be uploaded on youtube because : ". strip_tags($error_log->error->message)];
                }else{
                    $youtube_response_msg = ["yt_status" => "failed", "yt_msg" => "Video could not be uploaded on youtube because : ".$youtube_stream_log];
                }
            }
            $broadcast->save();
            return $youtube_response_msg;
        }
    }
}
