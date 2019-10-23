<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersCI extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'user';

    public function broadcasts() {
        return $this->hasMany('App\BroadcastsCI', 'user_id', 'sid');
    }

    public function plugins() {
        return $this->hasMany('App\PluginsCI', 'user_id', 'sid');
    }
}
