<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(Measurement::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required',
            'sensor_id' => 'required',
            'measurement_category_id' => 'required',
            'measurement_unit_id' => 'required',
            'measured_at' => 'required',
            'value' => 'required'
        ]);

        $existing = Beehive::where('device_id', $data['device_id'])->first();

        if (!$existing) {
            $measurement = Beehive::create([
                'device_id' => $data['device_id'],
                'sensor_id' => $data['sensor_id'],
                'measurement_category_id' => $data['measurement_category_id'],
                'measurement_unit_id' => $data['measurement_unit_id'],
                'measured_at' => $data['measured_at'],
                'value' => $data['value']    
            ]);
            return $measurement;
        }
        return response(['error' => 1, 'message' => 'measurement already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Measurment  $measurment
     * @return \Illuminate\Http\Response
     */
    public function show(Measurement $measurement)
    {
        return $this->respondWithSuccess($measurement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Measurment  $measurment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Measurment $measurement)
    {
        if (!$measurement) {
            return response(['error' => 1, 'message' => 'measurement doesn\'t exist'], 404);
        }

        $measurement->device_id = $request->device_id ?? $measurement->device_id;
        $measurement->sensor_id = $request->sensor_id ?? $measurement->sensor_id;
        $measurement->measurement_category_id = $request->measurement_category_id ?? $measurement->measurement_category_id;
        $measurement->measurement_unit_id = $request->measurement_unit_id ?? $measurement->measurement_unit_id;
        $measurement->measured_at = $request->measured_at ?? $measurement->measured_at;
        $measurement->value = $request->value ?? $measurement->value;

        $measurement->update();

        return $measurement;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Measurment  $measurment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Measurment $measurement)
    {
        $measurement->delete();
        return response(['error' => 0, 'message' => 'measurement has been deleted']);
    }
}
