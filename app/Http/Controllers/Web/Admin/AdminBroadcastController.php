<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Broadcast;
// use App\ReportBroadcast;
use App\User;
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
        $data = User::rightJoin('broadcasts','broadcasts.user_id','=','users.id')->select('broadcasts.*','users.username');

        if( isset($request['search']) || isset($request['datetimes']) ) {
            if(isset($request['search']) && $request['search'] != '') {
                $data = $data->where('title', 'like', "%".$request['search']."%");
            }
            if(isset($request['datetimes']) && $request['datetimes'] != '') {   
                $datetimes = explode('-', $request['datetimes']);
                $datetimes[0] = str_replace('/', '-', $datetimes[0]);
                $from = $datetimes[0];
                $datetimes[1] = str_replace('/', '-', $datetimes[1]);
                $to = $datetimes[1];
                $data = $data->whereBetween('broadcasts.created_at', [$from, $to]);
            }
        }

        $broadcasts = $data->orderBy('broadcasts.id','DESC')->paginate('20');
        return view('admin.all-broadcast',compact('broadcasts'));
    } 
    public function deleteBroadcast($broadcast_id){
        $user_id = Auth::user()->id;
        $broadcast = Broadcast::find($broadcast_id);
        if(isset($broadcast) && !empty($broadcast->filename)){
            $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $broadcast->filename);
            if (file_exists($file_path)) {
                if (is_file($file_path)) {
                    exec('rm -f ' . $file_path);
                }
            }
        }

        DB::table('broadcasts')->where('id',$broadcast_id)->delete();
        DB::table('report_broadcasts')->where('broadcast_id',$broadcast_id)->delete();
        return back()->with('flash_message','Broadcast Deleted Successfully ');
    }


    
}
