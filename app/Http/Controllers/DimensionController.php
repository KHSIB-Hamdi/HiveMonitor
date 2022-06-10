<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dimension;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class DimensionController extends Controller
{
    protected $user;
    public function index(){
        $dimensions = Dimension::all();
        return response()->json($dimensions);
        
    }

    public function store(Request $request)
    {
       
        $data = $request->only('length', 'width' );
        $validator = Validator::make($data, [
            'length' => 'required',
            'width' => 'required'
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Dimension::create($request->all());
        return "dimensions created successfully";
    }
  
    public function edit(Dimension $dimension)
    {
        //
    }

    
    public function update(Request $request, Dimension $dimension)
    {
       
        $data = $request->only('length', 'width');
        $validator = Validator::make($data, [
            'length' => 'required',
            'width' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        
        $dimension = $dimension->update([
            'length' => $request->length,
            'width' => $request->width
        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'dimensions data updated successfully',
            'data' => $dimension
        ], Response::HTTP_OK);
    }

    public function destroy(Dimension $dimension)
    {
        $dimension->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'dimensions data deleted successfully'
        ], Response::HTTP_OK);
    }
}