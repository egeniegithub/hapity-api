<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastViewer extends Model
{
    //
    protected $table        = 'broadcast_viewers';
    protected $primaryKey   = 'bid';
    protected $guarded      = [];
}
