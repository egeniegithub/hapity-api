<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Broadcast extends Model
{
    use SoftDeletes;

    protected $table = 'broadcasts';
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function likes() {
        return $this->belongsToMany('App\User', 'broadcast_likes', 'broadcast_id', 'user_id');
    }
}
