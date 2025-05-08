<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // clock-in

    public function clock_in(Request $request)
    {
        // return $request->all();

        $user_id = Auth::user()->id;

        $attendance_date = date('d-m-Y');
        $clock_in = date('d-m-Y H:i:s', strtotime($request->clock_in)) ?? date('d-m-Y H:i:s');
        $clock_in_ip_address = $request->clock_in_ip_address ?? $request->ip();
        $clock_in_latitude = $request->clock_in_latitude ?? '';
        $clock_in_longitude = $request->clock_in_longitude ?? '';
        $clock_in_address = $request->clock_in_address ?? '';

        $xin_attendance_time = DB::table('xin_attendance_time')
                                    ->where('employee_id', $user_id)
                                    ->where('attendance_date', $attendance_date)
                                    ->orderBy('time_attendance_id', 'desc')
                                    ->first();

        if($xin_attendance_time)
        {
            if($xin_attendance_time->clock_in_out == 1)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already Clock In'
                ]);
            }
        }

        $insert = [
            'employee_id' => $user_id,
            'attendance_date' => $attendance_date,
            'clock_in' => $clock_in,
            'clock_in_ip_address' => $clock_in_ip_address,
            'clock_in_latitude' => $clock_in_latitude,
            'clock_in_longitude' => $clock_in_longitude,
            'clock_in_address' => $clock_in_address,
            'clock_in_out' => 1,
            'attendance_status' => 'Present'
        ];

        $time_attendance_id = DB::table('xin_attendance_time')->insertGetId($insert);

        if($time_attendance_id)
        {
            $attendance_data = DB::table('xin_attendance_time')
                                    ->where('time_attendance_id', $time_attendance_id)
                                    ->first();

            $attendance_data->clock_in_time = date('h:i a', strtotime($attendance_data->clock_in));

            return response()->json([
                'status' => true,
                'message' => 'Clock-in Successfull',
                'data' => $attendance_data
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Failed'
            ]);
        }
    }

    // clock-out

    public function clock_out(Request $request)
    {
        // return $request->all();

        $user_id = Auth::user()->id;

        $time_attendance_id = $request->time_attendance_id;
        $attendance_date = date('d-m-Y');
        $clock_out = date('d-m-Y H:i:s', strtotime($request->clock_out)) ?? date('d-m-Y H:i:s');
        $clock_out_ip_address = $request->clock_out_ip_address ?? $request->ip();
        $clock_out_latitude = $request->clock_out_latitude ?? '';
        $clock_out_longitude = $request->clock_out_longitude ?? '';
        $clock_out_address = $request->clock_out_address ?? '';

        $xin_attendance_time = DB::table('xin_attendance_time')
                                    ->where('time_attendance_id', $time_attendance_id)
                                    ->first();

        if($xin_attendance_time)
        {
            if($xin_attendance_time->clock_in_out == 0)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'You have to Clock-In first'
                ]);
            }
            else
            {
                $update = [
                    'clock_out' => $clock_out,
                    'clock_out_ip_address' => $clock_out_ip_address,
                    'clock_out_latitude' => $clock_out_latitude,
                    'clock_out_longitude' => $clock_out_longitude,
                    'clock_out_address' => $clock_out_address,
                    'clock_in_out' => 0,
                    'attendance_status' => 'Present'
                ];

                $result = DB::table('xin_attendance_time')
                                ->where('time_attendance_id', $time_attendance_id)
                                ->update($update);

                if($result)
                {
                    $attendance_data = DB::table('xin_attendance_time')
                                            ->where('time_attendance_id', $time_attendance_id)
                                            ->first();

                    $attendance_data->clock_in_time = date('h:i a', strtotime($attendance_data->clock_in));
                    $attendance_data->clock_out_time = date('h:i a', strtotime($attendance_data->clock_out));

                    return response()->json([
                        'status' => true,
                        'message' => 'Clock-out Successfull',
                        'data' => $attendance_data
                    ]);
                }
                else
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed'
                    ]);
                }
            }
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'You have to Clock-In first'
            ]);
        }
    }

    // check clock in out status

    public function check_clock_in_out_status(Request $request)
    {
        // return $request->all();    

        $user_id = Auth::user()->id;

        $attendance_date = date('d-m-Y');

        $xin_attendance_time = DB::table('xin_attendance_time')
                                    ->where('employee_id', $user_id)
                                    ->where('attendance_date', $attendance_date)
                                    ->orderBy('time_attendance_id', 'desc')
                                    ->first();

        if($xin_attendance_time)
        {
            if($xin_attendance_time->clock_in_out == 1)
            {
                $is_clock_in = 1;

                $time_attendance_id = $xin_attendance_time->time_attendance_id ?? '';
            }
            else
            {
                $is_clock_in = 0;
            }
        }
        else
        {
            $is_clock_in = 0;
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'is_clock_in' => $is_clock_in,
            'time_attendance_id' => $time_attendance_id ?? '',
        ]);
    }

    // monthly attendance

    public function monthly_attendance(Request $request)
    {
        // return $request->all();

        $user_id = Auth::user()->id;

        $year = $request->year;

        $xin_employees = DB::table('xin_employees')->where('user_id', $user_id)->first();

        $result = [];

        for($m = 1; $m <= 12; $m++)
        {
            $month = str_pad($m, 2, 0, STR_PAD_LEFT);

            $daysInMonth = cal_days_in_month(0, $month, $year);

            $pcount = 0;
            $acount = 0;
            $lcount = 0;
        
            for($i = 1; $i <= $daysInMonth; $i++)
            {
                $i = str_pad($i, 2, 0, STR_PAD_LEFT);

                // get date
                $attendance_date = $i.'-'.$month.'-'.$year;
                $get_day = strtotime($attendance_date);
                $day = date('l', $get_day);
                $office_shift_id = $xin_employees->office_shift_id;
                $attendance_status = '';

                // get holiday
                $h_date_chck = DB::table('xin_holidays')
                                    ->where(function ($query) use ($attendance_date) {
                                        $query->where(function ($query1) use ($attendance_date) {
                                            $query1->where('start_date', '<=', $attendance_date);
                                            $query1->where('end_date', '>=', $attendance_date);
                                        })
                                        ->orWhere(function ($query2) use ($attendance_date) {
                                            $query2->where('start_date', $attendance_date)
                                                ->orWhere('end_date', $attendance_date);
                                        });
                                    })        
                                    ->limit(1)
                                    ->get();

                $holiday_arr = array();

                if(count($h_date_chck) == 1)
                {
                    $h_date = DB::table('xin_holidays')
                                ->where(function ($query) use ($attendance_date) {
                                    $query->where(function ($query1) use ($attendance_date) {
                                        $query1->where('start_date', '<=', $attendance_date);
                                        $query1->where('end_date', '>=', $attendance_date);
                                    })
                                    ->orWhere(function ($query2) use ($attendance_date) {
                                        $query2->where('start_date', $attendance_date)
                                            ->orWhere('end_date', $attendance_date);
                                    });
                                })        
                                ->limit(1)
                                ->get();

                    $begin = new DateTime( $h_date[0]->start_date );
                    $end = new DateTime( $h_date[0]->end_date);
                    $end = $end->modify( '+1 day' );
                
                    $interval = new DateInterval('P1D');
                    $daterange = new DatePeriod($begin, $interval ,$end);
                
                    foreach($daterange as $date)
                    {
                        $holiday_arr[] =  $date->format("d-m-Y");
                    }
                } 
                else 
                {
                    $holiday_arr[] = '99-99-99';
                }

                // get leave/employee
                $leave_date_chck = DB::table('xin_leave_applications')
                                        ->where(function ($query) use ($attendance_date) {
                                            $query->where('from_date', '<=', $attendance_date);
                                            $query->where('to_date', '>=', $attendance_date);
                                        })   
                                        ->where('employee_id', $user_id)     
                                        ->limit(1)
                                        ->get();
            
                $leave_arr = array();

                if(count($leave_date_chck) == 1)
                {
                    $leave_date = DB::table('xin_leave_applications')
                                    ->where(function ($query) use ($attendance_date) {
                                        $query->where('from_date', '<=', $attendance_date);
                                        $query->where('to_date', '>=', $attendance_date);
                                    })   
                                    ->where('employee_id', $user_id)     
                                    ->limit(1)
                                    ->get();

                    $begin1 = new DateTime( $leave_date[0]->from_date );
                    $end1 = new DateTime( $leave_date[0]->to_date);
                    $end1 = $end1->modify( '+1 day' );
                
                    $interval1 = new DateInterval('P1D');
                    $daterange1 = new DatePeriod($begin1, $interval1 ,$end1);
                
                    foreach($daterange1 as $date1)
                    {
                        $leave_arr[] =  $date1->format("d-m-Y");
                    }  
                }
                else 
                {
                    $leave_arr[] = '99-99-99';
                }

                $office_shift = DB::table('xin_office_shift')
                                    ->where('office_shift_id', $office_shift_id)
                                    ->limit(1)
                                    ->get();

                $check = DB::table('xin_attendance_time')
                            ->where('employee_id', $user_id)
                            ->where('attendance_date', $attendance_date)
                            ->limit(1)
                            ->get();

                if(count($check) > 0)
                {
                    $status = 'P';  
                    $pcount += 1;
                }
                else
                {
                    // get holiday events

                    if($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
                        $status = 'H';  
                        $pcount += 0;
                    } else if($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
                        $status = 'H';
                        $pcount += 0;
                    } else if($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
                        $status = 'H';
                        $pcount += 0;
                    } else if($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
                        $status = 'H';
                        $pcount += 0;
                    } else if($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
                        $status = 'H';
                        $pcount += 0;
                    } else if($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
                        $status = 'H';
                        $pcount += 0;
                    } else if($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
                        $status = 'H';
                        $pcount += 0;
                    } else if(in_array($attendance_date,$holiday_arr)) { // holiday
                        $status = 'H';
                        $pcount += 0;
                    } else if(in_array($attendance_date,$leave_arr)) { // on leave
                        $status = 'L';
                        $pcount += 0;
                        $lcount += 1;
                    } else if(count($check) > 0){
                        $pcount += 1;
                    } else {
                        $status = 'A';
                        $pcount += 0;

                        $iattendance_date = strtotime($attendance_date);
                        $icurrent_date = strtotime(date('Y-m-d'));
                        if($iattendance_date <= $icurrent_date){
                            $acount += 1;
                        } else {
                            $acount += 0;
                        }
                    }
                }
            }

            $attendance_data = [
                'leave_count'=>$lcount,
                'present_count'=>$pcount,
                'total_working_days'=>$lcount+$pcount+$acount
            ];

            $month_name = date('F', strtotime($attendance_date));

            $result[$month_name] = [
                'year' => $year,
                'month_name' => $month_name,
                'month' => $month,
                'attendance_data' => $attendance_data               
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Monthly Attendance List',
            'data'   => $result
        ]);
    }

    // daily attendance

    public function daily_attendance(Request $request)
    {
        // return $request->all();

        $user_id = Auth::user()->id;

        $year = $request->year;
        $month = $request->month;

        $daysInMonth = cal_days_in_month(0, $month, $year);

        $result = [];

        for($i = 1; $i <= $daysInMonth; $i++)
        {
            $i = str_pad($i, 2, 0, STR_PAD_LEFT);

            // get date
            $attendance_date = $i.'-'.$month.'-'.$year;
        
            $xin_attendance_time = DB::table('xin_attendance_time')
                                        ->where('employee_id', $user_id)
                                        ->where('attendance_date', $attendance_date)
                                        ->groupBy('attendance_date')
                                        ->select(
                                            'time_attendance_id', 'employee_id', 'clock_in_out',
                                            DB::raw('MONTHNAME(STR_TO_DATE(attendance_date, "%d-%m-%Y")) as month, MIN(clock_in) as clock_in, MAX(clock_out) as clock_out')
                                        )
                                        ->get();   

            if(!$xin_attendance_time->isEmpty())
            {
                $clock_in = $xin_attendance_time[0]->clock_in ?? '';
                $clock_out = $xin_attendance_time[0]->clock_out ?? '';
                                                       
                $day_name = date('D', strtotime($attendance_date));
               
                $result[] = [                              
                    'day' => $i,
                    'day_name' => $day_name,
                    'attendance_date' => $attendance_date,
                    'clock_in' => $clock_in,
                    'clock_out' => $clock_out,
                    'clock_in_time' => $clock_in ? date('h:i a', strtotime($clock_in)) : '',
                    'clock_out_time' => $clock_out ? date('h:i a', strtotime($clock_out)) : '',   
                ];
            }

            $month_name = date('F', strtotime($attendance_date));

            $data = [
                'year' => $year,
                'month_name' => $month_name,
                'month' => $month,
                'attendance_data' => $result   
            ];
        }

        return response()->json([
            'status' => true,
            'message' =>"Daily Attendance List",
            'data' => $data ?? []
        ]);
        
    }

    // check in timesheet details

    public function check_in_timesheet_details(Request $request)
    {
        // return $request->all();

        $user_id = Auth::user()->id;

        $attendance_date = date('d-m-Y', strtotime($request->attendance_date));

        $xin_attendance_time = DB::table('xin_attendance_time')
                                    ->where('employee_id', $user_id)
                                    ->where('attendance_date', $attendance_date)  
                                    ->select('time_attendance_id', 'employee_id', 'attendance_date', 'clock_in')   
                                    ->orderBy('clock_in', 'asc')                             
                                    ->get(); 

        foreach($xin_attendance_time as $item)
        {
            $item->check_in_time = $item->clock_in ? date('h:i a', strtotime($item->clock_in)) : '';
        }

        $data = [
            'attendance_date' => date('d M, Y', strtotime($attendance_date)),
            'timesheet_details' => $xin_attendance_time
        ];
        
        return response()->json([
            'status' => true,
            'messgae' => 'Check-In Timesheet',
            'data' => $data
        ]);
    }

    // check out timesheet details

    public function check_out_timesheet_details(Request $request)
    {
        // return $request->all();

        $user_id = Auth::user()->id;

        $attendance_date = date('d-m-Y', strtotime($request->attendance_date));

        $xin_attendance_time = DB::table('xin_attendance_time')
                                    ->where('employee_id', $user_id)
                                    ->where('attendance_date', $attendance_date)  
                                    ->select('time_attendance_id', 'employee_id', 'attendance_date', 'clock_out')   
                                    ->orderBy('clock_out', 'asc')                             
                                    ->get(); 

        foreach($xin_attendance_time as $item)
        {
            $item->check_out_time = $item->clock_out ? date('h:i a', strtotime($item->clock_out)) : '';
        }

        $data = [
            'attendance_date' => date('d M, Y', strtotime($attendance_date)),
            'timesheet_details' => $xin_attendance_time
        ];
        
        return response()->json([
            'status' => true,
            'messgae' => 'Check-Out Timesheet',
            'data' => $data
        ]);
    }
}
