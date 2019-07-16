<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Broadcast extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
