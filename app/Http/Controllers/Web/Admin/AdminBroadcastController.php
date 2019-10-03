<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Broadcast;
use App\ReportBroadcast;
use DB; 
use Auth;

class AdminBroadcastController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        $data = Broadcast::with('user');
        if( isset($request['search']) || isset($request['datetimes']) ) {
            if(isset($request['search']) && $request['search'] != '') {
                $data = $data->where('title', 'like', "%".$request['search']."%");
                // " AND b.title like '%".$request['search']."%' ";
            }
            if(isset($request['datetimes']) && $request['datetimes'] != '') {   
                $datetimes = explode('-', $request['datetimes']);
                $datetimes[0] = str_replace('/', '-', $datetimes[0]);
                $from = $datetimes[0];
                $datetimes[1] = str_replace('/', '-', $datetimes[1]);
                $to = $datetimes[1];
                $data = $data->whereBetween('created_at', [$from, $to]);
                // $qry .= " AND b.timestamp BETWEEN '".$datetimes[0]."' AND '".$datetimes[1]."' ";
            }
        }

        $broadcasts = $data->paginate('20');
        // dd($broadcasts);
        return view('admin.all-broadcast',compact('broadcasts'));
    } 
    public function deleteBroadcast($broadcast_id){
        $user_id = Auth::user()->id;
        $broadcast = Broadcast::find($broadcast_id);
        $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $broadcast->filename);
        if (file_exists($file_path)) {
            // unlink($file_path);

            if (is_file($file_path)) {
                exec('rm -f ' . $file_path);
            }
        }
        ReportBroadcast::where('broadcast_id',$broadcast_id)->delete();
        Broadcast::find($broadcast_id)->delete();
        return back()->with('flash_message','Broadcast Deleted Successfully ');
    }

    public function approvedbroadcast($broadcast_id){
        ReportBroadcast::where('broadcast_id',$broadcast_id)->delete();
        return back()->with('flash_message','ReportedBroadcast Approved Successfully ');
    }

    
}
