<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exttemperature extends Model
{
    use HasFactory;
    protected $fillable = [
        'exttemperature', 'symbol','beehive'
    ];
    public function beehive()
    {
       return $this->belongsTo(Beehive::class);
    }
}
