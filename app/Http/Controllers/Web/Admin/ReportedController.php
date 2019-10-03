<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReportBroadcast;

class ReportedController extends Controller
{
    //
    public function reportedBroadcasts(){
        // $broadcast = ReportBroadcast::with(array('user'=>function($query){
        //     $query->select('id','username');
        // }))->get();

        $broadcast = ReportBroadcast::with('broadcast')->get()->toArray();
        dd($broadcast);
         $qry="select DISTINCT (r.broadcast_id) as bid,sid,b.status,b.stream_url,username,b.id,title,b.timestamp,b.broadcast_image from report_broadcast r,broadcast b, user u where (r.broadcast_id=b.id) and (user_id=sid) LIMIT ";
            $query = $this->db->query($qry);
            $broadcast = array();
            foreach ($query->result_array() as $row) {
                $row['list_of_reporters'] = $this->get_broadcast_reporters($row['bid']);
                $row['filename'] = $this->get_name_from_link($row['stream_url']);
                $broadcast[] = $row;
            }
            return $broadcast;
        
        function get_reported_users($data){
            $qry="select DISTINCT (r.reportee_user_id) as uid,sid,username,profile_picture,join_date from report_user r, user u where r.reportee_user_id=u.sid LIMIT ".$data['end'] . " OFFSET " .$data['start'];
            $query = $this->db->query($qry);
            $reportee = array();
            foreach ($query->result_array() as $row) {
                $row['list_of_reporters']=$this->get_user_reporters($row['uid']);
                $reportee[] = $row;
            }
            return $reportee;
        }

        
    }
    public function reportedUsers(){
        $data['reported_users']=$this->admin_model->get_reported_users($model_data);
        function get_user_reporters($uid){
            $qry="select reporter_user_id, username, profile_picture from report_user, user where reporter_user_id=sid and reportee_user_id=".$uid;
            $query = $this->db->query($qry);
            $reporters = array();
            foreach ($query->result_array() as $row) {
    
                $reporters[] = $row;
            }
            return $reporters;
        }
    }
}
