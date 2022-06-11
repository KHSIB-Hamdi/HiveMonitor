<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beehive extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','type','apiary','status'
    ];
    public function apiary()
    {
       return $this->belongsTo(Apiary::class);
    }
    public function temperature()
    {
       return $this->hasMany(Temperature::class);
    }
    public function exttemperature()
    {
       return $this->hasMany(Exttemperature::class);
    }
    public function humidity()
    {
       return $this->hasMany(Humidity::class);
    }
    public function pressure()
    {
       return $this->hasMany(Pressure::class);
    }
    public function weight()
    {
       return $this->hasMany(Weight::class);
    }
    public function sound()
    {
       return $this->hasMany(Sound::class);
    }
}
