<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $data = User::all();
        return view('users.index', compact('data'));
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
}
