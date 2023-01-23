<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductDetail extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_detail';
    public $timestamps = false;


}
