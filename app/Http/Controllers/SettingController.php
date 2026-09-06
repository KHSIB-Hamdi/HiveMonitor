<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(Setting::all());
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
            'key' => 'required',
            'value' => 'required'
        ]);

        $existing = Setting::where('key', $data['key'])->first();

        if (!$existing) {
            $setting = Setting::create([
                'key' => $data['key'],
                'value' => $data['value']
            ]);
            return $setting;
        }

        return response(['error' => 1, 'message' => 'setting already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        return $setting;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        if (!$setting) {
            return response(['error' => 1, 'message' => 'setting doesn\'t exist'], 404);
        }

        $setting->key = $request->key ?? $setting->key;
        $setting->value = $request->value ?? $setting->value;
        $setting->update();
        return $setting;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();
        return response(['error' => 0, 'message' => 'setting has been deleted']);
    }
}
