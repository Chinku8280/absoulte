<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function __construct() 
    {
        date_default_timezone_set('Asia/Singapore');
    }

    // leave list

    public function leave_list(Request $request)
    {
        $user_id = Auth::user()->id;

        $xin_leave_applications = DB::table('xin_leave_applications')
                                    ->where('xin_leave_applications.employee_id', $user_id)
                                    ->leftJoin('xin_leave_type', 'xin_leave_applications.leave_type_id', '=', 'xin_leave_type.leave_type_id')                             
                                    ->select('xin_leave_applications.*', 'xin_leave_type.type_name')
                                    ->orderBy('xin_leave_applications.created_at', 'desc')
                                    ->get();

        if(!$xin_leave_applications->isEmpty())
        {
            foreach($xin_leave_applications as $item)
            {  
                if($item->from_date == $item->to_date)
                {
                    $item->leave_full_date = date('D, d M Y', strtotime($item->from_date));
                }
                else
                {
                    $item->leave_full_date = date('D, d M Y', strtotime($item->from_date)) . " - " . date('D, d M Y', strtotime($item->to_date));
                }

                if($item->status == 1)
                {
                    $item->status = "Pending";
                }
                else if($item->status == 2)
                {
                    $item->status = "Approved";
                }
                else if($item->status == 3)
                {
                    $item->status = "Cancelled";
                }

                $item->leave_attachment_file_path = $item->leave_attachment ? asset('/hrms/uploads/leave/'.$item->leave_attachment) : '';
            }

            return response()->json([
                'status' => true,
                'message' => 'Leave List',
                'data' => $xin_leave_applications
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => "Leave Not Found",
                'data' => []
            ]);
        }
    }

    // leave type

    public function leave_type()
    {
        $user_id = Auth::user()->id;

        $result = DB::table('xin_employee_year_leave')
                    ->join('xin_leave_type', 'xin_employee_year_leave.leave_type_id', '=', 'xin_leave_type.leave_type_id')
                    ->where('xin_employee_year_leave.employee_id', $user_id)
                    ->select('xin_leave_type.leave_type_id', 'xin_leave_type.type_name')
                    ->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Leave Type List',
            'data' => $result
        ]);               
    }

    // apply leave

    public function apply_leave(Request $request)
    {
        // return $request->all();

        $user_id = Auth::user()->id;

        try
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'leave_type_id' => 'required|exists:xin_leave_type,leave_type_id',
                    'leave_reason' => 'nullable',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                    'half_day_leave' => 'nullable',
                    'remarks' => 'nullable',
                    'leave_attachment' => 'nullable'
                ],
                [],
                [
                    'leave_type_id' => 'Leave Type',
                    'leave_reason' => 'Leave Reason',
                    'start_date' => 'Start Date',
                    'end_date' => 'End date',
                    'half_day_leave' => 'Half day leave',
                    'remarks' => 'Remarks',
                    'leave_attachment' => 'Leave Attachment'
                ]
            );

            if($validator->fails())
            {
                // $error = $validator->errors();
                // return response()->json(['status'=> false, 'message' => 'error', 'error'=>$error]);

                $error = $validator->errors()->all();

                foreach($error as $item)
                {
                    return response()->json(['status' => false, 'message' => $item]);
                }
            }
            else
            {
                // leave attactment start

                if($request->hasFile('leave_attachment'))
                {
                    $leave_attachment = $request->file('leave_attachment');
                    $ext = $leave_attachment->extension();
                    $leave_attachment_file = "leave_".rand(100000000, 99999999999).date("YmdHis").".".$ext;
                    $leave_attachment->move("hrms/uploads/leave", $leave_attachment_file);
                }
                else
                {
                    $leave_attachment_file = "";
                }

                // leave attachment end

                $xin_employees = DB::table('xin_employees')->where('user_id', $user_id)->first();

                $insert_data = [
                    'company_id' => $xin_employees->company_id ?? '',
                    'employee_id' => $user_id,
                    'department_id' => $xin_employees->department_id ?? '',
                    'leave_type_id' => $request->leave_type_id,
                    'from_date' => date('d-m-Y', strtotime($request->start_date)),
                    'to_date' => date('d-m-Y', strtotime($request->end_date)),
                    'applied_on' => date('d-m-Y H:i:s'),
                    'reason' => $request->leave_reason ?? '',         
                    'remarks' => $request->remarks ?? '',
                    'status' => 1,
                    'is_half_day' => $request->half_day_leave ?? 0,
                    'leave_attachment' => $leave_attachment_file,
                    'created_at' => Carbon::now()
                ];

                $result = DB::table('xin_leave_applications')->insert($insert_data);

                if($result)
                {      
                    return response()->json([
                        'status' => true,
                        'message' => 'Leave Applied Successfully',
                    ]);                   
                }
                else
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed',
                    ]);
                }
            }
        }
        catch (Exception $e) 
        {
            return response()->json(['status'=> false, 'message' => $e->getMessage()]);
        }
    }
}
