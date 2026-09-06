<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand','model','serial_number','description','measurement_category_id','device_id'
    ];
    public function measurementCategory(){
        return $this->belongsTo(MeasurementCategory::class);
    }
    public function device(){
        return $this->belongsTo(Device::class);
    }
}
