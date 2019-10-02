<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastComment extends Model
{
    //

    public function broadcast()
    {
        return $this->belongsTo('App\Broadcast');
    }
}
