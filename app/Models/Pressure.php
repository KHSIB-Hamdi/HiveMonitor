<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pressure extends Model
{
    use HasFactory;
    protected $fillable = [
        'pressure', 'symbol','beehive'
    ];
    public function beehive()
    {
       return $this->belongsTo(Beehive::class);
    }
}