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
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];


    /**
     * County associated with this zip code
     */
    public function county() {
        return this->belongsTo('App\Models\County');
    }

    /**
     * District associated with this zip code
     */
    public function district() {
        return this->county()->district();
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'district';
}
