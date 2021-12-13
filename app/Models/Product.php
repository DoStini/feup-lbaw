<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'attributes',
        'stock',
        'description',
        'price',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';
}
