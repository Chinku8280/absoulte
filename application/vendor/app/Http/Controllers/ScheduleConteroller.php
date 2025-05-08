<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleModel;

class ScheduleConteroller extends Controller
{
    
    function index(){
        
        return view('admin.scedule.index');

    }

    function create(Request $request){

        dd($request->all());

    }
    
}
