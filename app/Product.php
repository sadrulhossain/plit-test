<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Auth;

class Product extends Model {

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'product';
    public $timestamps = true;

    protected $fillable = [
        'name', 'slug', 'quantity', 'price', 'status',
    ];

    
}
