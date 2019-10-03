<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Broadcast;
use DB; 

class AdminBroadcastController extends Controller
{
    //
    public function index(Request $request){
        $data = Broadcast::with('user');
        // $broadcasts = Broadcast::paginate('20');
        // $data = DB::table('broadcasts bc')->join('users as u','u.id','bc.user_id');
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
    function deleteBroadcast($broadcast_id){
        Broadcast::find($broadcast_id)->delete();
    }

    
}
