<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function parent_category() {
        return $this->belongsTo(Category::class, 'id', 'parent_category');
    }

    public function child_categories() {
        return $this->hasMany(Category::class, 'parent_category');
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'photo';
}
