<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportBroadcast extends Model
{
    //
    protected $table = 'report_broadcasts';
    protected $guarded = [];
    
    public function broadcast(){
        return $this->belongsTo('App\Broadcast','broadcast_id')->with('userWithReportedUser');
    }
    
}
