<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Temperature extends Model
{
   protected $table = "temperature";
   
   // max created_at
   public function getMaxCreatedAt(){
      return $this->max('created_at');
   }

   // min created_at
   public function getMinCreatedAt(){
      return $this->min('created_at');
   }
    
}
