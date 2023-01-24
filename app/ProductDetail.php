<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Auth;

class ProductDetail extends Model {

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'product_detail';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
    ];

}
