<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'percentage',
        'minimum_cart_value',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupon';
}
