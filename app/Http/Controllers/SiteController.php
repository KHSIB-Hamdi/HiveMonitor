<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class SiteController extends Controller
{
    protected $user;
    public function index(){
        $sites = Site::all();
        return response()->json($sites);
        
    }

    public function store(Request $request)
    {
       
        $data = $request->only('longitude', 'latitude','altitude' );
        $validator = Validator::make($data, [
            'longitude' => 'required',
            'latitude' => 'required',
            'altitude' => 'required'
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Site::create($request->all());
        return "sites created successfully";
    }
  
    public function edit(Site $site)
    {
        //
    }

    
    public function update(Request $request, Site $site)
    {
       
        $data = $request->only('longitude', 'latitude','altitude');
        $validator = Validator::make($data, [
            'longitude' => 'required',
            'latitude' => 'required',
            'altitude' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        
        $site = $site->update([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'altitude' => $request->altitude
        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'sites data updated successfully',
            'data' => $site
        ], Response::HTTP_OK);
    }

    public function destroy(Site $site)
    {
        $site->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'sites data deleted successfully'
        ], Response::HTTP_OK);
    }
}