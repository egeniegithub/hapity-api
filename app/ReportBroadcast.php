<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportBroadcast extends Model
{
    //
    protected $table = 'report_broadcasts';
    protected $guarded = [];
    
    public function broadcast(){
        return $this->belongsTo('App\Broadcast','id')->with('userWithReportedUser');
    }
    
}
