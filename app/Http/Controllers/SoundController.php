<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sound;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class SoundController extends Controller
{
    
    public function index(){
        $sounds = Sound::all();
        return response()->json($sounds);
        
    }

    public function store(Request $request)
    {
        $data = $request->only('sound', 'symbol', 'beehive' );
        $validator = Validator::make($data, [
            'sound' => 'required',
            'symbol' => 'required',
            'beehive' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Sound::create($request->all());
        return "audio created successfully";
    }

    public function show($id)
    {
        $sound = Sound::find($id);
    
        if (!$sound) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, sound not found.'
            ], 400);
        }
    
        return $sound;
    }

    public function edit(Sound $sound)
    {
        //
    }

  
    public function update(Request $request, Sound $sound)
    {
        
        $data = $request->only('sound', 'symbol');
        $validator = Validator::make($data, [
            'sound' => 'required',
            'symbol' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

       
        $sound = $sound->update([
            'sound' => $request->sound,
            'symbol' => $request->symbol
            
        ]);

    
        return response()->json([
            'success' => true,
            'message' => 'Sound data updated successfully',
            'data' => $sound
        ], Response::HTTP_OK);
    }

    public function destroy(Sound $sound)
    {
        $sound->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Sound data deleted successfully'
        ], Response::HTTP_OK);
    }
}

