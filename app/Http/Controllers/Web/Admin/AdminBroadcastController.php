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
        // $admin_id=$this->session->userdata('admin_id');

        // if($admin_id!=''){
        //     $ans=$this->admin_model->delete_broadcast($id);
        //     if($ans>0){
        //         redirect('admin/reported_broadcasts?delete=true','refresh');
        //     }
        // }
        // else{
        //     redirect('admin/','refresh');
        // }
        // $qry1="delete from broadcast where id=".$id;
        // $qry2="delete from report_broadcast where broadcast_id=".$id;
        // $this->db->query($qry1);
        // $this->db->query($qry2);
        // $num = $this->db->affected_rows();
        // if($num>0)
        // {
        //     return $num;
        // }
        // else{
        //     return 0;
        // }

        Broadcast::find($broadcast_id)->delete();
    }

    
}
