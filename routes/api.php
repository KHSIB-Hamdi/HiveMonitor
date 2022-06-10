<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\HumidityController;
use App\Http\Controllers\WeightController;
use App\Http\Controllers\SoundController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DimensionController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\HiveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::get('/temperature', [TemperatureController::class,'index']);
Route::post('/temperature', [TemperatureController::class,'store']);

Route::post('/hive', [HiveController::class,'store']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', [ApiController::class, 'getAuthenticatedUser']);
    Route::get('logout', [ApiController::class, 'logout']);
   
    Route::put('/temperature/{id}', [TemperatureController::class,'update']);
    Route::get('/humidity', [HumidityController::class,'index']);
    Route::post('/humidity', [HumidityController::class,'store']);
    Route::get('/weight', [WeightController::class,'index']);
    Route::post('/weight', [WeightController::class,'store']);
    Route::get('/sound', [SoundController::class,'index']);
    Route::post('/sound', [SoundController::class,'store']);
});    

Route::get('/site', [SiteController::class,'index']);
Route::post('/site', [SiteController::class,'store']);
Route::get('/dimension', [DimensionController::class,'index']);
Route::post('/dimension', [DimensionController::class,'store']);
Route::get('/type', [DimensionController::class,'index']);
Route::post('/type', [DimensionController::class,'store']);
Route::get('/state', [DimensionController::class,'index']);
Route::post('/state', [DimensionController::class,'store']);
