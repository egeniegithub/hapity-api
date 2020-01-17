<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AntMediaBroadcastsController extends Controller
{
    public function index(Request $request)
    {
        $view_data = [];

        $user = User::with(['profile', 'plugins'])->where('id', Auth::id())->first()->toArray();
        $view_data['user'] = $user;

        $broadcasts = Broadcast::with(['user'])->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
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
                $broadcast->filename = $request->input('stream_name') . '.mp4';
                $broadcast->video_name = $request->input('stream_name') . '.mp4';
                $broadcast->stream_url = 'http://34.255.219.25:5080/WebRTCApp/play.html?name=' . $request->input('stream_name');
                $broadcast->share_url = '';
                $broadcast->save();

                $broadcast->share_url = route('broadcasts.view', [$broadcast->id]);
                $broadcast->save();

                echo 'success';

                break;
            case 'update_broadcast':
                $broadcast_id = $request->input('broadcast_id');
                $update_as = $request->input('update_as');

                $broadcast = Broadcast::where('id', $broadcast_id)->where('user_id', Auth::id())->first();
                
                $broadcast_video = $update_as == 'upload' ? $request->input('broadcast_video_name') : $request->input('stream_name') . '.mp4';

                if(!is_null($broadcast) && $broadcast->id > 0) {
                    $broadcast->user_id = Auth::id();
                    $broadcast->title = $request->input('broadcast_title');
                    $broadcast->description = $request->input('broadcast_description');
                    $broadcast->broadcast_image = $request->input('broadcast_image_name');
                    $broadcast->status = 'online';
                    $broadcast->timestamp = date('Y-m-d H:i:s');
                    $broadcast->filename = $broadcast_video;
                    $broadcast->video_name = $broadcast_video;
                    $broadcast->stream_url = 'https://stg-media.hapity.com:5443/WebRTCApp/play.html?name=' . pathinfo($broadcast_video, PATHINFO_FILENAME);
                    $broadcast->save();                    
                }

                echo 'success';

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
        $file_info = $this->handle_video_file_upload($request);

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

    private function handle_video_file_upload($request)
    {
        $to_return = [];
        if ($request->hasFile('broadcast_video')) {

            $video_file = $request->file('broadcast_video');
            $video_original_name = $video_file->getClientOriginalName();
            $ext = $video_file->getClientOriginalExtension();

            $temp_path = storage_path('temp');

            $file_name = "stream_" . time() . $ext;
            $antmedia_path = base_path('antmedia_store');

            $output_file_name = "stream_" . time() . ".mp4";

            $video_path = $video_file->move($temp_path, $file_name);

            copy($temp_path . DIRECTORY_SEPARATOR . $file_name, $antmedia_path . DIRECTORY_SEPARATOR . $output_file_name);

            ffmpeg_upload_file_path($video_path->getRealPath(), $antmedia_path . DIRECTORY_SEPARATOR . $output_file_name);

            $stream_url = '';

            $to_return = [
                'file_original_name' => $video_original_name,
                'file_name' => $output_file_name,
                'file_path' => $antmedia_path . DIRECTORY_SEPARATOR . $output_file_name,
                'file_stream_url' => $stream_url,
                'file_server' => 'https://stg-media.hapity.com:5443/',
            ];
        }

        return $to_return;
    }

}
