<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Humidity;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class HumidityController extends Controller
{
    
    public function index(){
        $humidities = Humidity::all();
        return response()->json($humidities);
        
    }

    public function store(Request $request)
    {
        $data = $request->only('humidity', 'symbol' );
        $validator = Validator::make($data, [
            'humidity' => 'required',
            'symbol' => 'required'
         
        
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
      
        Humidity::create($request->all());
    }

    public function show($id)
    {
        $humidity = $this->user->humidities()->find($id);
    
        if (!$humidity) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, humidity not found.'
            ], 400);
        }
    
        return $humidity;
    }

    public function edit(Humidity $humidity)
    {
        //
    }

    public function update(Request $request, Humidity $humidity)
    {
        
        $data = $request->only('humidity', 'symbol');
        $validator = Validator::make($data, [
            'humidity' => 'required',
            'symbol' => 'required'
            
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $humidity = $humidity->update([
            'humidity' => $request->humidity,
            'symbol' => $request->symbol
            
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Humidity data updated successfully',
            'data' => $humidity
        ], Response::HTTP_OK);
    }

    public function destroy(Humidity $humidity)
    {
        $humidity->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Humidity data deleted successfully'
        ], Response::HTTP_OK);
    }
}
