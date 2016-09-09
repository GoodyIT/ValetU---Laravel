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

}
