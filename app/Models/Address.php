<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;

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
        'zip_code_id',
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Aggregates the address and returns every related attribute
     */
    public function aggregate() {
        $address = [];
        $address["street"]   = $this->street;
        $address["door"]     = $this->door;
        $address["county"]   = $this->zip_code->county->name;
        $address["district"] = $this->zip_code->district->name;
        $address["zip_code"] = $this->zip_code->zip_code;
        $address["zip_code_id"] = $this->zip_code->id;
        $address["id"] = $this->id;
        $address["name"] = $this->name;
        return $address;
    }

    public function zip_code() {
        return $this->belongsTo(ZipCode::class, 'zip_code_id', 'id');
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';
}
