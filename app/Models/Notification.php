<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class Notification extends Model {
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopper',
        'review_id',
        'order_id',
        'product_id',
        'review_vote_notif_type',
        'review_mng_notif_type',
        'account_mng_notif_type',
        'order_notif_type'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification';
}
