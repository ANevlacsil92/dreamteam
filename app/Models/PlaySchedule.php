<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlaySchedule extends Model
{
   protected $table = "play_schedule";
   
   use SoftDeletes;
   
}
