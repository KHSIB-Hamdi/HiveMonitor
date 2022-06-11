<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exttemperature;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ExttemperatureController extends Controller
{
    protected $user;
    public function index(){
        $pressures = Exttemperaturee::all();
        return response()->json($pressures);
        
    }
    public function store(Request $request)
    {
       
        $data = $request->only('exttemperature', 'symbol', 'beehive');
        $validator = Validator::make($data, [
            'exttemperature' => 'required',
            'symbol' => 'required',
            'beehive' => 'required'
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Exttemperature::create($request->all());
        return "exttemperature created successfully";
    }
}
