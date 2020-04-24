<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;


    protected $table = 'users';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
    }
    public function hasRole($role_id)
    {
        return null !== $this->roles()->where('role_id', $role_id)->first();
    }

    public function profile()
    {
        return $this->hasOne('App\UserProfile', 'user_id');
    }

    public function social()
    {
        return $this->hasMany('App\UserSocial');
    }

    public function broadcasts()
    {
        return $this->hasMany('App\Broadcast', 'user_id')->orderBy('id','DESC');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Broadcast', 'broadcast_likes', 'user_id', 'broadcast_id');
    }

    public function comments()
    {
        return $this->belongsToMany('App\Broadcast', 'broadcast_comments', 'user_id', 'broadcast_id');
    }
    public function getToken(){
        return $this->hasOne('App\Token', 'user_id');
    }

    public function plugins() {
        return $this->hasMany('App\PluginId', 'user_id');
    }
    public function hasPlugin($user_id)
    {
        return null !== $this->plugins()->where('user_id', $user_id)->first();
    }

    public function reportedUser(){
        return $this->hasMany('App\ReportUser','reported_user_id');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function metaInfo(){
        return $this->hasMany(MetaInfo::class,'user_id');
    }
}
