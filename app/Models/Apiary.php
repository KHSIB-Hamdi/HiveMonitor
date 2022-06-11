<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apiary extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','beehives','status'
    ];
    public function beehives()
    {
        return $this->hasMany(Beehive::class);

    }
}
