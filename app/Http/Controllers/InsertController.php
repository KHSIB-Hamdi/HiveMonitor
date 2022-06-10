<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsertController extends Controller
{
    function index(){
        return view('pages.addhive');
    }

    function add(Request $request){
        $request->validate([
            'name'=>'required',
            'type'=>'required',
            'apiary'=>'required',
            'status'=>'required'
        ]);

        $query = DB::table('beehives')->insert([
            'name'=>$request->input('name'),
            'type'=>$request->input('type'),
            'apiary'=>$request->input('apiary'),
            'status'=>$request->input('status'),
        ]);

        if($query){

            return back()->with('success','new beehive has been added successfully');
        }
        else{
            return back()->with('fail','something went wrong!');
        }

    }
}
