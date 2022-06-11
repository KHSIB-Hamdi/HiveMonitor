<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiaryController extends Controller
{
    function index(){
        return view('pages.addapiary');
    }

    function add(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'nbrhives'=>'required',
            'status'=>'required'
            
        ]);

        $query = DB::table('apiaries')->insert([
            'name'=>$request->input('name'),
            'beehives'=>$request->input('nbrhives'),
            'status'=>$request->input('status'),
        ]);

        if($query){

            return back()->with('success','new apiary has been added successfully');
        }
        else{
            return back()->with('fail','something went wrong!');
        }

    }
    public function display(){

        $apiaries = DB::select('select * from apiaries');
        return view('pages.apiaries', ['apiaries'=>$apiaries]);
        
    }
}
