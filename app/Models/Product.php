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
<<<<<<< HEAD
     * The photos this product contains.
     */
    public function photos() {
        return $this->belongsToMany(Photo::class, 'product_photo', 'product_id', 'photo_id');
    }
=======
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'tsvectors',
    ];
>>>>>>> 727650a (Add cart response to api)

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';
}
