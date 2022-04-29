<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayScenes extends Model
{
   protected $table = "play_scenes";
   
   use SoftDeletes;

   public function playTextbook(){
      return $this->hasMany(PlayTextbook::class);
   }

   public function sections(){
      return $this->hasMany(PlaySceneSections::class, "scene_id");
   }
   
    
}
