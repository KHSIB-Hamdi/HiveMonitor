<?php

namespace App\Http\Controllers;

use App\Models\MeasurementCategory;
use Illuminate\Http\Request;

class MeasurementCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(MeasurementCategory::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $measurementCategory = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required'
        ]);

        $existing = MeasurementCategory::where('name', $data['name'])->first();

        if (!$existing) {
            $measurementCategory = MeasurementCategory::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
            ]);
            return $measurementCategory;
        }
        return response(['error' => 1, 'message' => 'measurement category already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeasurementCategory  $measurementCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MeasurementCategory $measurementCategory)
    {
        return $this->respondWithSuccess($measurementCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeasurementCategory  $measurementCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeasurementCategory $measurementCategory)
    {
        if (!$measurementCategory) {
            return response(['error' => 1, 'message' => 'beehive status doesn\'t exist'], 404);
        }

        $measurementCategory->name = $request->name ?? $measurementCategory->name;
        $measurementCategory->slug = $request->slug ?? $measurementCategory->slug;
        $measurementCategory->description = $request->description ?? $measurementCategory->description;

        $measurementCategory->update();

        return $measurementCategory;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeasurementCategory  $measurementCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MeasurementCategory $measurementCategory)
    {
        $measurementCategory->delete();
        return response(['error' => 0, 'message' => 'measurement category has been deleted']);
    }
}
