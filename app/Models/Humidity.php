<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Humidity extends Model
{
    use HasFactory;
    protected $fillable = [
        'humidity', 'symbol','beehive'
    ];
    public function beehive()
    {
       return $this->belongsTo(Beehive::class);
    }
}
