<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Weight;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class WeightController extends Controller
{
    
    public function index(){
        $weights = Weight::all();
        return response()->json($weights);
        
    }

    public function store(Request $request)
    {
        $data = $request->only('weight', 'symbol' );
        $validator = Validator::make($data, [
            'weight' => 'required',
            'symbol' => 'required'
           
        
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Weight::create($request->all());
    }

    public function show($id)
    {
        $weight = $this->user->weights()->find($id);
    
        if (!$weight) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, weight not found.'
            ], 400);
        }
    
        return $weight;
    }

    public function edit(Weight $weight)
    {
        //
    }

    public function update(Request $request, Weight $weight)
    {
       
        $data = $request->only('weight', 'symbol');
        $validator = Validator::make($data, [
            'weight' => 'required',
            'symbol' => 'required'
           
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        
        $weight = $weight->update([
            'weight' => $request->weight,
            'symbol' => $request->symbol
        ]);

       
        return response()->json([
            'success' => true,
            'message' => 'Weight data updated successfully',
            'data' => $weight
        ], Response::HTTP_OK);
    }

    public function destroy(Weight $weight)
    {
        $weight->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Weight data deleted successfully'
        ], Response::HTTP_OK);
    }
}
