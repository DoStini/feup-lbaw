<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {
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
        dd($this->hasOne(ZipCode::class, 'id', 'zip_code'));
        return $this->hasOne(ZipCode::class, 'id', 'zip_code');
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';
}
