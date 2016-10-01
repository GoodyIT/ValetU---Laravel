<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
     public $timestamps = true;

      protected $dateFormat = 'mm/dd/YYYY';
}
