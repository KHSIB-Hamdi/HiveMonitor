<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temperature;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class TemperatureController extends Controller
{
    protected $user;
    public function index(){
        $temperatures = Temperature::all();
        return response()->json($temperatures);
        
    }

    public function store(Request $request)
    {
       
        $data = $request->only('temperature', 'symbol','beehive');
        $validator = Validator::make($data, [
            'temperature' => 'required',
            'symbol' => 'required',
            'beehive' => 'required'
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Temperature::create($request->all());
        return "temperature created successfully";
    }

    
    public function show($id)
    {
        $temperature = $this->user->temperatures()->find($id);
    
        if (!$temperature) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, temperature not found.'
            ], 400);
        }
    
        return $temperature;
    }

   
    public function edit(Temperature $temperature)
    {
        //
    }

    
    public function update(Request $request, Temperature $temperature)
    {
       
        $data = $request->only('temperature', 'symbol');
        $validator = Validator::make($data, [
            'temperature' => 'required',
            'symbol' => 'required'  
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        
        $temperature = $temperature->update([
            'temperature' => $request->temperature,
            'symbol' => $request->symbol

        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'Temperature data updated successfully',
            'data' => $temperature
        ], Response::HTTP_OK);
    }

    public function destroy(Temperature $temperature)
    {
        $temperature->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Temperature data deleted successfully'
        ], Response::HTTP_OK);
    }
}
