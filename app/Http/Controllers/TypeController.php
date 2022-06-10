<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class TypeController extends Controller
{
    protected $user;
    public function index(){
        $types = Type::all();
        return response()->json($types);
        
    }

    public function store(Request $request)
    {
       
        $data = $request->only('type' );
        $validator = Validator::make($data, [
            'type' => 'required'
            
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Type::create($request->all());
        return "types created successfully";
    }
  
    public function edit(Type $type)
    {
        //
    }

    
    public function update(Request $request, Type $type)
    {
       
        $data = $request->only('type');
        $validator = Validator::make($data, [
            'type' => 'required'
            
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        
        $type= $type->update([
            'type' => $request->type
           
        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'types data updated successfully',
            'data' => $type
        ], Response::HTTP_OK);
    }

    public function destroy(Type $type)
    {
        $type->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'types data deleted successfully'
        ], Response::HTTP_OK);
    }
}