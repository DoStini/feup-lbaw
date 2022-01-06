<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Shopper extends Model {
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'about_me',
        'phone_number',
        'nif',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The user account this shopper is associated to.
     */
    public function user() {
        return $this->hasOne(User::class, 'id', 'id');
    }

    /**
     * The addresses this shopper contains.
     */
    public function addresses() {
        return $this->belongsToMany(Address::class, 'authenticated_shopper_address', 'shopper_id', 'address_id');
    }

    /**
     * The products and their amount the user has in its cart.
     */
    public function cart() {
        return $this->belongsToMany(
            Product::class,
            'product_cart',
            'shopper_id',
            'product_id',
        )->withPivot('amount')->as('details');
    }

    /**
     * The products and their amount the user has in its cart.
     */
    public function wishlist() {
        return $this->belongsToMany(
            Product::class,
            'wishlist',
            'shopper_id',
            'product_id',
        );
    }

    public function orders() {
        return $this->hasMany(
            Order::class,
            'shopper_id',
            'id',
        );
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authenticated_shopper';
}
