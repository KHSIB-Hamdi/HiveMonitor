<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspections;
use Illuminate\Support\Facades\DB;


class InspectionsController extends Controller
{
    function index(){
        return view('pages.inspections', $data);
    }

    public function viewInspectionSave(Request $request){

        $this->validate($request,[
         'frames' => 'required|string|max:255',
         'population' => 'required|string|max:255',
         'honey' => 'required|string|max:255',
         'egg' => 'required|string|max:255',
         'varroa' => 'required|string|max:255',
         'queen' => 'required|string|max:255',
        ]);
 
        try{
 
         $frames = $request->$frames;
         $population = $request->$population;
         $honey = $request->$honey;
         $egg = $request->$egg;
         $varroa = $request->$varroa;
         $queen = $request->$queen;

 
         $inspection = new Inspections();
         $inspection->frames = $frames;
         $inspection->population = $population;
         $inspection->honey = $honey;
         $inspection->egg = $egg;
         $inspection->varroa = $varroa;
         $inspection->queen = $queen;
         $inspection->save();
         return redirect()->back()->with('insert', 'inspection has been inserted successfully');
        }
 
        catch(\Exception $e){
         return redirect()->back()->with('error', 'inspection insert has failed!');
 
        }
 
    }
}
