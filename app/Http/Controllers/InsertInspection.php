<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspections;
use Illuminate\Support\Facades\DB;

class InsertInspection extends Controller
{
    function index(){
        return view('pages.addinspection');
    }

    function add(Request $request){
        $request->validate([
            'frames'=>'required',
            'population'=>'required',
            'honey'=>'required',
            'egg'=>'required',
            'varroa'=>'required',
            'queen'=>'required'
        ]);

        $query = DB::table('inspections')->insert([
            'frames'=>$request->input('frames'),
            'population'=>$request->input('population'),
            'honey'=>$request->input('honey'),
            'egg'=>$request->input('egg'),
            'varroa'=>$request->input('varroa'),
            'queen'=>$request->input('queen'),
        ]);

        if($query){

            return back()->with('success','new inspection has been added successfully');
        }
        else{
            return back()->with('fail','something went wrong!');
        }

        
    }

    public function formview(){

        return view('pages.inspections');

    }

    public function display(){

        $inspection = DB::select('select * from inspections');
        return view('pages.inspections', ['inspection'=>$inspection]);
        
    }
 
}
