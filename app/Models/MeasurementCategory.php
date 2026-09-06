<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','slug','description'
    ];
    public function measurements(){
        return $this->hasMany(Measurement::class);
    }
}
