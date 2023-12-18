<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index()
    {
        return $this->respondWithSuccess(Device::all());
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
            'brand' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
            'description' => 'required',
            'beehive_id' => 'required',
        ]);

        $existing = Device::where('serial_number', $data['serial_number'])->first();

        if (!$existing) {
            $device = Device::create([
                'brand' => $data['brand'],
                'model' => $data['model'],  
                'serial_number' => $data['serial_number'],
                'description' => $data['description'],
                'beehive_id' => $data['beehive_id'],
            ]);
            return $device;
        }
        return response(['error' => 1, 'message' => 'device already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        return $this->respondWithSuccess($device);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Device $device)
    {
        if (!$device) {
            return response(['error' => 1, 'message' => 'device doesn\'t exist'], 404);
        }

        $device->brand = $request->brand ?? $device->brand;
        $device->model = $request->model ?? $device->model;
        $device->serial_number = $request->serial_number ?? $device->serial_number;
        $device->description = $request->description ?? $device->description;
        $device->beehive_id = $request->beehive_id ?? $device->beehive_id;

        $device->update();

        return $device;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return response(['error' => 0, 'message' => 'device has been deleted']);
    }

    function display(){
        return view('pages.adddevice');
    }

    function add(Request $request){
        $request->validate([
            'brand' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
            'description' => 'required',
            'beehive_id' => 'required',
        ]);

        $query = DB::table('beehives')->insert([
            'brand'=>$request->input('brand'),
            'model'=>$request->input('model'),
            'serial_number'=>$request->input('serial_number'),
            'description'=>$request->input('description'),
            'beehive_id'=>$request->input('beehive'),
        ]);

        if($query){

            return back()->with('success','new device has been added successfully');
        }
        else{
            return back()->with('fail','something went wrong!');
        }

    }
}
