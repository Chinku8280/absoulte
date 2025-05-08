<?php

namespace App\Http\Controllers;

use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    public function holiday_list()
    {
        $data['xin_holiday'] = DB::table('xin_holidays')->where('is_publish', 1)->get();   

        foreach($data['xin_holiday'] as $dt)
        {
            $startDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($dt->start_date)));
            $endDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($dt->end_date)));
    
            $dateRange = CarbonPeriod::create($startDate, $endDate);
   
            foreach ($dateRange as $item) 
            {
                $date = $item->format('Y-m-d');

                if(strtotime(date('Y-m-d')) <= strtotime($date))
                {
                    $data['holidays_list'][] = date("Y/m/d", strtotime($date));
                    $data['holidays_name'][] = $dt->event_name;
                }
            } 
        }

        return response()->json($data);
    }
}
