<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Weather;

class WeatherController extends Controller
{
    public function index(Request $request) 
    {
    	$start = ($request->has('start') && $request->get('start') != '') ? date('Y-m-d',strtotime($request->get('start'))) : '';
        $end = ($request->has('end') && $request->get('end') != '') ? date('Y-m-d',strtotime($request->get('end'))) : '';
        if($start != '' && $end != ''){
            $weather = Weather::whereBetween('date_time',[$start,$end])->paginate(10);
        } elseif($start != ''){
            $weather = Weather::whereDate('date_time', '>=', $start)->paginate(10);
        }  elseif($end != ''){
            $weather = Weather::whereDate('date_time', '<=', $end)->paginate(10);
        } else {
            $weather = Weather::paginate(10);
        }

        return response()->json($weather);
    }
}
