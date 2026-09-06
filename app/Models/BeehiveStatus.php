<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeehiveStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','slug','description'
    ];
    public function beehives(){
        return $this->hasMany(Beehive::class);
    }
}
