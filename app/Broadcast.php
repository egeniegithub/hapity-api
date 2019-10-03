<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Broadcast extends Model
{
    use SoftDeletes;

    protected $table = 'broadcasts';
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function likes() {
        return $this->belongsToMany('App\User', 'broadcast_likes', 'broadcast_id', 'user_id');
    }

    public function comments() {
        return $this->belongsToMany('App\User', 'broadcast_comments', 'broadcast_id', 'user_id');
    }

    public function broadcastsComments()
    {
        return $this->hasMany('App\BroadcastComment', 'broadcast_id');
    }
    public function reportedBradcast(){
        return $this->hasMany('App\ReportBroadcast','broadcast_id')->select('broadcast_id');
    } 
}
