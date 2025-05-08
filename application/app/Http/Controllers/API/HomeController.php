<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // get all count
    public function get_all_count(Request $request)
    {
        $cleaner_id_arr = JobController::get_cleaner_id_array();

        $pending_job = ScheduleDetails::whereIn('tble_schedule_employee.employee_id', $cleaner_id_arr)
                                        ->where('tble_schedule_details.job_status', 0)
                                        ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                        ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id')
                                        ->get();

        $completed_job = ScheduleDetails::whereIn('tble_schedule_employee.employee_id', $cleaner_id_arr)
                                        ->where('tble_schedule_details.job_status', 2)
                                        ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                        ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id')
                                        ->get();

        $data['pending_job'] = count($pending_job);
        $data['completed_job'] = count($completed_job);
        
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ]);
    }
}
