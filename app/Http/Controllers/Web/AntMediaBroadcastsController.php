<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AntMediaBroadcastsController extends Controller
{
    public function index(Request $request)
    {
        $view_data = [];

        return view('ant_media_broadcasts.index', $view_data);
    }

    public function create(Request $request)
    {
        $view_data = [];

        return view('ant_media_broadcasts.create', $view_data);
    }

    public function edit(Request $request)
    {
        $view_data = [];

        return view('ant_media_broadcasts.edit', $view_data);
    }

    public function view(Request $request)
    {

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

        }

    }

    public function upload_image(Request $request)
    {

        $file_name = $this->handle_image_file_upload($request, Auth::id());

        echo $file_name;
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

}
