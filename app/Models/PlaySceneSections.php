<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlaySceneSections extends Model
{
   protected $table = "play_scene_sections";
   
   use SoftDeletes;

   
    
}
