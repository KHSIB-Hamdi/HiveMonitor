<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temperature;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index(){
    return view('pages.analytics') ;
    }

}
