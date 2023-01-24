<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Auth;

class UserGroup extends Model {

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'user_group';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

}
