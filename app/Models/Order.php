<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'total',
        'subtotal',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function products() {
        return $this->belongsToMany(
            Product::class,
            "order_product_amount",
            "order_id",
            "product_id"
        )->withPivot("amount","unit_price")->as("details");
    }

    public function address() {
        return $this->belongsTo(
            Address::class,
            'address_id',
            'id',
        );
    }


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';
}
