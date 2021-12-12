<?php

namespace App\Models;

class County {
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street',
        'door',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function zip_code() {
        return this->hasOne('App\Models\ZipCode');
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'district';
}
