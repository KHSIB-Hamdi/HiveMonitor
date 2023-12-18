<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;
    protected $fillable = [
        'device_id','sensor_id','measurement_category_id','measurement_unit_id','measured_at','value'
    ];
    public function device(){
        return $this->belongsTo(Device::class);
    }
    public function sensor(){
        return $this->belongsTo(Sensor::class);
    }
    public function category(){
        return $this->belongsTo(MeasurementCategory::class);
    }
    public function unit(){
        return $this->belongsTo(MeasurementUnit::class);
    }
}
