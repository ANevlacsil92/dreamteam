<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayTextbook extends Model
{
   protected $table = "play_textbooks";
   
   use SoftDeletes;

   
    
}
