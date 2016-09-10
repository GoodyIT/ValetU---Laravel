<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uberuser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'ubser_credential'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}
