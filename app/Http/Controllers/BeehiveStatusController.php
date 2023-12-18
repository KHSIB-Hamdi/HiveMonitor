<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeehiveStatus;

class BeehiveStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(BeehiveStatus::all());
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
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required'
        ]);

        $existing = BeehiveStatus::where('name', $data['name'])->first();

        if (!$existing) {
            $beehiveStatus = BeehiveStatus::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
            ]);
            return $beehiveStatus;
        }
        return response(['error' => 1, 'message' => 'beehive status already exists'], 409);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BeehiveStatus  $beehiveStatus
     * @return \Illuminate\Http\Response
     */
    public function show(BeehiveStatus $beehiveStatus)
    {
        return $this->respondWithSuccess($beehiveStatus);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BeehiveStatus  $beehiveStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BeehiveStatus $beehiveStatus)
    {
        if (!$beehiveStatus) {
            return response(['error' => 1, 'message' => 'beehive status doesn\'t exist'], 404);
        }

        $beehiveStatus->name = $request->name ?? $beehiveStatus->name;
        $beehiveStatus->slug = $request->slug ?? $beehiveStatus->slug;
        $beehiveStatus->description = $request->description ?? $beehiveStatus->description;

        $beehiveStatus->update();

        return $beehiveStatus;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BeehiveStatus  $beehiveStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(BeehiveStatus $beehiveStatus)
    {
        $beehiveStatus->delete();
        return response(['error' => 0, 'message' => 'beehive status has been deleted']);
    }
}
