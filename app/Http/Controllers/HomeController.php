<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Weather;
use Datatables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $start = ($request->has('start') && $request->get('start') != '') ? date('Y-m-d',strtotime($request->get('start'))) : '';
        $end = ($request->has('end') && $request->get('end') != '') ? date('Y-m-d',strtotime($request->get('end'))) : '';
        if($start != '' && $end != ''){
            $weather = Weather::whereBetween('date_time',[$start,$end])->get();
        } elseif($start != ''){
            $weather = Weather::whereDate('date_time', '>=', $start)->get();
        }  elseif($end != ''){
            $weather = Weather::whereDate('date_time', '<=', $end)->get();
        } else {
            $weather = Weather::get();
        }
        if($request->ajax()){
        return Datatables::of($weather)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('home');
    }
}
