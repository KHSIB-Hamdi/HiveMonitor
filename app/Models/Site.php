<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','latitude','longitude','street','street_number','zip_code','city','country_id'
    ];
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function beehives(){
        return $this->hasMany(Beehive::class);
    }
    
}
