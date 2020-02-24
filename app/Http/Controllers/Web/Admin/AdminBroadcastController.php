<?php

namespace App\Http\Controllers\Web\Admin;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\ReportBroadcast;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBroadcastController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $data = User::rightJoin('broadcasts', 'broadcasts.user_id', '=', 'users.id')->select('broadcasts.*', 'users.username');

        if (isset($request['search']) || isset($request['datetimes'])) {
            if (isset($request['search']) && $request['search'] != '') {
                $data = $data->where('title', 'like', "%" . $request['search'] . "%");
            }
            if (isset($request['datetimes']) && $request['datetimes'] != '') {
                $datetimes = explode('-', $request['datetimes']);
                $datetimes[0] = str_replace('/', '-', $datetimes[0]);
                $from = $datetimes[0];
                $datetimes[1] = str_replace('/', '-', $datetimes[1]);
                $to = $datetimes[1];
                $data = $data->whereBetween('broadcasts.created_at', [$from, $to]);
            }
        }

        $broadcasts = $data->orderBy('broadcasts.id', 'DESC')->paginate('20');

        $wowza_path = base_path('wowza_store') . DIRECTORY_SEPARATOR;

        foreach ($broadcasts as $key => $broadcast) {
            $ext = pathinfo($broadcast->filename, PATHINFO_EXTENSION);
            $ext = $ext == 'mp4' ? $ext : 'mp4';

            $filename = pathinfo($broadcast->filename, PATHINFO_FILENAME);

            $filename_normal = $filename . '.' . $ext;
            $filename_160p = $filename . '_160p.' . $ext;
            $filename_360p = $filename . '_360p.' . $ext;
            $filename_720p = $filename . '_720p.' . $ext;

            $filepath_normal = $wowza_path . $filename_normal;
            $filepath_160p = $wowza_path . $filename_160p;
            $filepath_360p = $wowza_path . $filename_360p;
            $filepath_720p = $wowza_path . $filename_720p;

            $file_exists_normal = file_exists($filepath_normal) ? true : false;
            $file_exists_160p = file_exists($filepath_160p) ? true : false;
            $file_exists_360p = file_exists($filepath_360p) ? true : false;
            $file_exists_720p = file_exists($filepath_720p) ? true : false;

            $broadcast['file_normal'] = $filename_normal;
            $broadcast['file_normal_exists'] = $file_exists_normal;

            $broadcast['file_160p'] = $filename_160p;
            $broadcast['file_160p_exists'] = $file_exists_160p;

            $broadcast['file_360p'] = $filename_360p;
            $broadcast['file_360p_exists'] = $file_exists_360p;

            $broadcast['file_720p'] = $filename_720p;
            $broadcast['file_720p_exists'] = $file_exists_720p;

            $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
            $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

            if ($file_exists_720p == true) {
                $stream_file = $filename_720p;
            } else if ($file_exists_360p == true) {
                $stream_file = $filename_360p;
            } else if ($file_exists_160p == true) {
                $stream_file = $filename_160p;
            } else {
                $stream_file = $filename_normal;
            }

            $broadcast['file_exists'] = $file_exists_160p || $file_exists_360p || $file_exists_720p || $file_exists_normal || $broadcast->status == 'online' ? true : false;

            $stream_url = urlencode('https://media.hapity.com/' . $vod_app . '/' . $ext . ':' . $stream_file . '/playlist.m3u8');
            if ($broadcast->status == 'online') {
                $stream_url = urlencode('rtsp://52.18.33.132:1935/' . $live_app . '/' . $filename . '/playlist.m3u8');
            }

            $broadcast['dynamic_stream_url'] = $stream_url;

            $broadcasts[$key] = $broadcast;
        }

        return view('admin.all-broadcast', compact('broadcasts'));
    }
    public function deleteBroadcast(Request $request)
    {
        try {
            $broadcast_id = $request->broadcast_id;
            $user_id = Auth::user()->id;
            $broadcast = Broadcast::findOrFail($broadcast_id);
            if (isset($broadcast) && !empty($broadcast->filename)) {
                $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $broadcast->filename);
                if (file_exists($file_path)) {
                    if (is_file($file_path)) {
                        unlink($file_path);
                        // exec('rm -f ' . $file_path);
                    }
                }
            }
            if (isset($broadcast) && !empty($broadcast->broadcast_image)) {
                $image_file_path = public_path('images' . DIRECTORY_SEPARATOR . 'broadcasts' . DIRECTORY_SEPARATOR . $broadcast->user_id . DIRECTORY_SEPARATOR . $broadcast->broadcast_image);
                if (file_exists($image_file_path)) {
                    if (is_file($image_file_path)) {
                        unlink($image_file_path);
                        // exec('rm -f ' . $file_path);
                    }
                }
            }
            Broadcast::find($broadcast_id)->delete();
            ReportBroadcast::where('broadcast_id', $broadcast_id)->delete();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return back()->with('flash_message', 'Broadcast Deleted Successfully ');
    }

}
