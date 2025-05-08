<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    // get_calendar_data

    public function get_calendar_data(Request $request)
    {
        $user_id = Auth::user()->id;     

        // leave

        $xin_leave_applications = DB::table('xin_leave_applications')
                                    ->where('xin_leave_applications.employee_id', $user_id)
                                    ->leftJoin('xin_leave_type', 'xin_leave_applications.leave_type_id', '=', 'xin_leave_type.leave_type_id')                             
                                    ->select('xin_leave_applications.*', 'xin_leave_type.type_name')
                                    ->orderBy('xin_leave_applications.created_at', 'desc')
                                    ->get();

        $leave_data = [];

        foreach($xin_leave_applications as $item)
        {
            if($item->from_date == $item->to_date)
            {
                $leave_data[] = [
                    'id' => $item->leave_id,
                    'date' => date('Y-m-d', strtotime($item->from_date)),
                    'event' => $item->type_name,
                    'type' => 'leave'
                ];
            }
            else
            {
                $startDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($item->from_date)));
                $endDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($item->to_date)));

                $dateRange = CarbonPeriod::create($startDate, $endDate);
   
                foreach ($dateRange as $dt) 
                {
                    $leave_data[] = [
                        'id' => $item->leave_id,
                        'date' => $dt->format('Y-m-d'),
                        'event' => $item->type_name,
                        'type' => 'leave'
                    ];                   
                } 
            }
        }

        // holiday

        $xin_holiday = DB::table('xin_holidays')->where('is_publish', 1)->orderBy('created_at', 'desc')->get();  
        
        $holiday_data = [];

        foreach($xin_holiday as $item)
        {
            if($item->start_date == $item->end_date)
            {
                $holiday_data[] = [
                    'id' => $item->holiday_id,
                    'date' => date('Y-m-d', strtotime($item->start_date)),
                    'event' => $item->event_name,
                    'type' => 'holiday'
                ];
            }
            else
            {
                $startDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($item->start_date)));
                $endDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($item->end_date)));
        
                $dateRange = CarbonPeriod::create($startDate, $endDate);
    
                foreach ($dateRange as $dt) 
                {
                    $holiday_data[] = [
                        'id' => $item->holiday_id,
                        'date' => $dt->format('Y-m-d'),
                        'event' => $item->event_name,
                        'type' => 'holiday'
                    ];
                } 
            }
        }

        $data = [
            'leave' => $leave_data,
            'holiday' => $holiday_data
        ];

        return response()->json([
            'status' => true,
            'message' => 'Calendar Data',
            'data' => $data
        ]);
    }

    // get_calendar_data_details

    public function get_calendar_data_details(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        if($type == "holiday")
        {
            $xin_holiday = DB::table('xin_holidays')->where('holiday_id', $id)->first();  

            if($xin_holiday)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Holiday details',
                    'type' => $type,
                    'data' => $xin_holiday
                ]);
            }
            else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found',
                    'type' => $type,
                ]);
            }
        }
        else if($type == "leave")
        {
            $xin_leave_applications = DB::table('xin_leave_applications')
                                        ->where('xin_leave_applications.leave_id', $id)
                                        ->leftJoin('xin_leave_type', 'xin_leave_applications.leave_type_id', '=', 'xin_leave_type.leave_type_id')                             
                                        ->select('xin_leave_applications.*', 'xin_leave_type.type_name')
                                        ->first();  

            if($xin_leave_applications)
            {
                if($xin_leave_applications->status == 1)
                {
                    $xin_leave_applications->status = "Pending";
                }
                else if($xin_leave_applications->status == 2)
                {
                    $xin_leave_applications->status = "Approved";
                }
                else if($xin_leave_applications->status == 3)
                {
                    $xin_leave_applications->status = "Cancelled";
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Leave details',
                    'type' => $type,
                    'data' => $xin_leave_applications
                ]);
            }
            else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found',
                    'type' => $type,
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'type' => $type,
            ]);
        }
    }
}
