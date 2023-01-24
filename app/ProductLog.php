<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Auth;

class ProductLog extends Model {

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'product_log';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'action', 'taken_at', 'taken_by',
    ];

}
