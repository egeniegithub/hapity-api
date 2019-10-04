<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    //
    protected $table = 'report_users';
    protected $guarded = [];

    public function user(){
        return $this->blongsTo('App\User');
    }
    public function userWithProfile(){
        return $this->belongsTo('App\User','user_id')->with('profile');
    }

}
