<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaInfo extends Model
{
    
    protected $table = 'meta_infos';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function broadcast(){
        return $this->belongsTo(Broadcast::class,'broadcast_id');
    }

}
