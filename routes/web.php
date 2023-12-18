<?php

use Illuminate\Support\Facades\Route;
use App\Models\Beehives;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');


Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('analytics', function () {return view('pages.analytics');})->name('analytics');
	Route::get('apiaries', [App\Http\Controllers\ApiaryController::class, 'display'])->name('apiaries');
	Route::get('tasks', [App\Http\Controllers\TaskController::class, 'display'])->name('tasks');
	Route::get('inspections', [App\Http\Controllers\InspectionController::class, 'display'])->name('inspections');
	Route::get('addhive',[App\Http\Controllers\BeehiveController::class ,'display'])->name('addhive');
	Route::get('adddevice', function () {return view('pages.adddevice');})->name('adddevice');
	Route::get('adduser', function () {return view('pages.adduser');})->name('adduser');
	Route::get('role', function () {return view('pages.role');})->name('role');
	Route::get('addrole', function () {return view('pages.addrole');})->name('addrole');
	Route::get('addtask', function () {return view('pages.addtask');})->name('addtask');
	Route::get('addinspection', function () {return view('pages.addinspection');})->name('addinspection');
	Route::get('addapiary', function () {return view('pages.addapiary');})->name('addapiary');
	Route::get('apiarysites', function () {return view('pages.apiarysites');})->name('apiarysites');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});


Route::post('addapiary', [App\Http\Controllers\ApiaryController::class, 'add']);

Route::post('addtask', [App\Http\Controllers\TaskController::class, 'add']);

Route::post('addinspection', [App\Http\Controllers\InspectionController::class, 'add']);



