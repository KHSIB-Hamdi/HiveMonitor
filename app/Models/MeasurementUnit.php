<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','unit','description'
    ];
    public function measurements(){
        return $this->hasMany(Measurement::class);
    }
}
