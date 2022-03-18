<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Play extends Model
{
   protected $table = "plays";
   
   use SoftDeletes;

   protected $appends = ['roles'];

   public function getRolesAttribute(){
      return PlayRole::all();
      return PlayRole::where("play_id", $this->id)->get();
   }
    
}
