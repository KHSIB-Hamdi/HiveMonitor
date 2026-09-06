<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand','model','serial_number','description','beehive_id'
    ];
    public function beehive(){
        return $this->belongsTo(Beehive::class);
    }
    public function sensors(){
        return $this->hasMany(Sensor::class);
    }
    public function measurements(){
        return $this->hasMany(Measurement::class);
    }
}
