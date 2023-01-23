<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductLog extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_log';
    public $timestamps = false;


}
