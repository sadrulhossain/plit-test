<?php

namespace App;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Auth;

class User extends Authenticatable {

    use Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_group_id', 'name', 'email', 'phone', 'password', 'status',
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
        return $this->belongsTo('App\UserGroup', 'user_group_id');
    }
}
