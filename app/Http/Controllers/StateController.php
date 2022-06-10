<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class StateController extends Controller
{
    protected $user;
    public function index(){
        $states = State::all();
        return response()->json($states);
        
    }

    public function store(Request $request)
    {
       
        $data = $request->only('state' );
        $validator = Validator::make($data, [
            'state' => 'required'
            
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        State::create($request->all());
        return "states created successfully";
    }
  
    public function edit(State $state)
    {
        //
    }

    
    public function update(Request $request, State $state)
    {
       
        $data = $request->only('state');
        $validator = Validator::make($data, [
            'state' => 'required'
            
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        
        $state= $state->update([
            'state' => $request->state
           
        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'states data updated successfully',
            'data' => $state
        ], Response::HTTP_OK);
    }

    public function destroy(State $state)
    {
        $state->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'states data deleted successfully'
        ], Response::HTTP_OK);
    }
}
