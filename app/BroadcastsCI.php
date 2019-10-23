<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastsCI extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'broadcast';
}
