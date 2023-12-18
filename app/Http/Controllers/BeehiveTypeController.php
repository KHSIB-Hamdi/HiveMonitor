<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeehiveType;

class BeehiveTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(BeehiveType::all());
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
            'height' => 'required',
            'width' => 'required',
            'length' => 'required',
            'material' => 'required',
            'description' => 'required'
        ]);

        $existing = BeehiveType::where('name', $data['name'])->first();

        if (!$existing) {
            $beehiveType = BeehiveType::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'height' => $data['height'],
                'width' => $data['width'],
                'length' => $data['length'],
                'material' => $data['material'],
                'description' => $data['description'],
            ]);
            return $beehiveType;
        }
        return response(['error' => 1, 'message' => 'beehive type already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BeehiveType  $beehiveType
     * @return \Illuminate\Http\Response
     */
    public function show(BeehiveType $beehiveType)
    {
        return $this->respondWithSuccess($beehiveType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BeehiveType  $beehiveType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BeehiveType $beehiveType)
    {
        if (!$beehiveType) {
            return response(['error' => 1, 'message' => 'beehive type doesn\'t exist'], 404);
        }

        $beehiveType->name = $request->name ?? $beehiveType->name;
        $beehiveType->slug = $request->slug ?? $beehiveType->slug;
        $beehiveType->height = $request->height ?? $beehiveType->height;
        $beehiveType->width = $request->width ?? $beehiveType->width;
        $beehiveType->length = $request->length ?? $beehiveType->length;
        $beehiveType->material = $request->material ?? $beehiveType->material;
        $beehiveType->description = $request->description ?? $beehiveType->description;

        $beehiveType->update();

        return $beehiveType;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BeehiveType  $beehiveType
     * @return \Illuminate\Http\Response
     */
    public function destroy(BeehiveType $beehiveType)
    {
        $beehiveType->delete();
        return response(['error' => 0, 'message' => 'beehive type has been deleted']);
    }
}
