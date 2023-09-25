<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayRole extends Model
{
   protected $table = "play_roles";
   
   use SoftDeletes;

   public function actor(){
      return $this->hasOne(User::class, 'id', 'played_by');
   }

   public function play(){
      return $this->hasOne(Play::class, 'id', 'play_id');
   }
    
}
