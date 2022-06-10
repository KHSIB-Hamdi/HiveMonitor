<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = User::all();
        $tasks = DB::select('select * from tasks');
       
        return view('dashboard', compact('data','tasks'));
    }
    public function viewUserSave(Request $request){

        $this->validate($request,[
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255',
        ]);
 
        try{
 
         $name = $request->$name;
         $email = $request->$email;
 
         $user = new User();
         $user->name = $name;
         $user->email = $name;
         $user->save();
         return redirect()->back()->with('insert', 'user has been inserted successfully');
        }
 
        catch(\Exception $e){
         return redirect()->back()->with('error', 'user insert has failed!');
 
        }
 
    }
    public function display(){

        
        
    }
}
