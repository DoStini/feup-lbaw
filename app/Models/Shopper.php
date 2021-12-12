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
     * The user this shopper belongs to.
     */
    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'id');
    }

    // Not working ?
    public function adresses() {
        return $this->belongsToMany('App\Models\Address', 'authenticated_shopper_address', 'shopper_id', 'address_id');
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authenticated_shopper';
}
