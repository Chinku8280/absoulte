<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    // store

    public static function store($module = "", $activity = "", $ref = "", $created_by = "", $created_by_name = "", $created_by_type = "user")
    {
        DB::table('log_details')->insert([
            'module' => $module,
            'activity' => $activity,
            'ref_no' => $ref,
            'created_by' => $created_by_type == "user" ? Auth::user()->id : $created_by,
            'created_by_name' => $created_by_type == "user" ? Auth::user()->first_name . " " . Auth::user()->last_name : $created_by_name,
            'created_by_type' => $created_by_type,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
