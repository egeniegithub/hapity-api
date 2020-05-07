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
        $data = Broadcast::with(['user'=>function($q){
            $q->select('id','username');
        },
        'metaInfo' => function($q){
            $q->select('meta_infos.*');
        }])
        ->orderBy('created_at','desc');
        
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

        $broadcasts = $data->paginate(20);
        foreach ($broadcasts as $key => $broadcast) {
            $wowza_path = base_path("wowza_store" . DIRECTORY_SEPARATOR . $broadcast->filename);        
            if (file_exists(base_path("antmedia_store" . DIRECTORY_SEPARATOR . $broadcast->filename)) || $broadcast->status == 'online') {
                $broadcasts[$key]['file_exists'] = true;
            }else if(file_exists($wowza_path)){
                $broadcst = check_file_exist($broadcast,$wowza_path);
                $broadcasts[$key] = $broadcst;
            }else {
                $broadcasts[$key]['file_exists'] = false;
            }
        }
        // dd($broadcasts);
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
