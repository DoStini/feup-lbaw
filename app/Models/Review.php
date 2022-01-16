<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    use HasFactory;
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stars',
        'text',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function photos() {
        return $this->belongsToMany(Photo::class, 'review_photo', 'review_id', 'photo_id');
    }

    public function voters() {
        return $this->belongsToMany(Shopper::class, 'review_vote', 'review_id', 'voter_id')->withPivot('vote')->as('details');
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'review';
}
