<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beehive extends Model
{
   use HasFactory;
   protected $fillable = [
       'identifier','site_id','beehive_type_id','apiary','beehive_status_id','beehive_levels','beehive_frames'
   ];
   public function site(){
       return $this->belongsTo(Site::class);
   }
   public function beehiveType(){
       return $this->belongsTo(BeehiveType::class);
   }
   public function beehiveStatus(){
       return $this->belongsTo(BeehiveStatus::class);
   }
    public function apiary()
    {
       return $this->belongsTo(Apiary::class);
    }
}
