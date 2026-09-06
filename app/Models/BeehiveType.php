<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeehiveType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','slug','height','width','length','material','description'
    ];
    public function beehives(){
        return $this->hasMany(Beehive::class);
    }
}
