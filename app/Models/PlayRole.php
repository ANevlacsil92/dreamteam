<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayRole extends Model
{
   protected $table = "play_roles";
   
   use SoftDeletes;

   
    
}
