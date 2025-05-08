<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Crm;
use App\Models\JobDetail;
use App\Models\JobDetails;
use App\Models\Lead;
use App\Models\LeadPaymentInfo;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use App\Models\ServiceAddress;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Svg\Tag\Rect;

class JobController extends Controller
{
    public function __construct() 
    {
        date_default_timezone_set('Asia/Singapore');
    }

    public static function get_cleaner_id_array()
    {
        $user_id = Auth::user()->id;

        $cleaner_id_arr = [];

        $cleaner_id_arr[] = $user_id;

        $xin_team = DB::table('xin_team')->get();

        foreach($xin_team as $item)
        {
            $temp_emp_arr = explode(',', $item->employee_id);

            if(in_array($user_id, $temp_emp_arr))
            {
                $cleaner_id_arr[] = $item->team_id;
            }
        }       

        return $cleaner_id_arr;
    }

    public static function check_superviser($schedule_details_id)
    {
        $user_id = Auth::user()->id;

        $schedule_details = ScheduleDetails::find($schedule_details_id);

        if($schedule_details->cleaner_type == "team")
        {
            $team_id = $schedule_details->employee_id;

            $xin_team = DB::table('xin_team')->where('team_id', $team_id)->first();

            if($xin_team->superviser_employee_id == $user_id)
            {
                return true;
            }
        }
        else if($schedule_details->cleaner_type == "individual")
        {
            if($schedule_details->superviser_emp_id == $user_id)
            {
                return true;
            }
        }

        return false;
    }

    // pending job
    public function pending_job(Request $request)
    {
        $cleaner_id_arr = $this->get_cleaner_id_array();

        $schedule_details = ScheduleDetails::whereIn('tble_schedule_employee.employee_id', $cleaner_id_arr)
                                            ->where('tble_schedule_details.job_status', 0)
                                            ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                            ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id')
                                            ->get();

        if(!$schedule_details->isEmpty())
        {
            foreach($schedule_details as $item)
            {
                $ScheduleModel = ScheduleModel::find($item->tble_schedule_id ?? '');

                // service address
                $item->service_address = $ScheduleModel->address;
                $item->service_address_unit_no = $ScheduleModel->unitNo;

                // date & time
                $item->date = date('d-m-Y', strtotime($item->schedule_date));
                $item->time = date('h:i a', strtotime($item->startTime));
            }

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $schedule_details
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found',
                'data' => $schedule_details
            ]);
        }
    }

    // completed job
    public function completed_job(Request $request)
    {
        $cleaner_id_arr = $this->get_cleaner_id_array();

        $schedule_details = ScheduleDetails::whereIn('tble_schedule_employee.employee_id', $cleaner_id_arr)
                                            ->where('tble_schedule_details.job_status', 2)
                                            ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                            ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id')
                                            ->get();

        if(!$schedule_details->isEmpty())
        {
            foreach($schedule_details as $item)
            {
                $ScheduleModel = ScheduleModel::find($item->tble_schedule_id ?? '');

                // service address
                $item->service_address = $ScheduleModel->address;
                $item->service_address_unit_no = $ScheduleModel->unitNo;

                // date & time
                $item->date = date('d-m-Y', strtotime($item->schedule_date));
                $item->time = date('h:i a', strtotime($item->startTime));
            }

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $schedule_details
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found',
                'data' => $schedule_details
            ]);
        }
    }

    // weekly task
    public function weeklyTask(Request $request)
    {
        $cleaner_id_arr = $this->get_cleaner_id_array();

        if($request->filled('week_start_date') && $request->filled('week_end_date'))
        {
            $week_start_date = date('Y-m-d', strtotime($request->week_start_date));
            $week_end_date = date('Y-m-d', strtotime($request->week_end_date));
        }
        else
        {
            $now = Carbon::now();
            $week_start_date = $now->startOfWeek()->format('Y-m-d');
            $week_end_date = $now->endOfWeek()->format('Y-m-d');
        }

        $organized_jobs = [];

        $dateRange = CarbonPeriod::create($week_start_date, $week_end_date);    
   
        // $weekly_tasks = ScheduleDetails::wherein('employee_id', $cleaner_id_arr)
        //                         ->whereBetween('schedule_date', [$week_start_date, $week_end_date])                                       
        //                         ->get();

        foreach($dateRange->toArray() as $date)
        {
            $day = date('l', strtotime($date));

            // cleaner task start

            $tasks = ScheduleDetails::whereIn('tble_schedule_employee.employee_id', $cleaner_id_arr)
                                    ->whereDate('tble_schedule_employee.schedule_date', $date)   
                                    ->where('tble_schedule_details.job_status', '!=', 3)
                                    ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')                                
                                    ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id')
                                    ->get();

            foreach($tasks as $item)
            {
                $ScheduleModel = ScheduleModel::find($item->tble_schedule_id ?? '');

                $customer_id = $ScheduleModel->customer_id ?? '';

                // customer
                $customer = Crm::find($customer_id);

                $item->customer_id = $customer_id;
                $item->customer_type = $customer->customer_type ?? '';
                $item->customer_name = $customer->customer_name ?? '';
                $item->company_name = $customer->individual_company_name ?? '';
                $item->mobile_number = $customer->mobile_number ?? '';

                // service address
                $item->service_address = $ScheduleModel->address;
                $item->service_address_unit_no = $ScheduleModel->unitNo;

                // date & time
                $item->date = date('d-m-Y', strtotime($item->schedule_date));
                $item->time = date('h:i a', strtotime($item->startTime));

                // cleaning type
                if($item->customer_type == "residential_customer_type")
                {
                    $item->cleaning_type = "Residential Cleaning";
                }
                else
                {
                    $item->cleaning_type = "Commercial Cleaning";
                }              
            }

            // cleaner task end

            // drivers data start

            $driver_tasks = ScheduleDetails::whereIn('tble_schedule_details.driver_emp_id', $cleaner_id_arr)
                                ->whereDate('tble_schedule_details.delivery_date', $date)   
                                ->where('tble_schedule_details.job_status', '!=', 3)                          
                                ->select('tble_schedule_details.*')
                                ->get();

            foreach($driver_tasks as $item)
            {
                $ScheduleModel = ScheduleModel::find($item->tble_schedule_id ?? '');

                $customer_id = $ScheduleModel->customer_id ?? '';

                // customer
                $customer = Crm::find($customer_id);

                $item->customer_id = $customer_id;
                $item->customer_type = $customer->customer_type ?? '';
                $item->customer_name = $customer->customer_name ?? '';
                $item->company_name = $customer->individual_company_name ?? '';
                $item->mobile_number = $customer->mobile_number ?? '';

                // service address
                $item->service_address = $ScheduleModel->address;
                $item->service_address_unit_no = $ScheduleModel->unitNo;

                // date & time
                $item->date = date('d-m-Y', strtotime($item->delivery_date));
                $item->time = date('h:i a', strtotime($item->delivery_time));

                // cleaning type
                if($item->customer_type == "residential_customer_type")
                {
                    $item->cleaning_type = "Residential Cleaning";
                }
                else
                {
                    $item->cleaning_type = "Commercial Cleaning";
                }              
            }

            // drivers data end

            $organized_jobs[] = [
                'date' => date('d-m-Y', strtotime($date)),
                'day' => $day,
                'total_task' => count($tasks) + count($driver_tasks),
                'tasks' => $tasks,
                'driver_tasks' => $driver_tasks
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Weekly jobs retrieved successfully',
            'week_start_date' => date('d-m-Y', strtotime($week_start_date)),
            'week_end_date' => date('d-m-Y', strtotime($week_end_date)),
            'data' => $organized_jobs
        ]);
    }

    // view directions
    public function view_directions(Request $request)
    {
        $schedule_details_id = $request->schedule_details_id;      

        $schedule_details = ScheduleDetails::find($schedule_details_id);

        if($schedule_details)
        {
            $schedule = ScheduleModel::find($schedule_details->tble_schedule_id);

            // customer
            $customer = Crm::find($schedule->customer_id);

            $schedule_details->customer_type = $customer->customer_type ?? '';
            $schedule_details->customer_name = $customer->customer_name ?? '';
            $schedule_details->company_name = $customer->individual_company_name ?? '';
            $schedule_details->mobile_number = $customer->mobile_number ?? '';

            // service address
            $schedule_details->service_address = $schedule->address;
            $schedule_details->service_address_unit_no = $schedule->unitNo;

            // date & time
            $schedule_details->date = date('d M Y', strtotime($schedule_details->schedule_date));
            $schedule_details->time = date('h:i a', strtotime($schedule_details->startTime));

            // cleaning type
            if($schedule_details->customer_type == "residential_customer_type")
            {
                $schedule_details->cleaning_type = "Residential Cleaning";
            }
            else
            {
                $schedule_details->cleaning_type = "Commercial Cleaning";
            }

            // latitude & longitude
            $get_data = $this->get_latlong_api($schedule->postalCode);

            $schedule_details->latitude = $get_data->results[0]->LATITUDE;
            $schedule_details->longitude = $get_data->results[0]->LONGITUDE;

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $schedule_details
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found',
                'data' => $schedule_details
            ]);
        }
    }

    public function get_latlong_api($postalcode)
    {        
        $url = "https://www.onemap.gov.sg/api/common/elastic/search?searchVal=$postalcode&returnGeom=Y&getAddrDetails=Y&pageNum=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        $result = curl_exec($ch);  
        curl_close($ch);

        return json_decode($result);
    }

    // view job details
    public function view_job_details(Request $request)
    {
        $schedule_details_id = $request->schedule_details_id;    

        $schedule_details = ScheduleDetails::find($schedule_details_id);

        $start_task = false;

        if($schedule_details)
        {
            $start_task = $this->check_superviser($schedule_details_id);

            $schedule = ScheduleModel::find($schedule_details->tble_schedule_id);

            // customer
            $customer = Crm::find($schedule->customer_id);

            $schedule_details->customer_type = $customer->customer_type ?? '';
            $schedule_details->customer_name = $customer->customer_name ?? '';
            $schedule_details->company_name = $customer->individual_company_name ?? '';
            $schedule_details->mobile_number = $customer->mobile_number ?? '';

            // service address
            $schedule_details->service_address = $schedule->address;
            $schedule_details->service_address_unit_no = $schedule->unitNo;

            // date & time
            $schedule_details->date = date('d M Y', strtotime($schedule_details->schedule_date));
            $schedule_details->time = date('h:i a', strtotime($schedule_details->startTime));

            // cleaning type
            if($schedule_details->customer_type == "residential_customer_type")
            {
                $schedule_details->cleaning_type = "Residential Cleaning";
            }
            else
            {
                $schedule_details->cleaning_type = "Commercial Cleaning";
            }

            // balance payable
            $sales_order = SalesOrder::find($schedule->sales_order_id ?? '');
            $quotation = Quotation::find($sales_order->quotation_id ?? '');

            if($quotation)
            {
                $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $sales_order->quotation_id)->get();

                $deposit = 0;
                $balance = 0;
                foreach($lead_payment_detail as $list)
                {
                    if($list->payment_status == 1)
                    {
                        $deposit += $list->payment_amount;
                    }
                }
                $balance = $quotation->grand_total - $deposit;
            }
            else
            {
                $deposit = 0;
                $balance = 0;
            }

            // job description
            $quotation_service_details = QuotationServiceDetail::where('quotation_id', $sales_order->quotation_id)->get();

            // company
            $company = Company::find($quotation->company_id); 

            // latitude & longitude
            $get_data = $this->get_latlong_api($schedule->postalCode);

            // $data['balance_payable'] = $balance;
            $data['balance_payable'] = $schedule_details->pay_amount ?? 0;
            $data['contact_whatsapp'] = $company->contact_number ?? '';
            $data['task_details'] = $schedule_details;
            $data['job_description'] = $quotation_service_details;
            $data['latitude'] = $get_data->results[0]->LATITUDE;
            $data['longitude'] = $get_data->results[0]->LONGITUDE;

            // $data['latitude'] = "37.785834";
            // $data['longitude'] = "-122.406417";
            
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'start_task' => $start_task
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found',
                'data' => null,
                'start_task' => $start_task
            ]);
        }
    }

    // start task
    public function start_task(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'schedule_details_id' => 'required|exists:tble_schedule_details,id',
                'start_task_time' => 'required',
                'latitude' => 'required',
                'longitude' => 'required'
            ],
            [],
            [
                'schedule_details_id' => 'Schedule details id',
                'start_task_time' => 'Start Task Time',
                'latitude' => 'Latitude',
                'longitude' => 'Longitude'
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
            $schedule_details_id = $request->schedule_details_id;  
            $schedule_details = ScheduleDetails::find($schedule_details_id);

            if($schedule_details)
            {
                if($schedule_details->job_status == 0)
                {
                    if($this->check_superviser($schedule_details_id) == true)
                    {                
                        $sales_order_id = $schedule_details->sales_order_id;
                        $schedule_id = $schedule_details->tble_schedule_id; 

                        // job details start

                        $db_job_details = JobDetail::where('sales_order_id', $schedule_details->sales_order_id)
                                            ->where('schedule_date', $schedule_details->schedule_date);

                        if($db_job_details->exists())
                        {
                            $job_details = $db_job_details->first();
                        }
                        else
                        {
                            $job_details = new JobDetail();
                        }

                        $job_details->schedule_id = $schedule_id;
                        $job_details->schedule_details_id = $schedule_details_id;
                        $job_details->sales_order_id = $sales_order_id;
                        $job_details->schedule_date = $schedule_details->schedule_date;
                        $job_details->start_task_time = date('H:i:s', strtotime($request->start_task_time)) ?? date('H:i:s');
                        $job_details->latitude = $request->latitude;
                        $job_details->longitude = $request->longitude;

                        $result = $job_details->save();

                        // job details end

                        if($result)
                        {
                            $schedule_details->job_status = 1;
                            $schedule_details->save();

                            ScheduleModel::where('id', $schedule_id)
                                            ->where('sales_order_id', $sales_order_id)
                                            ->update([
                                                'job_status' => 1
                                            ]);

                            SalesOrder::where('id', $sales_order_id)
                                        ->update([
                                            'job_status' => 1
                                        ]);

                            return response()->json([
                                'status' => true,
                                'message' => 'Task start Successfully'
                            ]);
                        }
                        else
                        {
                            return response()->json([
                                'status' => false,
                                'message' => 'Failed to task start'
                            ]);
                        }
                    }
                    else
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'You are not allowed to start task'
                        ]);
                    }
                }
                else if($schedule_details->job_status == 1)
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Job already Started'
                    ]);
                }
                else if($schedule_details->job_status == 2)
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Job already Completed'
                    ]);
                }
                else
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Job Cancelled'
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Not Found',
                ]);
            }
        }
    }

    // get start task time
    public function get_start_task_time(Request $request)
    {
        $schedule_details_id = $request->schedule_details_id;  
        $schedule_details = ScheduleDetails::find($schedule_details_id);

        if($schedule_details)
        {
            $db_job_details = JobDetail::where('schedule_details_id', $schedule_details_id);

            if($db_job_details->exists())
            {
                $job_details = $db_job_details->first();

                $job_details->format_start_task_time = date('h:i a', strtotime($job_details->start_task_time));

                return response()->json([
                    'status' => true,
                    'message' => 'Start Task time',
                    'data' => $job_details->only('id', 'schedule_id', 'schedule_details_id', 'sales_order_id', 'schedule_date', 'start_task_time', 'format_start_task_time')
                ]);
            }
            else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Job Details Data Not Found',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Schedule Details Data Not Found',
            ]);
        }
    }

    // end task
    public function end_task(Request $request)
    {
        // return $request->all();

        try
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'job_details_id' => 'required|exists:job_details,id',
                    'schedule_details_id' => 'required|exists:tble_schedule_details,id',
                    'before_photos' => 'nullable',
                    'before_remarks' => 'nullable|string',
                    'after_photos' => 'nullable',
                    'after_remarks' => 'nullable|string',
                ],
                [],
                [
                    'job_details_id' => 'Job details Id',
                    'schedule_details_id' => 'Schedule details Id',
                    'before_photos' => 'Before Cleaning Media',
                    'before_remarks' => 'Before Remarks',
                    'after_photos' => 'After Cleaning Media',
                    'after_remarks' => 'After Remarks',
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
                $job_details_id = $request->job_details_id; 
                $schedule_details_id = $request->schedule_details_id;       
                
                $db_job_details = JobDetail::where('id', $job_details_id)->where('schedule_details_id', $schedule_details_id)->first();

                if($db_job_details)
                {
                    if($db_job_details->end_task == 0)
                    {                      
                        $schedule_details = ScheduleDetails::find($schedule_details_id);

                        if($schedule_details)
                        {
                            if($schedule_details->job_status == 1)
                            {              
                                if($this->check_superviser($schedule_details_id) == true)
                                {
                                    // before cleaning photos start

                                    $db_before_photos = DB::table('before_cleaning_photos')
                                                            ->where('sales_order_id', $schedule_details->sales_order_id)
                                                            ->where('schedule_date', $schedule_details->schedule_date);

                                    if($db_before_photos->exists())
                                    {
                                        $db_before_photos->delete();
                                    }

                                    $before_cleaning_photos_data = [];

                                    if($request->hasFile('before_photos'))
                                    {           
                                        // return $request->file('before_photos');

                                        foreach($request->file('before_photos') as $before_image)
                                        {
                                            $ext = $before_image->extension();
                                            $before_photos_file_name = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                                            $before_image->move(public_path('uploads/before_photos'), $before_photos_file_name);

                                            $before_cleaning_photos_data[] = [
                                                'schedule_id' => $schedule_details->tble_schedule_id,
                                                'schedule_details_id' => $schedule_details_id,
                                                'sales_order_id' => $schedule_details->sales_order_id,
                                                'schedule_date' => $schedule_details->schedule_date,
                                                'before_photos' => $before_photos_file_name,
                                                'original_filename' => $before_image->getClientOriginalName(),
                                                'created_at' => Carbon::now(),
                                                'updated_at' => Carbon::now()
                                            ];
                                        }

                                        DB::table('before_cleaning_photos')->insert($before_cleaning_photos_data);
                                    }

                                    // before cleaning photos end

                                    // after cleaning photos start

                                    $db_after_photos = DB::table('after_cleaning_photos')
                                                            ->where('sales_order_id', $schedule_details->sales_order_id)
                                                            ->where('schedule_date', $schedule_details->schedule_date);

                                    if($db_after_photos->exists())
                                    {
                                        $db_after_photos->delete();
                                    }

                                    $after_cleaning_photos_data = [];

                                    if($request->hasFile('after_photos'))
                                    {           
                                        // return $request->file('after_photos');

                                        foreach($request->file('after_photos') as $after_image)
                                        {
                                            $ext = $after_image->extension();
                                            $after_photos_file_name = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                                            $after_image->move(public_path('uploads/after_photos'), $after_photos_file_name);

                                            $after_cleaning_photos_data[] = [
                                                'schedule_id' => $schedule_details->tble_schedule_id,
                                                'schedule_details_id' => $schedule_details_id,
                                                'sales_order_id' => $schedule_details->sales_order_id,
                                                'schedule_date' => $schedule_details->schedule_date,
                                                'after_photos' => $after_photos_file_name,
                                                'original_filename' => $after_image->getClientOriginalName(),
                                                'created_at' => Carbon::now(),
                                                'updated_at' => Carbon::now()
                                            ];
                                        }

                                        DB::table('after_cleaning_photos')->insert($after_cleaning_photos_data);
                                    }

                                    // after cleaning photos end

                                    // job details start

                                    // $db_job_details = JobDetail::where('sales_order_id', $schedule_details->sales_order_id)
                                    //                     ->where('schedule_date', $schedule_details->schedule_date);                            

                                    // if($db_job_details->exists())
                                    // {
                                    //     $job_details = $db_job_details->first();
                                    // }
                                    
                                    $job_details = JobDetail::find($job_details_id);

                                    $job_details->schedule_id = $schedule_details->tble_schedule_id;
                                    $job_details->schedule_details_id = $schedule_details_id;
                                    $job_details->sales_order_id = $schedule_details->sales_order_id;
                                    $job_details->schedule_date = $schedule_details->schedule_date;
                                    $job_details->before_remarks = $request->before_remarks ?? null;
                                    $job_details->after_remarks = $request->after_remarks ?? null;
                                    $job_details->end_task = 1;

                                    $result = $job_details->save();

                                    // job details end

                                    if($result)
                                    {
                                        return response()->json([
                                            'status' => true,
                                            'message' => 'Task End Successfully'
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
                                else
                                {
                                    return response()->json([
                                        'status' => false,
                                        'message' => 'You are not allowed to end task'
                                    ]);
                                }
                            }
                            else if($schedule_details->job_status == 2)
                            {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Job already Completed'
                                ]);
                            }
                            else if($schedule_details->job_status == 0)
                            {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Job not started'
                                ]);
                            }
                            else
                            {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Job Cancelled'
                                ]);
                            }
                        }
                        else
                        {
                            return response()->json([
                                'status' => false,
                                'message' => 'Data Not Found',
                            ]);
                        }
                    }
                    else
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'Task already End',
                        ]);
                    }
                }
                else
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Job Data not found or not started'
                    ]);
                }              
            }
        }
        catch (Exception $e) 
        {
            return response()->json(['status'=> false, 'message' => $e->getMessage()]);
        }
    }

    // complete survey
    public function complete_survey(Request $request)
    {
        // return $request->all();

        try
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'job_details_id' => 'required|exists:job_details,id',
                    'schedule_details_id' => 'required|exists:tble_schedule_details,id',
                    'damage' => 'required',
                    'remarks' => 'nullable|string',
                    'rating' => 'nullable',
                    'comments' => 'nullable|string',
                    'customer_signature' => 'nullable',
                ],
                [],
                [
                    'job_details_id' => 'Job Details Id',
                    'schedule_details_id' => 'Schedule details id',
                    'damage' => 'Damage',
                    'remarks' => 'Remarks',
                    'rating' => 'Rating',
                    'comments' => 'Comments',
                    'customer_signature' => 'Customer Signature',
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
                $job_details_id = $request->job_details_id;     
                $schedule_details_id = $request->schedule_details_id;  

                $db_job_details = JobDetail::where('id', $job_details_id)->where('schedule_details_id', $schedule_details_id)->first();

                if($db_job_details)
                {
                    if($db_job_details->end_task == 1)
                    {          
                        $schedule_details = ScheduleDetails::find($schedule_details_id);

                        if($schedule_details)
                        {
                            if($this->check_superviser($schedule_details_id) == true)
                            {
                                $sales_order_id = $schedule_details->sales_order_id;
                                $schedule_id = $schedule_details->tble_schedule_id; 

                                // $job_details = JobDetail::where('schedule_id', $schedule_id)
                                //                         ->where('sales_order_id', $sales_order_id)
                                //                         ->where('schedule_details_id', $schedule_details_id)
                                //                         ->first();

                                $job_details = JobDetail::find($job_details_id);

                                if($job_details)
                                {
                                    $job_details->damage = $request->damage;
                                    $job_details->remarks = $request->remarks;
                                    $job_details->rating = $request->rating;
                                    $job_details->comment = $request->comments;

                                    // customer signature start

                                    if($request->hasFile('customer_signature'))
                                    {
                                        $customer_signature = $request->file('customer_signature');

                                        $ext = $customer_signature->extension();
                                        $customer_signature_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                                        $customer_signature->move(public_path('uploads/customer_signature'), $customer_signature_file);
                                    }
                                    else
                                    {
                                        $customer_signature_file = "";
                                    }

                                    // customer signature end

                                    $job_details->signature = $customer_signature_file;
                                    $result = $job_details->save();

                                    if($result)
                                    {
                                        $schedule_details->job_status = 2;
                                        $schedule_details->save();

                                        $check_ScheduleDetails = ScheduleDetails::where('tble_schedule_id', $schedule_id)
                                                                ->where('sales_order_id', $sales_order_id)
                                                                ->where('job_status', 0);

                                        if($check_ScheduleDetails->exists())
                                        {
                                            $update_data = [
                                                'job_status' => 1
                                            ];
                                        }
                                        else
                                        {
                                            $update_data = [
                                                'job_status' => 2
                                            ];
                                        }

                                        ScheduleModel::where('id', $schedule_id)
                                                        ->where('sales_order_id', $sales_order_id)
                                                        ->update($update_data);

                                        SalesOrder::where('id', $sales_order_id)
                                                    ->update($update_data);

                                        return response()->json([
                                            'status' => true,
                                            'message' => 'Survey Completed Successfully'
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
                                else
                                {
                                    return response()->json([
                                        'status' => false,
                                        'message' => 'Job Details Not Found'
                                    ]);
                                }
                            }
                            else
                            {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'You are not allowed to complete survey'
                                ]);
                            }
                        }
                        else
                        {
                            return response()->json([
                                'status' => false,
                                'message' => 'Data Not Found',
                            ]);
                        }
                    }
                    else
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'Task not End',
                        ]);
                    }
                }
                else
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Job Details Not Found',
                    ]);
                }
            }
        }
        catch (Exception $e) 
        {
            return response()->json(['status'=> false, 'message' => $e->getMessage()]);
        }
    }

    // get payment qrcode
    public function get_payment_info(Request $request)
    {
        // return $request->all();

        $schedule_details_id = $request->schedule_details_id;    
        $schedule_details = ScheduleDetails::find($schedule_details_id);

        if($schedule_details)
        {
            if($this->check_superviser($schedule_details_id) == true)
            {
                $schedule = ScheduleModel::find($schedule_details->tble_schedule_id);
                $sales_order = SalesOrder::find($schedule->sales_order_id ?? '');
                $quotation = Quotation::find($sales_order->quotation_id ?? '');

                // company
                $company = Company::find($quotation->company_id); 

                // qr code
                $qr_code = asset('application/public/qr_code/'. $company->qr_code ?? '');

                // balance amount start

                // if($quotation)
                // {
                //     $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $sales_order->quotation_id)->get();

                //     $deposit = 0;
                //     $balance = 0;
                //     foreach($lead_payment_detail as $list)
                //     {
                //         if($list->payment_status == 1)
                //         {
                //             $deposit += $list->payment_amount;
                //         }
                //     }
                //     $balance = $quotation->grand_total - $deposit;
                // }
                // else
                // {
                //     $deposit = 0;
                //     $balance = 0;
                // }

                // balance amount end

                $data = [
                    // 'balance_payable' => $balance,
                    'balance_payable' => $schedule_details->pay_amount ?? 0,
                    'qr_code' => $qr_code,
                ];

                return response()->json([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
            else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not allowed to access this page',
                    'data' => null
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found',
                'data' => null
            ]);
        }
    }

    // complete payment
    // public function complete_payment(Request $request)
    // {
    //     // return $request->all();

    //     try
    //     {
    //         $validator = Validator::make(
    //             $request->all(),
    //             [
    //                 'schedule_details_id' => 'required|exists:tble_schedule_details,id',
    //                 'payment_method' => 'required',
    //                 'payment_proof' => 'nullable',
    //             ],
    //             [],
    //             [
    //                 'schedule_details_id' => 'Schedule details id',
    //                 'payment_method' => 'Payment Method',
    //                 'payment_proof' => 'Payment Proof',
    //             ]
    //         );

    //         if($validator->fails())
    //         {
    //             // $error = $validator->errors();
    //             // return response()->json(['status'=> false, 'message' => 'error', 'error'=>$error]);

    //             $error = $validator->errors()->all();

    //             foreach($error as $item)
    //             {
    //                 return response()->json(['status' => false, 'message' => $item]);
    //             }
    //         }
    //         else
    //         {
    //             $schedule_details_id = $request->schedule_details_id;    
    //             $schedule_details = ScheduleDetails::find($schedule_details_id);

    //             if($schedule_details)
    //             {
    //                 if($this->check_superviser($schedule_details_id) == true)
    //                 {
    //                     $schedule = ScheduleModel::find($schedule_details->tble_schedule_id);
    //                     $sales_order = SalesOrder::find($schedule->sales_order_id ?? '');
    //                     $quotation = Quotation::find($sales_order->quotation_id ?? '');

    //                     // balance amount start

    //                     // if($quotation)
    //                     // {
    //                     //     $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $sales_order->quotation_id)->get();

    //                     //     $deposit = 0;
    //                     //     $balance = 0;
    //                     //     foreach($lead_payment_detail as $list)
    //                     //     {
    //                     //         if($list->payment_status == 1)
    //                     //         {
    //                     //             $deposit += $list->payment_amount;
    //                     //         }
    //                     //     }
    //                     //     $balance = $quotation->grand_total - $deposit;
    //                     // }
    //                     // else
    //                     // {
    //                     //     $deposit = 0;
    //                     //     $balance = 0;
    //                     // }

    //                     // balance amount end

    //                     $balance = $schedule_details->pay_amount ?? 0;                           
                        
    //                     if($balance > 0)
    //                     {
    //                         $LeadPaymentInfo = new LeadPaymentInfo();  
    //                         $LeadPaymentInfo->lead_id = $quotation->lead_id;
    //                         $LeadPaymentInfo->quotation_id = $quotation->id;
    //                         $LeadPaymentInfo->customer_id =  $quotation->customer_id;
    //                         $LeadPaymentInfo->payment_method = $request->payment_method;
    //                         $LeadPaymentInfo->payment_amount = $balance;
    //                         $LeadPaymentInfo->created_at = Carbon::now();
    //                         $LeadPaymentInfo->updated_at = Carbon::now();
    //                         $LeadPaymentInfo->created_by_id = Auth::user()->id;
    //                         $LeadPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;
    //                         $result = $LeadPaymentInfo->save();     
                            
    //                         if($result)
    //                         {
    //                             // payment proof start

    //                             $insert_payment_proof = [];

    //                             if($request->hasFile('payment_proof'))
    //                             {           
    //                                 // return $request->file('payment_proof');

    //                                 foreach($request->file('payment_proof') as $payment_proof)
    //                                 {
    //                                     $ext = $payment_proof->extension();
    //                                     $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

    //                                     $payment_proof->move(public_path('uploads/payment_proof'), $payment_proof_file);

    //                                     $insert_payment_proof[] = [
    //                                         'payment_id' => $LeadPaymentInfo->id,
    //                                         "lead_id" => $quotation->lead_id,
    //                                         "quotation_id" => $quotation->id,
    //                                         "payment_proof" =>  $payment_proof_file,
    //                                         'created_at' => Carbon::now(),
    //                                         'updated_at' => Carbon::now(),
    //                                     ];
    //                                 }

    //                                 DB::table('payment_proof')->insert($insert_payment_proof);
    //                             }

    //                             // payment proof end   
                                
    //                             // change payment status start
                                
    //                             $quotation_grand_total = $quotation->grand_total;
    //                             $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $sales_order->quotation_id)->get();

    //                             $lead_payment_amount = 0;
    //                             foreach($lead_payment_detail as $list)
    //                             {
    //                                 if($list->payment_status == 1)
    //                                 {
    //                                     $lead_payment_amount += $list->payment_amount;
    //                                 }
    //                             }

    //                             if($quotation_grand_total == $lead_payment_amount)
    //                             {
    //                                 $payment_status = "paid";
    //                             }
    //                             else if($lead_payment_amount == 0)
    //                             {
    //                                 $payment_status = "unpaid";
    //                             }
    //                             else
    //                             {
    //                                 $payment_status = "partial_paid";
    //                             }

    //                             $quotation->payment_status = $payment_status;
    //                             $quotation->save();

    //                             $lead = Lead::find($quotation->lead_id);
    //                             if($lead)
    //                             {
    //                                 $lead->payment_status = "paid";
    //                                 $lead->save();
    //                             }

    //                             // change payment status end

    //                             return response()->json([
    //                                 'status' => true,
    //                                 'message' => 'Payment Completed Successfully'
    //                             ]);
    //                         }
    //                         else
    //                         {
    //                             return response()->json([
    //                                 'status' => false,
    //                                 'message' => 'Failed'
    //                             ]);
    //                         }
    //                     }
    //                     else
    //                     {
    //                         return response()->json([
    //                             'status' => true,
    //                             'message' => 'Payment already Completed'
    //                         ]);
    //                     }
    //                 }
    //                 else
    //                 {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'You are not allowed to complete payment'
    //                     ]);
    //                 }
    //             }
    //             else
    //             {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Data Not Found',
    //                 ]);
    //             }
    //         }
    //     }
    //     catch (Exception $e) 
    //     {
    //         return response()->json(['status'=> false, 'message' => $e->getMessage()]);
    //     }
    // }

    public function complete_payment(Request $request)
    {
        // return $request->all();

        try
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'schedule_details_id' => 'required|exists:tble_schedule_details,id',
                    'payment_method' => 'required',
                    'payment_proof' => 'nullable',
                ],
                [],
                [
                    'schedule_details_id' => 'Schedule details id',
                    'payment_method' => 'Payment Method',
                    'payment_proof' => 'Payment Proof',
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
                $schedule_details_id = $request->schedule_details_id;    
                $schedule_details = ScheduleDetails::find($schedule_details_id);

                if($schedule_details)
                {
                    if($this->check_superviser($schedule_details_id) == true)
                    {
                        $schedule = ScheduleModel::find($schedule_details->tble_schedule_id);
                        $sales_order = SalesOrder::find($schedule->sales_order_id ?? '');
                        $quotation = Quotation::find($sales_order->quotation_id ?? '');

                        $balance = $schedule_details->pay_amount ?? 0;                           
                        
                        if($balance > 0)
                        {
                            $LeadPaymentInfo = new LeadPaymentInfo();  
                            $LeadPaymentInfo->lead_id = $quotation->lead_id;
                            $LeadPaymentInfo->quotation_id = $quotation->id;
                            $LeadPaymentInfo->customer_id =  $quotation->customer_id;
                            $LeadPaymentInfo->payment_method = $request->payment_method;
                            $LeadPaymentInfo->payment_amount = $balance;
                            $LeadPaymentInfo->created_at = Carbon::now();
                            $LeadPaymentInfo->updated_at = Carbon::now();
                            $LeadPaymentInfo->created_by_id = Auth::user()->id;
                            $LeadPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;
                            $LeadPaymentInfo->payment_status = 0;
                            $result = $LeadPaymentInfo->save();     
                            
                            if($result)
                            {
                                // payment proof start

                                $insert_payment_proof = [];

                                if($request->hasFile('payment_proof'))
                                {           
                                    // return $request->file('payment_proof');

                                    foreach($request->file('payment_proof') as $payment_proof)
                                    {
                                        $ext = $payment_proof->extension();
                                        $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                                        $payment_proof->move(public_path('uploads/payment_proof'), $payment_proof_file);

                                        $insert_payment_proof[] = [
                                            'payment_id' => $LeadPaymentInfo->id,
                                            "lead_id" => $quotation->lead_id,
                                            "quotation_id" => $quotation->id,
                                            "payment_proof" =>  $payment_proof_file,
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now(),
                                        ];
                                    }

                                    DB::table('payment_proof')->insert($insert_payment_proof);
                                }

                                // payment proof end  
                                
                                // change payment status start
                                
                                // $quotation_grand_total = $quotation->grand_total;
                                // $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $sales_order->quotation_id)->get();

                                // $lead_payment_amount = 0;
                                // foreach($lead_payment_detail as $list)
                                // {
                                //     if($list->payment_status == 1)
                                //     {
                                //         $lead_payment_amount += $list->payment_amount;
                                //     }
                                // }

                                // if($quotation_grand_total == $lead_payment_amount)
                                // {
                                //     $payment_status = "paid";
                                // }
                                // else if($lead_payment_amount == 0)
                                // {
                                //     $payment_status = "unpaid";
                                // }
                                // else
                                // {
                                //     $payment_status = "partial_paid";
                                // }

                                // $quotation->payment_status = $payment_status;
                                // $quotation->save();

                                // $lead = Lead::find($quotation->lead_id);
                                // if($lead)
                                // {
                                //     $lead->payment_status = "paid";
                                //     $lead->save();
                                // }

                                // change payment status end

                                return response()->json([
                                    'status' => true,
                                    'message' => 'Payment Completed Successfully'
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
                        else
                        {
                            return response()->json([
                                'status' => true,
                                'message' => 'Payment already Completed'
                            ]);
                        }
                    }
                    else
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'You are not allowed to complete payment'
                        ]);
                    }
                }
                else
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data Not Found',
                    ]);
                }
            }
        }
        catch (Exception $e) 
        {
            return response()->json(['status'=> false, 'message' => $e->getMessage()]);
        }
    }

    // get balance amount
    public static function get_balance_amount($quotation_id)
    {
        $quotation = Quotation::find($quotation_id ?? '');

        if($quotation)
        {
            $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $quotation_id)->get();

            $deposit = 0;
            $balance = 0;
            foreach($lead_payment_detail as $list)
            {
                if($list->payment_status == 1)
                {
                    $deposit += $list->payment_amount;
                }
            }
            $balance = $quotation->grand_total - $deposit;
        }
        else
        {
            $deposit = 0;
            $balance = 0;
        }

        return $balance;
    }

}
