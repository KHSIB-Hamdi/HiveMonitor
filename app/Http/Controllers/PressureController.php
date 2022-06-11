<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class PressureController extends Controller
{
    protected $user;
    public function index(){
        $pressures = Pressure::all();
        return response()->json($pressures);
        
    }
    public function store(Request $request)
    {
       
        $data = $request->only('pressure', 'symbol', 'beehive');
        $validator = Validator::make($data, [
            'pressure' => 'required',
            'symbol' => 'required',
            'beehive' => 'required'
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Pressure::create($request->all());
        return "pressure created successfully";
    }
}
