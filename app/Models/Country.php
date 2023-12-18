<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        'name','iso_code_2', 'iso_code_3', 'country_code'
    ];
    public function sites(){
        return $this->hasMany(Site::class);
    }
}
