<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsertTask extends Controller
{
    function index(){
        return view('pages.addtask');
    }

    function add(Request $request){
        $request->validate([
            'name'=>'required',
            'todo'=>'required'
        ]);

        $query = DB::table('tasks')->insert([
            'name'=>$request->input('name'),
            'todo'=>$request->input('todo'),
        ]);

        if($query){

            return back()->with('success','new task has been added successfully');
        }
        else{
            return back()->with('fail','something went wrong!');
        }

    }
    public function display(){

        $tasks = DB::select('select * from tasks');
        return view('pages.tasks', ['tasks'=>$tasks]);
        
    }
}
