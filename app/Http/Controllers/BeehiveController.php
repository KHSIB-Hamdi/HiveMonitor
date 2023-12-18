<?php

namespace App\Http\Controllers;

use App\Models\Beehive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeehiveController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(Beehive::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required',
            'site_id' => 'required',
            'beehive_type_id' => 'required',
            'apiary' => 'required',
            'beehive_status_id' => 'required',
            'beehive_levels' => 'required',
            'beehive_frames' => 'required'
        ]);

        $existing = Beehive::where('identifier', $data['identifier'])->first();

        if (!$existing) {
            $beehive = Beehive::create([
                'identifier' => $data['identifier'],
                'site_id' => $data['site_id'],
                'beehive_type_id' => $data['beehive_type_id'],
                'apiary' => $data['apiary'],
                'beehive_status_id' => $data['beehive_status_id'],
                'beehive_levels' => $data['beehive_levels'],
                'beehive_frames' => $data['beehive_frames']    
            ]);
            return $beehive;
        }
        return response(['error' => 1, 'message' => 'beehive already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Beehive  $beehive
     * @return \Illuminate\Http\Response
     */
    public function show(Beehive $beehive)
    {
        return $this->respondWithSuccess($beehive);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Beehive  $beehive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Beehive $beehive)
    {
        if (!$beehive) {
            return response(['error' => 1, 'message' => 'beehive doesn\'t exist'], 404);
        }

        $beehive->identifier = $request->identifier ?? $beehive->identifier;
        $beehive->site_id = $request->site_id ?? $beehive->site_id;
        $beehive->beehive_type_id = $request->beehive_type_id ?? $beehive->beehive_type_id;
        $beehive->apiary = $request->apiary ?? $beehive->apiary;
        $beehive->beehive_status_id = $request->beehive_status_id ?? $beehive->beehive_status_id;
        $beehive->beehive_levels = $request->beehive_levels ?? $beehive->beehive_levels;
        $beehive->beehive_frames = $request->beehive_frames ?? $beehive->beehive_frames;

        $beehive->update();

        return $beehive;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Beehive  $beehive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Beehive $beehive)
    {
        $beehive->delete();
        return response(['error' => 0, 'message' => 'beehive has been deleted']);
    }

    
    public function display(){

        $beehives = DB::select('select * from beehives');
        return view('pages.beehives', ['beehives'=>$beehives]);
        
    }

    function add(Request $request){
        $request->validate([
            'identifier' => 'required',
            'site_id' => 'required',
            'beehive_type_id' => 'required',
            'apiary' => 'required',
            'beehive_status_id' => 'required',
            'beehive_levels' => 'required',
            'beehive_frames' => 'required'
        ]);

        $query = DB::table('beehives')->insert([
            'identifier'=>$request->input('identifier'),
            'site_id'=>$request->input('site'),
            'beehive_type_id'=>$request->input('beehive_type'),
            'apiary'=>$request->input('apiary'),
            'beehive_status_id'=>$request->input('beehive_status'),
            'beehive_levels'=>$request->input('beehive_levels'),
            'beehive_frames'=>$request->input('beehive_frames'),
        ]);

        if($query){

            return back()->with('success','new beehive has been added successfully');
        }
        else{
            return back()->with('fail','something went wrong!');
        }

    }
}
