<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model {
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zip_code'
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
        return this->belongsTo(County::class);
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
