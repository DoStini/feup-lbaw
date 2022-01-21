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
     * The photos this product contains.
     */
    public function photos() {
        return $this->belongsToMany(Photo::class, 'product_photo', 'product_id', 'photo_id');
    }

    /**
     * The categories this product belongs to.
     */
    public function categories() {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'product_id');
    }

    /**
     * The products and their amount the user has in its cart.
     */
    public function usersWishlisted() {
        return $this->belongsToMany(
            Shopper::class,
            'wishlist',
            'product_id',
            'shopper_id',
        );
    }

    /**
     * The products and their amount the user has in its cart.
     */
    public function usersCart() {
        return $this->belongsToMany(
            Shopper::class,
            'product_cart',
            'product_id',
            'shopper_id',
        )->withPivot('amount')->as('details');
    }

    /**
     * Serialize a product to json
     */
    public function serialize() {
        $prodJson = json_decode($this->toJson());
        $prodJson->photos = $this->photos->map(fn ($photo) => $photo->url);
        $prodJson->attributes = json_decode($prodJson->attributes);
        return $prodJson;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @return array
     */
    protected $hidden = [
        'tsvectors',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';
}
