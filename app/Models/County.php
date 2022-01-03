<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model {
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

    public function district() {
        return $this->belongsTo(District::class);
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'county';
}
