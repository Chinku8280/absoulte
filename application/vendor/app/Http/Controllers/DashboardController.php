<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function home() {
         $heading_name  = 'Dashboard';
        return view( 'dashboard.dashboard',compact('heading_name') );
    }

}
