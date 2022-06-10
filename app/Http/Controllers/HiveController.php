<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hive;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class HiveController extends Controller
{
    protected $user;
    public function index(){
        $hives = Hive::all();
        return response()->json($hives);
        
    }
    public function store(Request $request)
    {
       
        $data = $request->only('internal_temperature','external_temperature', 'humidity','pressure', 'weight');
        $validator = Validator::make($data, [
            'internal_temperature' => 'required',
            'external_temperature' => 'required',
            'humidity' => 'required',
            'pressure' => 'required',
            'weight' => 'required'
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Hive::create($request->all());
        return "hive data created successfully";
    }

    
    public function show($id)
    {
        $hive = $this->user->hives()->find($id);
    
        if (!$hive) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, hive data not found.'
            ], 400);
        }
    
        return $hive;
    }

    public function update(Request $request, Hive $hive)
    {
       
        $data = $request->only('internal_temperature','external_temperature', 'humidity','pressure', 'weight');
        $validator = Validator::make($data, [
            'internal_temperature' => 'required',
            'external_temperature' => 'required', 
            'humidity' => 'required',
            'pressure' => 'required', 
            'weight' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        
        $hive = $hive->update([
            'internal_temperature' => $request->internal_temperature,
            'external_temperature' => $request->external_temperature,
            'humidity' => $request->humidity,
            'pressure'=> $request->pressure,
            'weight' => $request->weight,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'hive data updated successfully',
            'data' => $hive
        ], Response::HTTP_OK);
    }
    
    public function destroy(Hive $hive)
    {
        $hive->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'hive data deleted successfully'
        ], Response::HTTP_OK);
    }

}
