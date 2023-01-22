<?php

namespace App;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'remember_token', 'conf_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = isset(Auth::user()->id) ? Auth::user()->id : 1;
            $post->updated_by = isset(Auth::user()->id) ? Auth::user()->id : 1;
        });

        static::updating(function($post) {
            $post->updated_by = isset(Auth::user()->id) ? Auth::user()->id : 1;
        });
    }

    public function UserGroup() {
        return $this->belongsTo('App\UserGroup', 'group_id');
    }
    public function retailer() {
        return $this->hasone(Retailer::class, 'user_id','id');
    }    

}
