<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\ScheduleModel;
use App\Models\Crm;
use App\Models\LeadServices;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\ScheduleDetails;
use App\Models\Service;
use App\Models\ServiceAddress;
use App\Models\Services;
use App\Models\User;
use App\Notifications\CleanerNotification;
use DateTime;
use Google\Service\Monitoring\Custom;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Ladumor\OneSignal\OneSignal;
use Svg\Tag\Rect;

class ScheduleConteroller extends Controller
{
    public function index()
    {
        $schedule = ScheduleModel::select('tble_schedule.*', 'zs.zone_color')
            ->selectRaw('LEFT(tble_schedule.postalCode, 2) as shortPostalCode')
            ->leftJoin('zone_settings as zs', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(tble_schedule.postalCode, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
            })
            ->whereIn('tble_schedule.status', [1, 2])
            ->get();

        $order = SalesOrder::where('status', 0)->pluck('customer_id');
        $customer = DB::table('customers')->whereIn('id', $order)->pluck('id');

        $unassign_job = SalesOrder::where('sales_order.status', '=', 0)->get();

        foreach($unassign_job as $item)
        {
            $item->sales_order_id = $item->id;

            $quotation = Quotation::where('id', $item->quotation_id)->first();

            $item->schedule_date = $quotation->schedule_date ?? '';
            $item->time_of_cleaning = $quotation->time_of_cleaning ?? '';

            $ServiceAddress = ServiceAddress::where('service_address.id', $quotation->service_address)
                                ->select('service_address.*', 'zs.zone_color')
                                ->selectRaw('LEFT(service_address.postal_code, 2) as shortPostalCode')
                                ->leftJoin('zone_settings as zs', function ($join) {
                                    $join->whereRaw('FIND_IN_SET(LEFT(service_address.postal_code, 2), REPLACE(zs.postal_code, " ", ""))');
                                })
                                ->first();

            $item->shortPostalCode = $ServiceAddress->shortPostalCode ?? '';
            $item->address = $ServiceAddress->address ?? '';
            $item->unit_number = $ServiceAddress->unit_number ?? '';
            $item->person_incharge_name = $ServiceAddress->person_incharge_name ?? '';
            $item->zone = $ServiceAddress->zone ?? '';
            $item->territory = $ServiceAddress->territory ?? '';
            $item->contact_no = $ServiceAddress->contact_no ?? '';
            $item->email_id = $ServiceAddress->email_id ?? '';
            $item->zone_color = $ServiceAddress->zone_color ?? '';

            $customer = Crm::find($item->customer_id);

            $item->customer_name = $customer->customer_name ?? '';
            $item->customer_type = $customer->customer_type ?? '';
            $item->individual_company_name = $customer->individual_company_name ?? '';
        }

        $unassign_job = $unassign_job->map(function ($job) {
            $quotationId = $job->quotation_id;

            $details = QuotationServiceDetail::where('quotation_id', $quotationId)->get();

            // Add the details to the job
            $job['quotation_services_details'] = $details;

            // Fetch matched records from the "services" table based on service_id
            $serviceId = $details->pluck('service_id')->toArray();
            $matchedServices = Services::whereIn('id', $serviceId)->get();

            // Add matched services to the job
            $job['get_total_session_weekly_freq'] = $matchedServices;

            return $job;
        });

        // return $unassign_job;
        // dd($unassign_job);

        // get user role id
        $roles_id = SalesOrderController::get_user_roles_id();

        $employees = DB::table('xin_employees')
            ->whereIn('xin_employees.user_role_id', $roles_id)
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.*', 'zone_settings.zone_color')
            ->get();

        //dd($employees);

        $teams = DB::table('xin_team')->get();
        $resources = collect($employees)->merge($teams);

        foreach ($resources as $resource) {
            $user_id = $resource->user_id ?? $resource->team_id;
            $full_name = (isset($resource->first_name) && $resource->last_name) ? $resource->first_name . ' ' . $resource->last_name : $resource->team_name;
            $resource->full_name = $full_name;
            $title = $resource->team_name ?? $full_name;
            $employeesWithFullNames[] = $resource;
            $resource->flag = isset($resource->user_id) ? 'individual' : 'team';
        }

        // return $employeesWithFullNames;

        // dd($employeesWithFullNames);

        $resources = [];
        $events = [];
        $month_events = [];

        foreach ($employeesWithFullNames as $employee) {
            $user_id = $employee->user_id ?? $employee->team_id;
            $full_name = $employee->full_name;
            $color = isset($employee->zone_color) ? $employee->zone_color : null;
            $title = $employee->full_name ?? $employee->team_name;

            $resources[] = [
                'id' => $user_id,
                'title' => $title,
                'flag' => isset($employee->user_id) ? "individual" : "team",
                'color' => $color,
                'postal_code' => $employee->zipcode ?? ''
            ];
        }
        

        // return $resources;
        
        $ServiceDetails = [];

        foreach ($schedule as $user) 
        {
            $days = explode(', ', $user->days);

            foreach ($days as $day) 
            {         
                $customer = Crm::find($user->customer_id);
                $SalesOrder = SalesOrder::where('id', $user->sales_order_id)->first();
                $leadId = $SalesOrder->lead_id ?? null;
                $quotationId = $SalesOrder->quotation_id ?? null;

                $ServiceDetails = [];
                if ($leadId == null) {
                    $quotationServiceDetails = QuotationServiceDetail::where('quotation_id', $quotationId)->get();
                    $ServiceDetails = $quotationServiceDetails->toArray();
                } else {
                    $leadServiceDetails = LeadServices::where('lead_id', $leadId)->get();
                    $ServiceDetails = $leadServiceDetails->toArray();
                }           
                
                $schedule_details = ScheduleDetails::where('sales_order_id', $user->sales_order_id)
                                                        ->whereDate('schedule_date', $day)
                                                        ->first();
                                                        
                $formattedDay = date('Y-m-d', strtotime($day));
                // $startDateTimeString = $formattedDay . 'T' . $user->startTime;
                // $endDateTimeString = $formattedDay . 'T' . $user->endTime;
                $startDateTimeString = $formattedDay . 'T' . $schedule_details->startTime;
                $endDateTimeString = $formattedDay . 'T' . $schedule_details->endTime;

                $start = $schedule_details->startTime;
                $end = $schedule_details->endTime;
                $timeRange = date("h:i A", strtotime($start)) . " to " . date("h:i A", strtotime($end));

                // for day view start

                $tble_schedule_employee = DB::table('tble_schedule_employee')
                                                ->where('sales_order_id', $user->sales_order_id)
                                                ->whereDate('schedule_date', $day)
                                                ->get();

                if(!$tble_schedule_employee->isEmpty())
                {
                    foreach($tble_schedule_employee as $loop_emp_id)
                    {
                        $fullName = '';
    
                        if ($user->cleaner_type === 'individual') 
                        {
                            $employee = DB::table('xin_employees')
                                ->where('user_id', $loop_emp_id->employee_id)
                                ->first();
    
                            if ($employee) {
                                $fullName = $employee->first_name . ' ' . $employee->last_name;
                            }
                        } 
                        elseif ($user->cleaner_type === 'team') 
                        {
                            $team = DB::table('xin_team')
                                ->where('employee_id', $loop_emp_id->employee_id)
                                ->first();
    
                            if ($team) {
                                $fullName = $team->team_name;
                            }
                        }
    
                        if($customer->customer_type == "residential_customer_type")
                        {
                            $title = $customer->customer_name;
                        }
                        else if($customer->customer_type == "commercial_customer_type")
                        {
                            $title = $customer->individual_company_name;
                        }
                        
                        $events[] = [
                            'id' => $user->id,
                            'resourceId' => $loop_emp_id->employee_id,
                            'flag' => $user->cleaner_type,
                            'title' => $title ?? "",
                            'start' => $startDateTimeString,
                            'end' => $endDateTimeString,
                            'color' => $user->zone_color,
                            'customer_id' => $customer->id ?? null,
                            'customerName' => $customer->customer_name ?? null,
                            'email' => $customer->email ?? null,
                            'mobileNumber' => $customer->mobile_number ?? null,
                            'address' => $user->address,
                            'ServiceDetails' => $ServiceDetails ?? null,
                            'postal_code' => $user->postalCode ?? null,
                            'cleanerName' => $user->name,
                            'employeeName' => $fullName,
                            'startDate' => $user->startDate,
                            'endDate' => $user->endDate,
                            'cleaningDate' => $day,
                            'cleaningTime' => $timeRange,
                            'sales_order_id' => $user->sales_order_id,
                            'backgroundColor' => $user->zone_color,
                            'eventColor' => $user->zone_color,
                            'textColor'=> '#000000',
                        ];
                    }
                }

                // for day view end

                // for month and week view start

                if(!empty($schedule_details->employee_id))
                {                  
                    if($customer->customer_type == "residential_customer_type")
                    {
                        $title = $customer->customer_name;
                    }
                    else if($customer->customer_type == "commercial_customer_type")
                    {
                        $title = $customer->individual_company_name;
                    }
                    
                    $month_events[] = [
                        'id' => $user->id,
                        'resourceId' => '',
                        'flag' => $user->cleaner_type,
                        'title' => $title ?? "",
                        'start' => $startDateTimeString,
                        'end' => $endDateTimeString,
                        'color' => $user->zone_color,
                        'customer_id' => $customer->id ?? null,
                        'customerName' => $customer->customer_name ?? null,
                        'email' => $customer->email ?? null,
                        'mobileNumber' => $customer->mobile_number ?? null,
                        'address' => $user->address,
                        'ServiceDetails' => $ServiceDetails ?? null,
                        'postal_code' => $user->postalCode ?? null,
                        'cleanerName' => $user->name,
                        'employeeName' => '',
                        'startDate' => $user->startDate,
                        'endDate' => $user->endDate,
                        'cleaningDate' => $day,
                        'cleaningTime' => $timeRange,
                        'sales_order_id' => $user->sales_order_id,
                        'backgroundColor' => $user->zone_color,
                        'eventColor' => $user->zone_color,
                        'textColor'=> '#000000',
                    ];
                }

                // for month and week view end

                // if(!empty($schedule_details->employee_id))
                // {
                //     $fullName = '';

                //     if ($user->cleaner_type === 'individual') 
                //     {
                //         $employee = DB::table('xin_employees')
                //             ->where('user_id', $schedule_details->employee_id)
                //             ->first();

                //         if ($employee) {
                //             $fullName = $employee->first_name . ' ' . $employee->last_name;
                //         }
                //     } 
                //     elseif ($user->cleaner_type === 'team') 
                //     {
                //         $team = DB::table('xin_team')
                //             ->where('employee_id', $schedule_details->employee_id)
                //             ->first();

                //         if ($team) {
                //             $fullName = $team->team_name;
                //         }
                //     }

                //     if($customer->customer_type == "residential_customer_type")
                //     {
                //         $title = $customer->customer_name;
                //     }
                //     else if($customer->customer_type == "commercial_customer_type")
                //     {
                //         $title = $customer->individual_company_name;
                //     }
                    
                //     $events[] = [
                //         'id' => $user->id,
                //         'resourceId' => $schedule_details->employee_id,
                //         'title' => $title ?? "",
                //         'start' => $startDateTimeString,
                //         'end' => $endDateTimeString,
                //         'color' => $user->zone_color,
                //         'customer_id' => $customer->id ?? null,
                //         'customerName' => $customer->customer_name ?? null,
                //         'email' => $customer->email ?? null,
                //         'mobileNumber' => $customer->mobile_number ?? null,
                //         'address' => $user->address,
                //         'ServiceDetails' => $ServiceDetails ?? null,
                //         'postal_code' => $user->postalCode ?? null,
                //         'cleanerName' => $user->name,
                //         'employeeName' => $fullName,
                //         'startDate' => $user->startDate,
                //         'endDate' => $user->endDate,
                //         'cleaningDate' => $day,
                //         'cleaningTime' => $timeRange,
                //         'sales_order_id' => $user->sales_order_id,
                //         'backgroundColor' => $user->zone_color,
                //         'eventColor' => $user->zone_color,
                //         'textColor'=> '#000000',
                //     ];
                // }
            }
        }

        // return $events;

        // for assign cleaner

        $ac_get_team = DB::table('xin_team')->get();
        $ac_employeeNames = SalesOrderController::getEmployeeNames($ac_get_team);
        
        $ac_users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color')
            ->whereIn('xin_employees.user_role_id', $roles_id)
            ->get();

        return view('admin.scedule.index', compact('events', 'month_events', 'resources', 'unassign_job', 'ServiceDetails', 'ac_get_team', 'ac_users', 'ac_employeeNames', 'employees'));
    }

    public function get_event_data()
    {
        $schedule = ScheduleModel::select('tble_schedule.*', 'zs.zone_color')
            ->selectRaw('LEFT(tble_schedule.postalCode, 2) as shortPostalCode')
            ->leftJoin('zone_settings as zs', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(tble_schedule.postalCode, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
            })
            ->whereIn('tble_schedule.status', [1, 2])
            ->get();

        $events = [];
        $month_events = [];
        
        $ServiceDetails = [];

        foreach ($schedule as $user) 
        {
            $days = explode(', ', $user->days);

            foreach ($days as $day) 
            {         
                $customer = Crm::find($user->customer_id);
                $SalesOrder = SalesOrder::where('id', $user->sales_order_id)->first();
                $leadId = $SalesOrder->lead_id ?? null;
                $quotationId = $SalesOrder->quotation_id ?? null;

                $ServiceDetails = [];
                if ($leadId == null) {
                    $quotationServiceDetails = QuotationServiceDetail::where('quotation_id', $quotationId)->get();
                    $ServiceDetails = $quotationServiceDetails->toArray();
                } else {
                    $leadServiceDetails = LeadServices::where('lead_id', $leadId)->get();
                    $ServiceDetails = $leadServiceDetails->toArray();
                }           
                
                $schedule_details = ScheduleDetails::where('sales_order_id', $user->sales_order_id)
                                                        ->whereDate('schedule_date', $day)
                                                        ->first();
                                                        
                $formattedDay = date('Y-m-d', strtotime($day));
                // $startDateTimeString = $formattedDay . 'T' . $user->startTime;
                // $endDateTimeString = $formattedDay . 'T' . $user->endTime;
                $startDateTimeString = $formattedDay . 'T' . $schedule_details->startTime;
                $endDateTimeString = $formattedDay . 'T' . $schedule_details->endTime;

                $start = $schedule_details->startTime;
                $end = $schedule_details->endTime;
                $timeRange = date("h:i A", strtotime($start)) . " to " . date("h:i A", strtotime($end));

                // for day view start

                $tble_schedule_employee = DB::table('tble_schedule_employee')
                                                ->where('sales_order_id', $user->sales_order_id)
                                                ->whereDate('schedule_date', $day)
                                                ->get();

                if(!$tble_schedule_employee->isEmpty())
                {
                    foreach($tble_schedule_employee as $loop_emp_id)
                    {
                        $fullName = '';
    
                        if ($user->cleaner_type === 'individual') 
                        {
                            $employee = DB::table('xin_employees')
                                ->where('user_id', $loop_emp_id->employee_id)
                                ->first();
    
                            if ($employee) {
                                $fullName = $employee->first_name . ' ' . $employee->last_name;
                            }
                        } 
                        elseif ($user->cleaner_type === 'team') 
                        {
                            $team = DB::table('xin_team')
                                ->where('employee_id', $loop_emp_id->employee_id)
                                ->first();
    
                            if ($team) {
                                $fullName = $team->team_name;
                            }
                        }
    
                        if($customer->customer_type == "residential_customer_type")
                        {
                            $title = $customer->customer_name;
                        }
                        else if($customer->customer_type == "commercial_customer_type")
                        {
                            $title = $customer->individual_company_name;
                        }
                        
                        $events[] = [
                            'id' => $user->id,
                            'resourceId' => $loop_emp_id->employee_id,
                            'flag' => $user->cleaner_type,
                            'title' => $title ?? "",
                            'start' => $startDateTimeString,
                            'end' => $endDateTimeString,
                            'color' => $user->zone_color,
                            'customer_id' => $customer->id ?? null,
                            'customerName' => $customer->customer_name ?? null,
                            'email' => $customer->email ?? null,
                            'mobileNumber' => $customer->mobile_number ?? null,
                            'address' => $user->address,
                            'ServiceDetails' => $ServiceDetails ?? null,
                            'postal_code' => $user->postalCode ?? null,
                            'cleanerName' => $user->name,
                            'employeeName' => $fullName,
                            'startDate' => $user->startDate,
                            'endDate' => $user->endDate,
                            'cleaningDate' => $day,
                            'cleaningTime' => $timeRange,
                            'sales_order_id' => $user->sales_order_id,
                            'backgroundColor' => $user->zone_color,
                            'eventColor' => $user->zone_color,
                            'textColor'=> '#000000',
                        ];
                    }
                }

                // for day view end

                // for month and week view start

                if(!empty($schedule_details->employee_id))
                {                  
                    if($customer->customer_type == "residential_customer_type")
                    {
                        $title = $customer->customer_name;
                    }
                    else if($customer->customer_type == "commercial_customer_type")
                    {
                        $title = $customer->individual_company_name;
                    }
                    
                    $month_events[] = [
                        'id' => $user->id,
                        'resourceId' => '',
                        'flag' => $user->cleaner_type,
                        'title' => $title ?? "",
                        'start' => $startDateTimeString,
                        'end' => $endDateTimeString,
                        'color' => $user->zone_color,
                        'customer_id' => $customer->id ?? null,
                        'customerName' => $customer->customer_name ?? null,
                        'email' => $customer->email ?? null,
                        'mobileNumber' => $customer->mobile_number ?? null,
                        'address' => $user->address,
                        'ServiceDetails' => $ServiceDetails ?? null,
                        'postal_code' => $user->postalCode ?? null,
                        'cleanerName' => $user->name,
                        'employeeName' => '',
                        'startDate' => $user->startDate,
                        'endDate' => $user->endDate,
                        'cleaningDate' => $day,
                        'cleaningTime' => $timeRange,
                        'sales_order_id' => $user->sales_order_id,
                        'backgroundColor' => $user->zone_color,
                        'eventColor' => $user->zone_color,
                        'textColor'=> '#000000',
                    ];
                }

                // for month and week view end

                // if(!empty($schedule_details->employee_id))
                // {
                //     $fullName = '';

                //     if ($user->cleaner_type === 'individual') 
                //     {
                //         $employee = DB::table('xin_employees')
                //             ->where('user_id', $schedule_details->employee_id)
                //             ->first();

                //         if ($employee) {
                //             $fullName = $employee->first_name . ' ' . $employee->last_name;
                //         }
                //     } 
                //     elseif ($user->cleaner_type === 'team') 
                //     {
                //         $team = DB::table('xin_team')
                //             ->where('employee_id', $schedule_details->employee_id)
                //             ->first();

                //         if ($team) {
                //             $fullName = $team->team_name;
                //         }
                //     }

                //     if($customer->customer_type == "residential_customer_type")
                //     {
                //         $title = $customer->customer_name;
                //     }
                //     else if($customer->customer_type == "commercial_customer_type")
                //     {
                //         $title = $customer->individual_company_name;
                //     }
                    
                //     $events[] = [
                //         'id' => $user->id,
                //         'resourceId' => $schedule_details->employee_id,
                //         'title' => $title ?? "",
                //         'start' => $startDateTimeString,
                //         'end' => $endDateTimeString,
                //         'color' => $user->zone_color,
                //         'customer_id' => $customer->id ?? null,
                //         'customerName' => $customer->customer_name ?? null,
                //         'email' => $customer->email ?? null,
                //         'mobileNumber' => $customer->mobile_number ?? null,
                //         'address' => $user->address,
                //         'ServiceDetails' => $ServiceDetails ?? null,
                //         'postal_code' => $user->postalCode ?? null,
                //         'cleanerName' => $user->name,
                //         'employeeName' => $fullName,
                //         'startDate' => $user->startDate,
                //         'endDate' => $user->endDate,
                //         'cleaningDate' => $day,
                //         'cleaningTime' => $timeRange,
                //         'sales_order_id' => $user->sales_order_id,
                //         'backgroundColor' => $user->zone_color,
                //         'eventColor' => $user->zone_color,
                //         'textColor'=> '#000000',
                //     ];
                // }
            }
        }

        // return $events;     

        $data['events'] = $events;
        $data['month_events'] = $month_events;

        return response()->json($data);
    }

    // schedule create / assign cleaner

    public function create(Request $request)
    {
        // return $request->all();

        $rules = [
            'days' => 'required',
            'table_schedule_date.*' => 'required',
            'table_pay_amount.*' => 'nullable',
        ];

        $rules_msg = [
            'days' => 'Days',
            'table_schedule_date.*' => 'Schedule date',
            'table_pay_amount.*' => 'Payable Amount',
        ];

        if($request->input('cleaner_type') == "team")
        {
            $rules['table_team_id'] = 'nullable';
            $rules_msg['table_team_id'] = 'Team';
        }
        else if($request->input('cleaner_type') == "individual")
        {
            $rules['table_cleaner_id'] = 'nullable';
            $rules['table_superviser_emp_id'] = 'nullable';
            $rules_msg['table_cleaner_id'] = 'Cleaner';
            $rules_msg['table_superviser_emp_id'] = 'Superviser';
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [],
            $rules_msg
        );

        if ($validator->fails()) 
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $flag_status = 0;

            if($request->filled('table_schedule_date'))
            {
                // check schedule date is repeated or not start

                $check_schedule_date_repeated = $this->check_schedule_date_repeated($request);

                if($check_schedule_date_repeated['status'] == true)
                {
                    $msg = $check_schedule_date_repeated['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }    

                // check schedule date is repeated or not end

                // cleaner already assigned or not start

                $check_cleaner_assigned = $this->check_cleaner_assigned($request);

                if($check_cleaner_assigned['status'] == true)
                {
                    $msg = $check_cleaner_assigned['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }

                // cleaner already assigned or not end

                // check delivery date is repeated or not start

                $check_delivery_date_repeated = $this->check_delivery_date_repeated($request);

                if($check_delivery_date_repeated['status'] == true)
                {
                    $msg = $check_delivery_date_repeated['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }

                // check delivery date is repeated or not end

                // check driver already assigned or not start

                $check_driver_assigned = $this->check_driver_assigned($request);

                if($check_driver_assigned['status'] == true)
                {
                    $msg = $check_driver_assigned['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }

                // check driver already assigned or not end
            }

            $sales = SalesOrder::where('id', $request->sales_order_id)->first();

            $serviceDetails = QuotationServiceDetail::where('quotation_id', $sales->quotation_id)->first();

            $cleanerType = $request->input('cleaner_type');

            // schedule start

            if(ScheduleModel::where('sales_order_id', $request->input('sales_order_id'))->exists())
            {
                ScheduleModel::where('sales_order_id', $request->input('sales_order_id'))->delete();
            }

            $schedule = new ScheduleModel();
            $schedule->sales_order_id = $request->input('sales_order_id');
            $schedule->sales_order_no = $request->input('sales_order_no');
            $schedule->customer_id = $request->input('customer_id');
            $schedule->service_id = $serviceDetails->service_id ?? 0;
            $schedule->cleaner_type = $request->input('cleaner_type');
            $schedule->startDate = $request->input('startDate');
            $schedule->endDate = $request->input('endDate');
            $schedule->postalCode = $request->input('postalCode');
            $schedule->unitNo = $request->input('unitNo');
            $schedule->address = $request->input('address');
            $schedule->total_session = $request->input('total_session');
            $schedule->weekly_freq = $request->input('weekly_freq');
            $schedule->man_power = $request->man_power;
            $schedule->startTime = $request->input('startTime');
            $schedule->endTime = $request->input('endTime');
            $schedule->remarks = $request->input('remarks');

            $selectedDays = $request->input('days');
            if ($selectedDays) {
                $schedule->selected_days = implode(',', $selectedDays);
            }

            $schedule->save();

            // schedule end

            // schedule details start

            $formattedDates = [];

            ScheduleDetails::where('sales_order_id', $request->input('sales_order_id'))->delete();
            DB::table('tble_schedule_employee')->where('sales_order_id', $request->input('sales_order_id'))->delete();

            if($request->filled('table_schedule_date'))
            {
                $table_schedule_date = $request->table_schedule_date;
                $table_startTime = $request->table_startTime;
                $table_endTime = $request->table_endTime;
                $table_team_id = $request->table_team_id;
                $table_cleaner_id = $request->table_cleaner_id;
                $table_superviser_emp_id = $request->table_superviser_emp_id;
                $table_pay_amount = $request->table_pay_amount;
                $table_remarks = $request->table_remarks;

                // driver start               
                $table_delivery_date = $request->table_delivery_date;
                $table_delivery_time = $request->table_delivery_time;
                $table_driver_emp_id = $request->table_driver_emp_id;
                $table_delivery_remarks = $request->table_delivery_remarks;                
                // drievr end

                for($i=0; $i<count($table_schedule_date); $i++)
                {
                    $schedule_details = new ScheduleDetails();
                    $schedule_details->tble_schedule_id = $schedule->id;
                    $schedule_details->sales_order_id = $request->input('sales_order_id');
                    $schedule_details->sales_order_no = $request->input('sales_order_no');
                    $schedule_details->schedule_date = $table_schedule_date[$i];
                    $schedule_details->schedule_day = date('l', strtotime($table_schedule_date[$i]));
                    $schedule_details->startTime = $table_startTime[$i];
                    $schedule_details->endTime = $table_endTime[$i];
                    $schedule_details->pay_amount = $table_pay_amount[$i] ?? 0;
                    $schedule_details->remarks = $table_remarks[$i];
                    $schedule_details->cleaner_type = $request->cleaner_type;

                    if ($cleanerType == 'team') 
                    {
                        $schedule_details->employee_id = $table_team_id[$i];                       
                    } 
                    else if ($cleanerType == 'individual') 
                    {
                        $schedule_details->superviser_emp_id = isset($table_cleaner_id[$i]) ? $table_superviser_emp_id[$i] : null;
                        $schedule_details->employee_id = isset($table_cleaner_id[$i]) ? implode(",", $table_cleaner_id[$i]) : null;
                    }

                    // driver start
                    if(!empty($table_delivery_date[$i]))
                    {
                        $schedule_details->delivery_date = $table_delivery_date[$i];
                        $schedule_details->delivery_time = $table_delivery_time[$i];
                        $schedule_details->driver_emp_id = $table_driver_emp_id[$i];
                        $schedule_details->delivery_remarks = $table_delivery_remarks[$i];
                    }
                    // drievr end

                    $formattedDates[] = $table_schedule_date[$i];

                    $schedule_details->save();

                    // schedule employee start   
                    
                    $player_id_arr = [];

                    if ($cleanerType == 'team') 
                    {
                        if(isset($table_team_id[$i]))
                        {
                            $tble_schedule_employee = [
                                'tble_schedule_details_id' => $schedule_details->id,
                                'tble_schedule_id' => $schedule->id,
                                'sales_order_id' => $request->input('sales_order_id'),
                                'sales_order_no' => $request->input('sales_order_no'),
                                'schedule_date' => $table_schedule_date[$i],
                                'cleaner_type' => $cleanerType,
                                'employee_id' => $table_team_id[$i]
                            ];

                            DB::table('tble_schedule_employee')->insert($tble_schedule_employee);

                            // player id start

                            $cleaner_id_arr = $this->get_cleaner_id_array($table_team_id[$i]);        
                            $get_user = User::whereIn('id', $cleaner_id_arr)->get();     

                            if(!$get_user->isEmpty())
                            {
                                foreach($get_user as $loop_user)
                                {
                                    if(isset($loop_user->player_id))
                                    {
                                        // $player_id_arr[] = $get_user->player_id; 

                                        // onesignal push notification for cleaner start
                                    
                                        $user_fields['include_player_ids'] = [$loop_user->player_id];
                                        // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($table_schedule_date[$i]));
                                        $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($table_schedule_date[$i])) . ", Time: " . date('h:i A', strtotime($table_startTime[$i])) . " - " . date('h:i A', strtotime($table_endTime[$i]));
                                        OneSignal::sendPush($user_fields, $notificationMsg);                               
    
                                        // onesignal push notification for cleaner end    
                                        
                                        // normal notification start

                                        $cleaner_data_notif = User::find($loop_user->id);
                                        $cleaner_notification = [
                                            'user_id' => $loop_user->id,
                                            'message' => $notificationMsg
                                        ];
                                        Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                                        // normal notification end
                                    }
                                }
                            }

                            // player id end
                        }
                    }
                    else if ($cleanerType == 'individual') 
                    {
                        $tble_schedule_employee = [];

                        if(isset($table_cleaner_id[$i]))
                        {
                            foreach($table_cleaner_id[$i] as $cleaner_id)
                            {
                                $tble_schedule_employee = [
                                    'tble_schedule_details_id' => $schedule_details->id,
                                    'tble_schedule_id' => $schedule->id,
                                    'sales_order_id' => $request->input('sales_order_id'),
                                    'sales_order_no' => $request->input('sales_order_no'),
                                    'schedule_date' => $table_schedule_date[$i],
                                    'cleaner_type' => $cleanerType,
                                    'employee_id' => $cleaner_id
                                ];

                                DB::table('tble_schedule_employee')->insert($tble_schedule_employee);

                                // player id start

                                $get_user = User::where('id', $cleaner_id)->first();     
                                
                                if(isset($get_user->player_id))
                                {
                                    // $player_id_arr[] = $get_user->player_id;   
                                    
                                    // onesignal push notification for cleaner start
                       
                                    $user_fields['include_player_ids'] = [$get_user->player_id];
                                    // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($table_schedule_date[$i]));
                                    $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($table_schedule_date[$i])) . ", Time: " . date('h:i A', strtotime($table_startTime[$i])) . " - " . date('h:i A', strtotime($table_endTime[$i]));
                                    OneSignal::sendPush($user_fields, $notificationMsg);
                                    
                                    // onesignal push notification for cleaner end
                                    
                                    // normal notification start

                                    $cleaner_data_notif = User::find($get_user->id);
                                    $cleaner_notification = [
                                        'user_id' => $get_user->id,
                                        'message' => $notificationMsg
                                    ];
                                    Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                                    // normal notification end
                                }

                                // player id end
                            }

                            if($request->man_power != count($table_cleaner_id[$i]))
                            {
                                $flag_status = 1;
                            }                            
                        }     
                    }     

                    // schedule employee end                                             
                }

                $schedule->days = implode(', ', $formattedDates);               
                $schedule->save();
            }

            // schedule details end

            // sales order start

            if ($sales) 
            {
                $total_count = count($formattedDates);
                $assign_count = count(ScheduleDetails::where('tble_schedule_id', $schedule->id)->whereNotNull('employee_id')->get());

                if($total_count == 0)
                {
                    $sales->status = 0;
                    $sales->cleaner_assigned_status = 0;
                    $sales->save();

                    $schedule->status = 0;
                    $schedule->cleaner_assigned_status = 0;
                    $schedule->save();
                }
                else
                {
                    if($total_count == $assign_count)
                    {
                        if($flag_status == 1)
                        {
                            $sales->status = 2;
                            $sales->cleaner_assigned_status = 2;
                            $sales->save();
    
                            $schedule->status = 2;
                            $schedule->cleaner_assigned_status = 2;
                            $schedule->save();
                        }
                        else
                        {
                            $sales->status = 1;
                            $sales->cleaner_assigned_status = 1;
                            $sales->save();
    
                            $schedule->status = 1;
                            $schedule->cleaner_assigned_status = 1;
                            $schedule->save();
                        }
                        
                    }
                    else
                    {
                        $sales->status = 2;
                        $sales->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                        $sales->save();

                        $schedule->status = 2;
                        $schedule->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                        $schedule->save();
                    }
                }
            }

            // sales order end

            // quotation details start

            $db_ScheduleModel = ScheduleModel::where('sales_order_id', $request->sales_order_id)->first();
            $QuotationServiceDetail = QuotationServiceDetail::where('quotation_id', $sales->quotation_id)->where('service_id', $db_ScheduleModel->service_id)->get();

            if(!$QuotationServiceDetail->isEmpty())
            {
                QuotationServiceDetail::where('id', $QuotationServiceDetail[0]->id)->update(['total_session'=>$request->input('total_session')]);
            }

            // quotation details end

            // log data store start

            LogController::store('sales_order', 'Cleaner Assigned', $request->input('sales_order_id'));

            // log data store end

            // return redirect()->back()->with(['status' => 'success', 'message' => 'Cleaner Assigned']);

            return response()->json(['status' => 'success', 'message' => 'Cleaner Assigned']);
        }
    }

    public static function get_cleaner_id_array($team_id)
    {
        $cleaner_id_arr = [];

        $xin_team = DB::table('xin_team')->where('team_id', $team_id)->first();

        if($xin_team)
        {
            $cleaner_id_arr = explode(',', $xin_team->employee_id);
        }
         
        return $cleaner_id_arr;
    }

    // edit cleaner

    public function fetch($id)
    {
        // get user role id
        $roles_id = SalesOrderController::get_user_roles_id();

        $users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color')
            ->whereIn('xin_employees.user_role_id', $roles_id)
            ->get();

        $get_team = DB::table('xin_team')->get();
        $employeeNames = $this->getEmployeeNames($get_team);   

        // $cleaner_data = ScheduleModel::find($id);
        $cleaner_data = ScheduleModel::where('sales_order_id', $id)->first();
        $Schedule_details = ScheduleDetails::where('tble_schedule_id', $cleaner_data->id)->get();

        if ($cleaner_data) 
        {
            $cleaner_data->selected_days = explode(',', $cleaner_data->selected_days);
            $cleaner_data->new_days = explode(', ', $cleaner_data->days);
        }

        $SalesOrder = SalesOrder::where('id', $cleaner_data->sales_order_id)->first();
        $Quotation = Quotation::find($SalesOrder->quotation_id);
        $QuotationServiceDetail = QuotationServiceDetail::where('quotation_id', $SalesOrder->quotation_id)->get();
        $Services = Services::where('id', $QuotationServiceDetail[0]->service_id)->first();

        $cleaner_data->hour_session = $Services->hour_session;
        $cleaner_data->invoice_amount = $Quotation->grand_total;
        $cleaner_data->balance_amount = PaymentController::get_balance_amount($SalesOrder->quotation_id);
        $cleaner_data->invoice_no = $SalesOrder->invoice_no ?? '';

        return view('admin.salesOrder.edit-schedule', compact('cleaner_data', 'Schedule_details', 'users', 'get_team', 'employeeNames'));
    }

    public function getDataFromSchedule($id)
    {
        // get user role id
        $roles_id = SalesOrderController::get_user_roles_id();

        $users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color')
            ->whereIn('xin_employees.user_role_id', $roles_id)
            ->get();

        $get_team = DB::table('xin_team')->get();
        $employeeNames = $this->getEmployeeNames($get_team);

        $cleaner_data = ScheduleModel::find($id);
        $Schedule_details = ScheduleDetails::where('tble_schedule_id', $id)->get();

        if ($cleaner_data) 
        {
            $cleaner_data->selected_days = explode(',', $cleaner_data->selected_days);
            $cleaner_data->new_days = explode(', ', $cleaner_data->days);
        }

        $cleaner_data->customer_name = Crm::find($cleaner_data->customer_id)->customer_name;
        $cleaner_data->company_name = Crm::find($cleaner_data->customer_id)->individual_company_name;
        $cleaner_data->customer_type = Crm::find($cleaner_data->customer_id)->customer_type;

        $SalesOrder = SalesOrder::where('id', $cleaner_data->sales_order_id)->first();
        $Quotation = Quotation::find($SalesOrder->quotation_id);
        $QuotationServiceDetail = QuotationServiceDetail::where('quotation_id', $SalesOrder->quotation_id)->get();
        $Services = Services::where('id', $QuotationServiceDetail[0]->service_id)->first();

        $cleaner_data->hour_session = $Services->hour_session;
        $cleaner_data->invoice_amount = $Quotation->grand_total;
        $cleaner_data->balance_amount = PaymentController::get_balance_amount($SalesOrder->quotation_id);
        $cleaner_data->invoice_no = $SalesOrder->invoice_no ?? '';

        return response()->json([
            'cleaner_data' => $cleaner_data,
            'users' => $users,
            'get_team' => $get_team,
            'employeeNames' => $employeeNames,
        ]);
    }

    private function getEmployeeNames($teams)
    {
        $employeeNames = [];

        foreach ($teams as $team) {
            $employeeIds = explode(',', $team->employee_id);

            foreach ($employeeIds as $employee_id) {
                $employee = DB::table('xin_employees')->where('user_id', $employee_id)->first();
                if ($employee) {
                    $employeeNames[$team->team_id][] = $employee->first_name . ' ' . $employee->last_name;
                }
            }
        }
        //  dd($employeeNames);
        return $employeeNames;
    }

    public function update(Request $request, $id)
    {
        // return $request->all();

        $rules = [
            'days' => 'required',
            'table_schedule_date.*' => 'required',
            'table_pay_amount.*' => 'nullable',
        ];

        $rules_msg = [
            'days' => 'Days',
            'table_schedule_date.*' => 'Schedule date',
            'table_pay_amount.*' => 'Payable Amount',
        ];

        if($request->input('cleaner_type') == "team")
        {
            $rules['table_team_id'] = 'nullable';
            $rules_msg['table_team_id'] = 'Team';
        }
        else if($request->input('cleaner_type') == "individual")
        {
            $rules['table_cleaner_id'] = 'nullable';
            $rules['table_superviser_emp_id'] = 'nullable';
            $rules_msg['table_cleaner_id'] = 'Cleaner';
            $rules_msg['table_superviser_emp_id'] = 'Superviser';
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [],
            $rules_msg
        );

        if ($validator->fails()) 
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $flag_status = 0;

            if($request->filled('table_schedule_date'))
            {           
                // check schedule date is repeated or not start

                $check_schedule_date_repeated = $this->check_schedule_date_repeated($request);

                if($check_schedule_date_repeated['status'] == true)
                {
                    $msg = $check_schedule_date_repeated['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }             

                // check schedule date is repeated or not end

                // cleaner already assigned or not start

                $check_cleaner_assigned = $this->check_cleaner_assigned($request);

                if($check_cleaner_assigned['status'] == true)
                {
                    $msg = $check_cleaner_assigned['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }

                // cleaner already assigned or not end

                // check delivery date is repeated or not start

                $check_delivery_date_repeated = $this->check_delivery_date_repeated($request);

                if($check_delivery_date_repeated['status'] == true)
                {
                    $msg = $check_delivery_date_repeated['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }

                // check delivery date is repeated or not end

                // check driver already assigned or not start

                $check_driver_assigned = $this->check_driver_assigned($request);

                if($check_driver_assigned['status'] == true)
                {
                    $msg = $check_driver_assigned['msg'];
                    return response()->json(['status' => 'failed', 'message' => $msg]);
                }

                // check driver already assigned or not end
            }

            if($request->filled('schedule_details_id'))
            {
                $schedule_details_id_arr = $request->schedule_details_id;
            }  
            else
            {
                $schedule_details_id_arr = [];
            }

            // return $schedule_details_id_arr;

            $cleanerType = $request->input('cleaner_type');

            // schedule start

            $cleaner_data = ScheduleModel::find($id);

            $cleaner_data->sales_order_id = $request->input('sales_order_id');
            $cleaner_data->sales_order_no = $request->input('sales_order_no');
            $cleaner_data->customer_id = $request->input('customer_id');
            $cleaner_data->cleaner_type = $request->input('cleaner_type');      
            $cleaner_data->startDate = $request->input('startDate');
            $cleaner_data->endDate = $request->input('endDate');
            $cleaner_data->postalCode = $request->input('postalCode');
            $cleaner_data->unitNo = $request->input('unitNo');
            $cleaner_data->address = $request->input('address');
            $cleaner_data->total_session = $request->input('total_session');
            $cleaner_data->weekly_freq = $request->input('weekly_freq');
            $cleaner_data->man_power = $request->man_power;
            $cleaner_data->startTime = $request->input('startTime');
            $cleaner_data->endTime = $request->input('endTime');
            $cleaner_data->remarks = $request->input('edit_remarks');

            $selectedDays = $request->input('days');
            if ($selectedDays) {
                $cleaner_data->selected_days = implode(',', $selectedDays);
            }
            
            $cleaner_data->save();

            // schedule end

            // schedule details start

            $formattedDates = [];

            ScheduleDetails::where('tble_schedule_id', $id)
                            ->where('sales_order_id', $request->input('sales_order_id'))
                            ->whereNotIn('id', $schedule_details_id_arr)
                            ->delete();

            DB::table('tble_schedule_employee')
                ->where('tble_schedule_id', $id)
                ->where('sales_order_id', $request->input('sales_order_id'))
                ->whereNotIn('tble_schedule_details_id', $schedule_details_id_arr)
                ->delete();

            if($request->filled('table_schedule_date'))
            {
                $table_schedule_details_id = $request->table_schedule_details_id;
                $table_schedule_date = $request->table_schedule_date;
                $table_startTime = $request->table_startTime;
                $table_endTime = $request->table_endTime;
                $table_team_id = $request->table_team_id;
                $table_cleaner_id = $request->table_cleaner_id;
                $table_superviser_emp_id = $request->table_superviser_emp_id;
                $table_pay_amount = $request->table_pay_amount;
                $table_remarks = $request->table_remarks;

                // driver start
                $table_delivery_date = $request->table_delivery_date;
                $table_delivery_time = $request->table_delivery_time;
                $table_driver_emp_id = $request->table_driver_emp_id;
                $table_delivery_remarks = $request->table_delivery_remarks;
                // drievr end

                for($i=0; $i<count($table_schedule_date); $i++)
                {
                    if(empty($table_schedule_details_id[$i]))
                    {                   
                        $schedule_details = new ScheduleDetails();
                        $schedule_details->tble_schedule_id = $id;
                        $schedule_details->sales_order_id = $request->input('sales_order_id');
                        $schedule_details->sales_order_no = $request->input('sales_order_no');
                        $schedule_details->schedule_date = $table_schedule_date[$i];
                        $schedule_details->schedule_day = date('l', strtotime($table_schedule_date[$i]));
                        $schedule_details->startTime = $table_startTime[$i];
                        $schedule_details->endTime = $table_endTime[$i];
                        $schedule_details->pay_amount = $table_pay_amount[$i] ?? 0;
                        $schedule_details->remarks = $table_remarks[$i];
                        $schedule_details->cleaner_type = $request->cleaner_type;

                        if ($cleanerType == 'team') 
                        {
                            $schedule_details->employee_id = $table_team_id[$i];
                        } 
                        else if ($cleanerType == 'individual') 
                        {
                            $schedule_details->superviser_emp_id = isset($table_cleaner_id[$i]) ? $table_superviser_emp_id[$i] : null;
                            $schedule_details->employee_id = isset($table_cleaner_id[$i]) ? implode(",", $table_cleaner_id[$i]) : null;
                        }                  
                        
                        // driver start
                        if(!empty($table_delivery_date[$i]))
                        {
                            $schedule_details->delivery_date = $table_delivery_date[$i];
                            $schedule_details->delivery_time = $table_delivery_time[$i];
                            $schedule_details->driver_emp_id = $table_driver_emp_id[$i];
                            $schedule_details->delivery_remarks = $table_delivery_remarks[$i];
                        }
                        // drievr end

                        $schedule_details->save();

                        // schedule employee start        
                        
                        $player_id_arr = [];

                        if ($cleanerType == 'team') 
                        {
                            if(isset($table_team_id[$i]))
                            {
                                $tble_schedule_employee = [
                                    'tble_schedule_details_id' => $schedule_details->id,
                                    'tble_schedule_id' => $cleaner_data->id,
                                    'sales_order_id' => $request->input('sales_order_id'),
                                    'sales_order_no' => $request->input('sales_order_no'),
                                    'schedule_date' => $table_schedule_date[$i],
                                    'cleaner_type' => $cleanerType,
                                    'employee_id' => $table_team_id[$i]
                                ];

                                DB::table('tble_schedule_employee')->insert($tble_schedule_employee);

                                // player id start

                                $cleaner_id_arr = $this->get_cleaner_id_array($table_team_id[$i]);        
                                $get_user = User::whereIn('id', $cleaner_id_arr)->get();     

                                if(!$get_user->isEmpty())
                                {
                                    foreach($get_user as $loop_user)
                                    {
                                        if(isset($loop_user->player_id))
                                        {
                                            // $player_id_arr[] = $get_user->player_id;   
                                            
                                            // onesignal push notification for cleaner start
                                    
                                            $user_fields['include_player_ids'] = [$loop_user->player_id];
                                            // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($table_schedule_date[$i]));
                                            $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($table_schedule_date[$i])) . ", Time: " . date('h:i A', strtotime($table_startTime[$i])) . " - " . date('h:i A', strtotime($table_endTime[$i]));
                                            OneSignal::sendPush($user_fields, $notificationMsg);                               
        
                                            // onesignal push notification for cleaner end  

                                            // normal notification start

                                            $cleaner_data_notif = User::find($loop_user->id);
                                            $cleaner_notification = [
                                                'user_id' => $loop_user->id,
                                                'message' => $notificationMsg
                                            ];
                                            Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                                            // normal notification end
                                        }
                                    }
                                }

                                // player id end
                            }
                        }
                        else if ($cleanerType == 'individual') 
                        {
                            $tble_schedule_employee = [];

                            if(isset($table_cleaner_id[$i]))
                            {
                                foreach($table_cleaner_id[$i] as $cleaner_id)
                                {
                                    $tble_schedule_employee = [
                                        'tble_schedule_details_id' => $schedule_details->id,
                                        'tble_schedule_id' => $cleaner_data->id,
                                        'sales_order_id' => $request->input('sales_order_id'),
                                        'sales_order_no' => $request->input('sales_order_no'),
                                        'schedule_date' => $table_schedule_date[$i],
                                        'cleaner_type' => $cleanerType,
                                        'employee_id' => $cleaner_id
                                    ];

                                    DB::table('tble_schedule_employee')->insert($tble_schedule_employee);
                                    
                                    // player id start

                                    $get_user = User::where('id', $cleaner_id)->first();     
                                    
                                    if(isset($get_user->player_id))
                                    {
                                        // $player_id_arr[] = $get_user->player_id;      
                                        
                                        // onesignal push notification for cleaner start
                       
                                        $user_fields['include_player_ids'] = [$get_user->player_id];
                                        // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($table_schedule_date[$i]));
                                        $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($table_schedule_date[$i])) . ", Time: " . date('h:i A', strtotime($table_startTime[$i])) . " - " . date('h:i A', strtotime($table_endTime[$i]));
                                        OneSignal::sendPush($user_fields, $notificationMsg);
                                        
                                        // onesignal push notification for cleaner end

                                        // normal notification start

                                        $cleaner_data_notif = User::find($get_user->id);
                                        $cleaner_notification = [
                                            'user_id' => $get_user->id,
                                            'message' => $notificationMsg
                                        ];
                                        Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                                        // normal notification end
                                    }

                                    // player id end
                                }

                                if($request->man_power != count($table_cleaner_id[$i]))
                                {
                                    $flag_status = 1;
                                }
                            }
                        }                      

                        // schedule employee end
                    }                 

                    $formattedDates[] = $table_schedule_date[$i];
                }

                $cleaner_data->days = implode(', ', $formattedDates);
                $cleaner_data->save();
            }

            // schedule details end

            // sales order start

            $sales = SalesOrder::where('id', $request->sales_order_id)->first();

            if ($sales) 
            {
                $total_count = count($formattedDates);
                $assign_count = count(ScheduleDetails::where('tble_schedule_id', $id)->whereNotNull('employee_id')->get());

                if($total_count == 0)
                {
                    $sales->status = 0;
                    $sales->cleaner_assigned_status = 0;
                    $sales->save();

                    $cleaner_data->status = 0;
                    $cleaner_data->cleaner_assigned_status = 0;
                    $cleaner_data->save();
                }
                else
                {
                    if($total_count == $assign_count)
                    {
                        if($flag_status == 1)
                        {
                            $sales->status = 2;
                            $sales->cleaner_assigned_status = 2;
                            $sales->save();
    
                            $cleaner_data->status = 2;                           
                            $cleaner_data->cleaner_assigned_status = 2;
                            $cleaner_data->save();
                        }
                        else
                        {
                            $sales->status = 1;
                            $sales->cleaner_assigned_status = 1;
                            $sales->save();

                            $cleaner_data->status = 1;
                            $cleaner_data->cleaner_assigned_status = 1;
                            $cleaner_data->save();
                        }
                    }
                    else
                    {
                        $sales->status = 2;
                        $sales->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                        $sales->save();

                        $cleaner_data->status = 2;
                        $cleaner_data->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                        $cleaner_data->save();
                    }
                }
            }

            // sales order end

            // quotation details start

            $db_ScheduleModel = ScheduleModel::where('sales_order_id', $request->sales_order_id)->first();
            $QuotationServiceDetail = QuotationServiceDetail::where('quotation_id', $sales->quotation_id)->where('service_id', $db_ScheduleModel->service_id)->get();

            if(!$QuotationServiceDetail->isEmpty())
            {
                QuotationServiceDetail::where('id', $QuotationServiceDetail[0]->id)->update(['total_session'=>$request->input('total_session')]);
            }

            // quotation details end

            // log data store start

            LogController::store('sales_order', 'Schedule Updated', $request->input('sales_order_id'));

            // log data store end

            // return redirect()->back()->with(['status' => 'success', 'message' => 'Cleaner Assigned Updated']);

            return response()->json(['status' => 'success', 'message' => 'Schedule Updated']);
        }
    }

    public function scheduleUpdate(Request $request, $id)
    {
        // return $request->all();

        $rules = [
            'days' => 'required',
            'table_schedule_date.*' => 'required',
            'table_pay_amount.*' => 'nullable',
        ];

        $rules_msg = [
            'days' => 'Days',
            'table_schedule_date.*' => 'Schedule date',
            'table_pay_amount.*' => 'Payable Amount',
        ];

        if($request->input('cleaner_type') == "team")
        {
            $rules['table_team_id'] = 'nullable';
            $rules_msg['table_team_id'] = 'Team';
        }
        else if($request->input('cleaner_type') == "individual")
        {
            $rules['table_cleaner_id'] = 'nullable';
            $rules['table_superviser_emp_id'] = 'nullable';
            $rules_msg['table_cleaner_id'] = 'Cleaner';
            $rules_msg['table_superviser_emp_id'] = 'Superviser';
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [],
            $rules_msg
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }      

        $flag_status = 0;

        if($request->filled('table_schedule_date'))
        {
            // check schedule date is repeated or not start

            $check_schedule_date_repeated = $this->check_schedule_date_repeated($request);

            if($check_schedule_date_repeated['status'] == true)
            {
                $msg = $check_schedule_date_repeated['msg'];
                return response()->json(['status' => 'failed', 'message' => $msg]);
            }     

            // check schedule date is repeated or not end

            // cleaner already assigned or not start

            $check_cleaner_assigned = $this->check_cleaner_assigned($request);

            if($check_cleaner_assigned['status'] == true)
            {
                $msg = $check_cleaner_assigned['msg'];
                return response()->json(['status' => 'failed', 'message' => $msg]);
            }

            // cleaner already assigned or not end

            // check delivery date is repeated or not start

            $check_delivery_date_repeated = $this->check_delivery_date_repeated($request);

            if($check_delivery_date_repeated['status'] == true)
            {
                $msg = $check_delivery_date_repeated['msg'];
                return response()->json(['status' => 'failed', 'message' => $msg]);
            }

            // check delivery date is repeated or not end

            // check driver already assigned or not start

            $check_driver_assigned = $this->check_driver_assigned($request);

            if($check_driver_assigned['status'] == true)
            {
                $msg = $check_driver_assigned['msg'];
                return response()->json(['status' => 'failed', 'message' => $msg]);
            }

            // check driver already assigned or not end
        }

        if($request->filled('schedule_details_id'))
        {
            $schedule_details_id_arr = $request->schedule_details_id;
        }  
        else
        {
            $schedule_details_id_arr = [];
        }

        // return $schedule_details_id_arr;

        $cleanerType = $request->input('cleaner_type');

        // schedule start

        $cleaner_data = ScheduleModel::find($id);

        $cleaner_data->sales_order_id = $request->input('sales_order_id');
        $cleaner_data->sales_order_no = $request->input('sales_order_no');
        $cleaner_data->customer_id = $request->input('customer_id');
        $cleaner_data->cleaner_type = $request->input('cleaner_type');
        $cleaner_data->startDate = $request->input('startDate');
        $cleaner_data->endDate = $request->input('endDate');
        $cleaner_data->postalCode = $request->input('postalCode');
        $cleaner_data->unitNo = $request->input('unitNo');
        $cleaner_data->address = $request->input('address');
        $cleaner_data->total_session = $request->input('total_session');
        $cleaner_data->weekly_freq = $request->input('weekly_freq');
        $cleaner_data->man_power = $request->man_power;
        $cleaner_data->startTime = $request->input('startTime');
        $cleaner_data->endTime = $request->input('endTime');
        $cleaner_data->remarks = $request->input('edit_remarks');

        $selectedDays = $request->input('days');
        if ($selectedDays) {
            $cleaner_data->selected_days = implode(',', $selectedDays);
        }
        
        $cleaner_data->save();

        // schedule start

        // schedule details start

        ScheduleDetails::where('tble_schedule_id', $id)
                        ->where('sales_order_id', $request->input('sales_order_id'))
                        ->whereNotIn('id', $schedule_details_id_arr)
                        ->delete();

        DB::table('tble_schedule_employee')
                        ->where('tble_schedule_id', $id)
                        ->where('sales_order_id', $request->input('sales_order_id'))
                        ->whereNotIn('tble_schedule_details_id', $schedule_details_id_arr)
                        ->delete();

        if($request->filled('table_schedule_date'))
        {
            $table_schedule_details_id = $request->table_schedule_details_id;
            $table_schedule_date = $request->table_schedule_date;
            $table_startTime = $request->table_startTime;
            $table_endTime = $request->table_endTime;
            $table_team_id = $request->table_team_id;
            $table_cleaner_id = $request->table_cleaner_id;
            $table_superviser_emp_id = $request->table_superviser_emp_id;
            $table_pay_amount = $request->table_pay_amount;
            $table_remarks = $request->table_remarks;

            // driver start
            $table_delivery_date = $request->table_delivery_date;
            $table_delivery_time = $request->table_delivery_time;
            $table_driver_emp_id = $request->table_driver_emp_id;
            $table_delivery_remarks = $request->table_delivery_remarks;
            // drievr end

            for($i=0; $i<count($table_schedule_date); $i++)
            {
                if(empty($table_schedule_details_id[$i]))
                {
                    $schedule_details = new ScheduleDetails();
                    $schedule_details->tble_schedule_id = $id;
                    $schedule_details->sales_order_id = $request->input('sales_order_id');
                    $schedule_details->sales_order_no = $request->input('sales_order_no');
                    $schedule_details->schedule_date = $table_schedule_date[$i];
                    $schedule_details->schedule_day = date('l', strtotime($table_schedule_date[$i]));
                    $schedule_details->startTime = $table_startTime[$i];
                    $schedule_details->endTime = $table_endTime[$i];
                    $schedule_details->pay_amount = $table_pay_amount[$i] ?? 0;
                    $schedule_details->remarks = $table_remarks[$i];
                    $schedule_details->cleaner_type = $request->cleaner_type;

                    if ($cleanerType == 'team') 
                    {
                        $schedule_details->employee_id = $table_team_id[$i];
                    } 
                    else if ($cleanerType == 'individual') 
                    {
                        $schedule_details->superviser_emp_id = isset($table_cleaner_id[$i]) ? $table_superviser_emp_id[$i] : null;
                        $schedule_details->employee_id = isset($table_cleaner_id[$i]) ? implode(",", $table_cleaner_id[$i]) : null;
                    }   

                    // driver start
                    if(!empty($table_delivery_date[$i]))
                    {
                        $schedule_details->delivery_date = $table_delivery_date[$i];
                        $schedule_details->delivery_time = $table_delivery_time[$i];
                        $schedule_details->driver_emp_id = $table_driver_emp_id[$i];
                        $schedule_details->delivery_remarks = $table_delivery_remarks[$i];
                    }
                    // drievr end
                  
                    $schedule_details->save();

                    // schedule employee start   

                    $player_id_arr = [];

                    if ($cleanerType == 'team') 
                    {
                        if(isset($table_team_id[$i]))
                        {
                            $tble_schedule_employee = [
                                'tble_schedule_details_id' => $schedule_details->id,
                                'tble_schedule_id' => $cleaner_data->id,
                                'sales_order_id' => $request->input('sales_order_id'),
                                'sales_order_no' => $request->input('sales_order_no'),
                                'schedule_date' => $table_schedule_date[$i],
                                'cleaner_type' => $cleanerType,
                                'employee_id' => $table_team_id[$i]
                            ];

                            DB::table('tble_schedule_employee')->insert($tble_schedule_employee);

                            // player id start

                            $cleaner_id_arr = $this->get_cleaner_id_array($table_team_id[$i]);        
                            $get_user = User::whereIn('id', $cleaner_id_arr)->get();     

                            if(!$get_user->isEmpty())
                            {
                                foreach($get_user as $loop_user)
                                {
                                    if(isset($loop_user->player_id))
                                    {
                                        // $player_id_arr[] = $get_user->player_id;
                                        
                                        // onesignal push notification for cleaner start
                                    
                                        $user_fields['include_player_ids'] = [$loop_user->player_id];
                                        // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($table_schedule_date[$i]));
                                        $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($table_schedule_date[$i])) . ", Time: " . date('h:i A', strtotime($table_startTime[$i])) . " - " . date('h:i A', strtotime($table_endTime[$i]));
                                        OneSignal::sendPush($user_fields, $notificationMsg);                               

                                        // onesignal push notification for cleaner end  
                                        
                                        // normal notification start

                                        $cleaner_data_notif = User::find($loop_user->id);
                                        $cleaner_notification = [
                                            'user_id' => $loop_user->id,
                                            'message' => $notificationMsg
                                        ];
                                        Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                                        // normal notification end
                                    }
                                }
                            }

                            // player id end
                        }
                    }
                    else if ($cleanerType == 'individual') 
                    {
                        $tble_schedule_employee = [];

                        if(isset($table_cleaner_id[$i]))
                        {
                            foreach($table_cleaner_id[$i] as $cleaner_id)
                            {
                                $tble_schedule_employee = [
                                    'tble_schedule_details_id' => $schedule_details->id,
                                    'tble_schedule_id' => $cleaner_data->id,
                                    'sales_order_id' => $request->input('sales_order_id'),
                                    'sales_order_no' => $request->input('sales_order_no'),
                                    'schedule_date' => $table_schedule_date[$i],
                                    'cleaner_type' => $cleanerType,
                                    'employee_id' => $cleaner_id
                                ];

                                DB::table('tble_schedule_employee')->insert($tble_schedule_employee);

                                // player id start

                                $get_user = User::where('id', $cleaner_id)->first();     
                                
                                if(isset($get_user->player_id))
                                {
                                    // $player_id_arr[] = $get_user->player_id;
                                    
                                    // onesignal push notification for cleaner start
                       
                                    $user_fields['include_player_ids'] = [$get_user->player_id];
                                    // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($table_schedule_date[$i]));
                                    $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($table_schedule_date[$i])) . ", Time: " . date('h:i A', strtotime($table_startTime[$i])) . " - " . date('h:i A', strtotime($table_endTime[$i]));
                                    OneSignal::sendPush($user_fields, $notificationMsg);
                                    
                                    // onesignal push notification for cleaner end

                                    // normal notification start

                                    $cleaner_data_notif = User::find($get_user->id);
                                    $cleaner_notification = [
                                        'user_id' => $get_user->id,
                                        'message' => $notificationMsg
                                    ];
                                    Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                                    // normal notification end
                                }

                                // player id end
                            }

                            if($request->man_power != count($table_cleaner_id[$i]))
                            {
                                $flag_status = 1;
                            }
                        }
                    }                   

                    // schedule employee end
                }

                $formattedDates[] = $table_schedule_date[$i];
            }

            $cleaner_data->days = implode(', ', $formattedDates);
            $cleaner_data->save();
        }

        // schedule details end

        // sales order start

        $sales = SalesOrder::where('id', $request->sales_order_id)->first();

        if ($sales) 
        {
            $total_count = count($formattedDates);
            $assign_count = count(ScheduleDetails::where('tble_schedule_id', $id)->whereNotNull('employee_id')->get());

            if($total_count == 0)
            {
                $sales->status = 0;
                $sales->cleaner_assigned_status = 0;
                $sales->save();

                $cleaner_data->status = 0;
                $cleaner_data->cleaner_assigned_status = 0;
                $cleaner_data->save();
            }
            else
            {
                if($total_count == $assign_count)
                {                   
                    if($flag_status == 1)
                    {
                        $sales->status = 2;
                        $sales->cleaner_assigned_status = 2;
                        $sales->save();

                        $cleaner_data->status = 2;
                        $cleaner_data->cleaner_assigned_status = 2;
                        $cleaner_data->save();
                    }
                    else
                    {
                        $sales->status = 1;
                        $sales->cleaner_assigned_status = 1;
                        $sales->save();

                        $cleaner_data->status = 1;
                        $cleaner_data->cleaner_assigned_status = 1;
                        $cleaner_data->save();
                    }
                }
                else
                {
                    $sales->status = 2;
                    $sales->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                    $sales->save();

                    $cleaner_data->status = 2;
                    $cleaner_data->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                    $cleaner_data->save();
                }
            }
        }

        // sales order end

        // quotation details start

        $db_ScheduleModel = ScheduleModel::where('sales_order_id', $request->sales_order_id)->first();
        $QuotationServiceDetail = QuotationServiceDetail::where('quotation_id', $sales->quotation_id)->where('service_id', $db_ScheduleModel->service_id)->get();

        if(!$QuotationServiceDetail->isEmpty())
        {
            QuotationServiceDetail::where('id', $QuotationServiceDetail[0]->id)->update(['total_session'=>$request->input('total_session')]);
        }

        // quotation details end

        // log data store start

        LogController::store('sales_order', 'Schedule Updated', $request->input('sales_order_id'));

        // log data store end

        return response()->json(['status'=>'success', 'message' => 'Schedule Updated']);
    }

    // check schedule date is repeated or not start

    public static function check_schedule_date_repeated($request)
    {
        $temp_table_schedule_date_arr = $request->table_schedule_date;

        $temp_duplicate_date = array_diff_assoc($temp_table_schedule_date_arr, array_unique($temp_table_schedule_date_arr));
    
        if(count($temp_duplicate_date) > 0)
        {         
            $new_date_arr = [];

            foreach($temp_duplicate_date as $item)
            {
                $new_date_arr[] = date('d-m-Y', strtotime($item));
            }

            $result = [
                'status' => true,
                'msg' => implode(', ', $new_date_arr) . ' Dates are repeated'
            ];

            return $result;
        }

        $result = [
            'status' => false,
            'msg' => ''
        ];

        return $result;
    }

    // check cleaner already assigned or not

    public static function check_cleaner_assigned($request)
    {
        for($i=0; $i<count($request->table_schedule_date); $i++)
        {
            if(!empty($request->table_team_id[$i]) || !empty($request->table_cleaner_id[$i]))
            {
                $schedule_details_exists = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $request->sales_order_id)
                                                            ->whereDate('tble_schedule_details.schedule_date', $request->table_schedule_date[$i])
                                                            // ->where(function ($query) use ($request, $i) {
                                                            //     $query->where(function ($query1) use ($request, $i) {
                                                            //         $query1->whereTime('tble_schedule_details.startTime', '<=', $request->table_startTime[$i]);
                                                            //         $query1->whereTime('tble_schedule_details.endTime', '>=', $request->table_startTime[$i]);
                                                            //     })
                                                            //     ->orWhere(function ($query2) use ($request, $i) {
                                                            //         $query2->whereTime('tble_schedule_details.startTime', '<=', $request->table_endTime[$i]);
                                                            //         $query2->whereTime('tble_schedule_details.endTime', '>=', $request->table_endTime[$i]);
                                                            //     }) ;
                                                            // })   
                                                            ->where(function ($query) use ($request, $i) {
                                                                $query->where(function ($query1) use ($request, $i) {
                                                                    $query1->whereTime('tble_schedule_details.startTime', '<', $request->table_endTime[$i])
                                                                           ->whereTime('tble_schedule_details.endTime', '>', $request->table_startTime[$i]);
                                                                });
                                                            })                          
                                                            ->where('tble_schedule_details.job_status', 0)
                                                            ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                                            ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id');                                                                               

                if ($request->input('cleaner_type') == 'team') 
                {
                    $schedule_details_exists = $schedule_details_exists->where('tble_schedule_employee.employee_id', $request->table_team_id[$i]);
                } 
                else if ($request->input('cleaner_type') == 'individual') 
                {
                    if(isset($request->table_cleaner_id[$i]))
                    {
                        foreach($request->table_cleaner_id[$i] as $cleaner_id)
                        {
                            $schedule_details_exists = $schedule_details_exists->where('tble_schedule_employee.employee_id', $cleaner_id);
                        }
                    }                          
                }

                // return $schedule_details_exists->get();

                if($schedule_details_exists->exists())
                {
                    $msg = "Cleaner already assigned on " . date('d-m-Y', strtotime($request->table_schedule_date[$i])) . ", " . date('h:i a', strtotime($request->table_startTime[$i])) . " to " . date('h:i a', strtotime($request->table_endTime[$i]));
                
                    $result = [
                        'status' => true,
                        'msg' => $msg
                    ];

                    return $result;
                }
            }
        }

        $result = [
            'status' => false,
            'msg' => ''
        ];

        return $result;
    }

    // check delivery date is repeated or not

    public static function check_delivery_date_repeated($request)
    {
        $temp_table_delivery_date_arr = $request->table_delivery_date;

        $filtered_dates = array_filter($temp_table_delivery_date_arr, function ($date) {
            return !is_null($date);
        });

        $temp_duplicate_date = array_diff_assoc($filtered_dates, array_unique($filtered_dates));
    
        if(count($temp_duplicate_date) > 0)
        {         
            $new_date_arr = [];

            foreach($temp_duplicate_date as $item)
            {
                if(!empty($item))
                {
                    $new_date_arr[] = date('d-m-Y', strtotime($item));
                }
            }

            $result = [
                'status' => true,
                'msg' => implode(', ', $new_date_arr) . ' Delivery Dates are repeated'
            ];

            return $result;
        }

        $result = [
            'status' => false,
            'msg' => ''
        ];

        return $result;
    }

    // check driver already assigned or not

    public static function check_driver_assigned($request)
    {
        $req_table_delivery_date = $request->table_delivery_date;
        $req_delivery_time = $request->table_delivery_time;      
        $req_table_driver_emp_id = $request->table_driver_emp_id;

        for($i=0; $i<count($request->table_schedule_date); $i++)
        {
            $req_delivery_end_time = Carbon::parse($request->table_delivery_time[$i])->addHour()->format('H:i');

            if(!empty($request->table_delivery_date[$i]))
            {
                $check_ScheduleDetails = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $request->sales_order_id)
                                                    ->whereDate('tble_schedule_details.delivery_date', $request->table_delivery_date[$i])                         
                                                    ->where('tble_schedule_details.driver_emp_id', $request->table_driver_emp_id[$i])                         
                                                    ->where('tble_schedule_details.job_status', 0)     
                                                    ->get();

                foreach($check_ScheduleDetails as $item)
                {
                    $item->delivery_end_time = Carbon::parse($item->delivery_time)->addHour()->format('H:i');
                    $item->delivery_before_time = Carbon::parse($item->delivery_time)->subHour()->format('H:i');

                    if((strtotime($request->table_delivery_time[$i]) >= strtotime($item->delivery_time) && strtotime($request->table_delivery_time[$i]) < strtotime($item->delivery_end_time)) 
                        || (strtotime($item->delivery_time) >= strtotime($request->table_delivery_time[$i]) && strtotime($item->delivery_time) < strtotime($req_delivery_end_time)))
                    {
                        $msg = "Driver already assigned on " . date('d-m-Y', strtotime($request->table_delivery_date[$i])) . ", " . date('h:i a', strtotime($item->delivery_time)) . " to " . date('h:i a', strtotime($item->delivery_end_time));
                
                        $result = [
                            'status' => true,
                            'msg' => $msg
                        ];

                        return $result;
                    }
                }
            }
        }

        $result = [
            'status' => false,
            'msg' => ''
        ];

        return $result;
    }

    public function eventUpdate(Request $request)
    {
        // return $request->all();

        // dd($request->all());          

        if ($request->data['flag'] == 'updateTime') 
        {
            $schedule_id = $request->data['schedule_id'];
            $schedule_date = $request->data['schedule_date'];

            $timeObj = \DateTime::createFromFormat('h:ia', $request->data['startTime']);
            $startTime = $timeObj->format('H:i:s');

            $timeObj1 = \DateTime::createFromFormat('h:ia', $request->data['endTime']);
            $endTime = $timeObj1->format('H:i:s');

            // cleaner already assigned or not start

            $db_schedule_details = ScheduleDetails::where('tble_schedule_id', $schedule_id)->whereDate('schedule_date', $schedule_date)->first();
            $db_schedule_employee_arr = DB::table('tble_schedule_employee')->where('tble_schedule_id', $schedule_id)->whereDate('schedule_date', $schedule_date)->pluck('employee_id')->toArray();

            $schedule_details_exists = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $db_schedule_details->sales_order_id)
                                                        ->whereDate('tble_schedule_details.schedule_date', $db_schedule_details->schedule_date)
                                                        // ->where(function ($query) use ($request, $startTime, $endTime) {
                                                        //     $query->where(function ($query1) use ($request, $startTime) {
                                                        //         $query1->whereTime('tble_schedule_details.startTime', '<=', $startTime);
                                                        //         $query1->whereTime('tble_schedule_details.endTime', '>=', $startTime);
                                                        //     })
                                                        //     ->orWhere(function ($query2) use ($request, $endTime) {
                                                        //         $query2->whereTime('tble_schedule_details.startTime', '<=', $endTime);
                                                        //         $query2->whereTime('tble_schedule_details.endTime', '>=', $endTime);
                                                        //     }) ;
                                                        // })  
                                                        ->where(function ($query) use ($request, $startTime, $endTime) {
                                                            $query->where(function ($query1) use ($request, $startTime, $endTime) {
                                                                $query1->whereTime('tble_schedule_details.startTime', '<', $endTime)
                                                                       ->whereTime('tble_schedule_details.endTime', '>', $startTime);
                                                            });
                                                        })                        
                                                        ->where('tble_schedule_details.job_status', 0)
                                                        ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                                        ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id');                                                                                  
                                     
            if ($db_schedule_details->cleaner_type == 'team') 
            {
                // $schedule_details_exists = $schedule_details_exists->where('employee_id', $db_schedule_details->employee_id);
                $schedule_details_exists = $schedule_details_exists->whereIn('tble_schedule_employee.employee_id', $db_schedule_employee_arr);
            } 
            else if ($db_schedule_details->cleaner_type == 'individual') 
            {
                // $schedule_details_exists = $schedule_details_exists->where('employee_id', $db_schedule_details->employee_id);
                $schedule_details_exists = $schedule_details_exists->whereIn('tble_schedule_employee.employee_id', $db_schedule_employee_arr);
            }

            // return $schedule_details_exists->get();

            if($schedule_details_exists->exists())
            {
                $msg = "Cleaner already assigned on " . date('d-m-Y', strtotime($db_schedule_details->schedule_date)) . ", " . date('h:i a', strtotime($startTime)) . " to " . date('h:i a', strtotime($endTime));
                return response()->json(['status' => 'failed', 'message' => $msg]);
            }                                           

            // cleaner already assigned or not end

            $schedule_details = ScheduleDetails::where('tble_schedule_id', $schedule_id)->whereDate('schedule_date', $schedule_date)->first();
            $schedule_details->startTime =  $startTime;
            $schedule_details->endTime =  $endTime;
            $schedule_details->save();

            // log data store start

            LogController::store('schedule', 'Schedule Time updated', $schedule_id);

            // log data store end

            return response()->json(['status' => 'success', 'message' => 'Time Updated Sucessfully']);
        } 
        else if($request->data['flag'] == "dayView_eventUpdate")
        {
            $emp = $request->data['empId'];    
            $resourceType = $request->data['resourceType'];

            if ($resourceType == 'individual') {
                $cleanerType = DB::table('xin_employees')->where('user_id', $emp)->first();
                $cleanrName = $cleanerType->first_name . ' ' . $cleanerType->last_name;
            } else {
                $cleanrName = DB::table('xin_team')->where('team_id', $emp)->pluck('team_name')->first();
            }

            $sales_order_id = $request->data['sales_order_id'];
            $customerID = $request->data['customerID'];
        
            $SalesOrder = SalesOrder::where('id', $sales_order_id)->first();
            $serviceDetail = json_decode($request->data['Service_Details'], true)[0];

            $quotation = Quotation::find($SalesOrder->quotation_id);
            $quotation_details = QuotationServiceDetail::where('quotation_id', $SalesOrder->quotation_id)
                                                        ->where('service_type', 'service')
                                                        ->get();
            
            $service = Services::find($quotation_details[0]->service_id);
            $hour_session = $service->hour_session ?? 1;

            $custAdd = ServiceAddress::where('id', $quotation->service_address ?? '')->first();

            $sch_date = $request->data['days'];
            $dateTime = Carbon::parse($request->data['startTime']);
            $startTime = $dateTime->format('H:i:s');
            $endTime = date('H:i:s', strtotime($startTime.'+'.$hour_session.' hour'));

            // cleaner already assigned or not start

            $schedule_details_exists = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $request->data['sales_order_id'])
                                                        ->whereDate('tble_schedule_details.schedule_date', $request->data['days'])
                                                        // ->where(function ($query) use ($request, $startTime, $endTime) {
                                                        //     $query->where(function ($query1) use ($request, $startTime) {
                                                        //         $query1->whereTime('tble_schedule_details.startTime', '<=', $startTime);
                                                        //         $query1->whereTime('tble_schedule_details.endTime', '>=', $startTime);
                                                        //     })
                                                        //     ->orWhere(function ($query2) use ($request, $endTime) {
                                                        //         $query2->whereTime('tble_schedule_details.startTime', '<=', $endTime);
                                                        //         $query2->whereTime('tble_schedule_details.endTime', '>=', $endTime);
                                                        //     }) ;
                                                        // })       
                                                        ->where(function ($query) use ($request, $startTime, $endTime) {
                                                            $query->where(function ($query1) use ($request, $startTime, $endTime) {
                                                                $query1->whereTime('tble_schedule_details.startTime', '<', $endTime)
                                                                       ->whereTime('tble_schedule_details.endTime', '>', $startTime);
                                                            });
                                                        })                  
                                                        ->where('tble_schedule_details.job_status', 0)
                                                        ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                                        ->select('tble_schedule_details.*', 'tble_schedule_employee.employee_id as new_employee_id');                                                                                  
                                                        
            if ($request->data['resourceType'] == 'team') 
            {
                $schedule_details_exists = $schedule_details_exists->where('tble_schedule_employee.employee_id', $request->data['empId']);
            } 
            else if ($request->data['resourceType'] == 'individual') 
            {
                $schedule_details_exists = $schedule_details_exists->where('tble_schedule_employee.employee_id', $request->data['empId']);
            }

            if($schedule_details_exists->exists())
            {
                $msg = "Cleaner already assigned on " . date('d-m-Y', strtotime($request->data['days'])) . ", " . date('h:i a', strtotime($startTime)) . " to " . date('h:i a', strtotime($endTime));
                return response()->json(['status' => 'failed', 'message' => $msg]);
            }                                           

            // cleaner already assigned or not end

            // man power start

            $service_id_arr = $quotation_details->pluck('service_id')->toarray();

            $db_all_services = Services::whereIn('id', $service_id_arr)->get();

            $man_power = 0;
            foreach($db_all_services as $loop_item)
            {
                $man_power += $loop_item->man_power;
            }

            // man power end

            // schedule start

            if(ScheduleModel::where('sales_order_id', $sales_order_id)->exists())
            {
                ScheduleDetails::where('sales_order_id', $sales_order_id)->delete();
            }

            $ScheduleModel = new ScheduleModel();
            $ScheduleModel->customer_id = $customerID;
            $ScheduleModel->startDate = $sch_date;
            $ScheduleModel->endDate = $sch_date;
            $ScheduleModel->startTime = $startTime;
            $ScheduleModel->endTime = $endTime;
            $ScheduleModel->days = $sch_date;
            $ScheduleModel->selected_days = date('l', strtotime($sch_date));
            $ScheduleModel->sales_order_no = $SalesOrder->sales_order_no;
            $ScheduleModel->sales_order_id = $sales_order_id;
            $ScheduleModel->postalCode = $custAdd->postal_code ?? '';
            $ScheduleModel->unitNo = $custAdd->unit_number ?? '';
            $ScheduleModel->address = $custAdd->address ?? '';
            // $ScheduleModel->name = $cleanrName;
            $ScheduleModel->cleaner_type = $resourceType;

            // $ScheduleModel->service_id = $serviceDetail['id'];
            // $ScheduleModel->total_session = $serviceDetail['total_session'];
            // $ScheduleModel->weekly_freq = $serviceDetail['weekly_freq'];

            $ScheduleModel->service_id = $service->id;
            $ScheduleModel->total_session = $quotation_details[0]->total_session ? $quotation_details[0]->total_session : ($service->total_session ?? '');
            $ScheduleModel->weekly_freq = $service->weekly_freq;
            $ScheduleModel->man_power = $man_power;
      
            $ScheduleModel->save();

            // schedule end

            // schedule details start

            ScheduleDetails::where('sales_order_id', $sales_order_id)->delete();
                
            $schedule_details = new ScheduleDetails();
            $schedule_details->tble_schedule_id = $ScheduleModel->id;
            $schedule_details->sales_order_id = $sales_order_id;
            $schedule_details->sales_order_no = $SalesOrder->sales_order_no;
            $schedule_details->schedule_date = $sch_date;
            $schedule_details->schedule_day = date('l', strtotime($sch_date));
            $schedule_details->startTime = $startTime;
            $schedule_details->endTime = $endTime;
            $schedule_details->cleaner_type = $resourceType;
            $schedule_details->employee_id = $emp;
            $schedule_details->superviser_emp_id = $emp;
            $schedule_details->pay_amount = PaymentController::get_balance_amount($quotation->id);

            $schedule_details->save();

            // schedule details end

            // schedule employee start    
            
            DB::table('tble_schedule_employee')->where('sales_order_id', $sales_order_id)->delete();
          
            $tble_schedule_employee = [
                'tble_schedule_details_id' => $schedule_details->id,
                'tble_schedule_id' => $ScheduleModel->id,
                'sales_order_id' => $sales_order_id,
                'sales_order_no' => $SalesOrder->sales_order_no,
                'schedule_date' => $sch_date,
                'cleaner_type' => $resourceType,
                'employee_id' => $emp
            ];
            
            DB::table('tble_schedule_employee')->insert($tble_schedule_employee);

            // schedule employee end

            if($resourceType == "team")
            {
                // player id start

                $cleaner_id_arr = $this->get_cleaner_id_array($emp);        
                $get_user = User::whereIn('id', $cleaner_id_arr)->get();     

                if(!$get_user->isEmpty())
                {
                    foreach($get_user as $loop_user)
                    {
                        if(isset($loop_user->player_id))
                        {                          
                            // onesignal push notification for cleaner start
                    
                            $user_fields['include_player_ids'] = [$loop_user->player_id];
                            // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($sch_date));
                            $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($sch_date)) . ", Time: " . date('h:i A', strtotime($startTime)) . " - " . date('h:i A', strtotime($endTime));
                            OneSignal::sendPush($user_fields, $notificationMsg);                               

                            // onesignal push notification for cleaner end  

                            // normal notification start

                            $cleaner_data_notif = User::find($loop_user->id);
                            $cleaner_notification = [
                                'user_id' => $loop_user->id,
                                'message' => $notificationMsg
                            ];
                            Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                            // normal notification end
                        }
                    }
                }

                // player id end
            }
            else if($resourceType == "individual")
            {
                // player id start

                $get_user = User::where('id', $emp)->first();     
                                                
                if(isset($get_user->player_id))
                {        
                    // onesignal push notification for cleaner start

                    $user_fields['include_player_ids'] = [$get_user->player_id];
                    // $notificationMsg = "You have assigned a task, schedule date is " . date('d-m-Y', strtotime($sch_date));
                    $notificationMsg = "You have assigned a task. Appointment Date: " . date('d-m-Y', strtotime($sch_date)) . ", Time: " . date('h:i A', strtotime($startTime)) . " - " . date('h:i A', strtotime($endTime));
                    OneSignal::sendPush($user_fields, $notificationMsg);
                    
                    // onesignal push notification for cleaner end

                    // normal notification start

                    $cleaner_data_notif = User::find($get_user->id);
                    $cleaner_notification = [
                        'user_id' => $get_user->id,
                        'message' => $notificationMsg
                    ];
                    Notification::send($cleaner_data_notif, new CleanerNotification($cleaner_notification));

                    // normal notification end
                }

                // player id end
            }

            // sales order start

            $order = SalesOrder::where(['id' => $sales_order_id])->first();
            if ($order) 
            {
                if($ScheduleModel->total_session == 1)
                {
                    if($ScheduleModel->man_power == 1)
                    {
                        $order->status = 1;
                        $order->cleaner_assigned_status = 1;
                        $order->save();
        
                        $ScheduleModel->status = 1;
                        $ScheduleModel->cleaner_assigned_status = 1;
                        $ScheduleModel->save();
                    }
                    else
                    {
                        $order->status = 2;
                        $order->cleaner_assigned_status = 2;
                        $order->save();
        
                        $ScheduleModel->status = 2;
                        $ScheduleModel->cleaner_assigned_status = 2;
                        $ScheduleModel->save();
                    }
                }
                else
                {
                    $order->status = 2;
                    $order->cleaner_assigned_status = 2;
                    $order->save();
    
                    $ScheduleModel->status = 2;
                    $ScheduleModel->cleaner_assigned_status = 2;
                    $ScheduleModel->save();
                }                
            }

            // sales order end

            // log data store start

            LogController::store('sales_order', 'Cleaner Assigned', $sales_order_id);

            // log data store end

            return response()->json(['status' => 'success', 'message' => 'Cleaner Assigned Successfully']);
        }
        // else if($request->data['flag'] == "monthView_eventUpdate")
        // { 
        //     $resourceType = $request->data['resourceType'];

        //     $sales_order_id = $request->data['sales_order_id'];
        //     $customerID = $request->data['customerID'];
        
        //     $SalesOrder = SalesOrder::where('id', $sales_order_id)->first();
        //     $serviceDetail = json_decode($request->data['Service_Details'], true)[0];

        //     $quotation = Quotation::find($SalesOrder->quotation_id);
        //     $quotation_details = QuotationServiceDetail::where('quotation_id', $SalesOrder->quotation_id)
        //                                                 ->where('service_type', 'service')
        //                                                 ->get();
            
        //     $service = Services::find($quotation_details[0]->service_id);
        //     $hour_session = $service->hour_session ?? 1;

        //     $custAdd = ServiceAddress::where('id', $quotation->service_address ?? '')->first();

        //     $sch_date = $request->data['days'];

        //     // man power start

        //     $service_id_arr = $quotation_details->pluck('service_id')->toarray();

        //     $db_all_services = Services::whereIn('id', $service_id_arr)->get();

        //     $man_power = 0;
        //     foreach($db_all_services as $loop_item)
        //     {
        //         $man_power += $loop_item->man_power;
        //     }

        //     // man power end

        //     // schedule start

        //     if(ScheduleModel::where('sales_order_id', $sales_order_id)->exists())
        //     {
        //         ScheduleDetails::where('sales_order_id', $sales_order_id)->delete();
        //     }

        //     $ScheduleModel = new ScheduleModel();
        //     $ScheduleModel->customer_id = $customerID;
        //     $ScheduleModel->startDate = $sch_date;
        //     $ScheduleModel->endDate = $sch_date;
        //     $ScheduleModel->days = $sch_date;
        //     $ScheduleModel->selected_days = date('l', strtotime($sch_date));
        //     $ScheduleModel->sales_order_no = $SalesOrder->sales_order_no;
        //     $ScheduleModel->sales_order_id = $sales_order_id;
        //     $ScheduleModel->postalCode = $custAdd->postal_code ?? '';
        //     $ScheduleModel->unitNo = $custAdd->unit_number ?? '';
        //     $ScheduleModel->address = $custAdd->address ?? '';
        //     $ScheduleModel->cleaner_type = $resourceType;
        //     $ScheduleModel->service_id = $service->id;
        //     $ScheduleModel->total_session = $quotation_details[0]->total_session ? $quotation_details[0]->total_session : ($service->total_session ?? '');
        //     $ScheduleModel->weekly_freq = $service->weekly_freq;
        //     $ScheduleModel->man_power = $man_power;
      
        //     $ScheduleModel->save();

        //     // schedule end

        //     // schedule details start

        //     ScheduleDetails::where('sales_order_id', $sales_order_id)->delete();
                
        //     $schedule_details = new ScheduleDetails();
        //     $schedule_details->tble_schedule_id = $ScheduleModel->id;
        //     $schedule_details->sales_order_id = $sales_order_id;
        //     $schedule_details->sales_order_no = $SalesOrder->sales_order_no;
        //     $schedule_details->schedule_date = $sch_date;
        //     $schedule_details->schedule_day = date('l', strtotime($sch_date));
        //     $schedule_details->cleaner_type = $resourceType;
        //     $schedule_details->pay_amount = PaymentController::get_balance_amount($quotation->id);

        //     $schedule_details->save();

        //     // schedule details end

        //     // schedule employee start    
            
        //     DB::table('tble_schedule_employee')->where('sales_order_id', $sales_order_id)->delete();
          
        //     // schedule employee end

        //     // sales order start

        //     $order = SalesOrder::where(['id' => $sales_order_id])->first();
        //     if ($order) 
        //     {
        //         if($ScheduleModel->total_session == 1)
        //         {
        //             if($ScheduleModel->man_power == 1)
        //             {
        //                 $order->status = 1;
        //                 $order->cleaner_assigned_status = 1;
        //                 $order->save();
        
        //                 $ScheduleModel->status = 1;
        //                 $ScheduleModel->cleaner_assigned_status = 1;
        //                 $ScheduleModel->save();
        //             }
        //             else
        //             {
        //                 $order->status = 2;
        //                 $order->cleaner_assigned_status = 2;
        //                 $order->save();
        
        //                 $ScheduleModel->status = 2;
        //                 $ScheduleModel->cleaner_assigned_status = 2;
        //                 $ScheduleModel->save();
        //             }
        //         }
        //         else
        //         {
        //             $order->status = 2;
        //             $order->cleaner_assigned_status = 0;
        //             $order->save();
    
        //             $ScheduleModel->status = 2;
        //             $ScheduleModel->cleaner_assigned_status = 0;
        //             $ScheduleModel->save();
        //         }                
        //     }

        //     // sales order end

        //     // log data store start

        //     LogController::store('sales_order', 'Cleaner Assigned', $sales_order_id);

        //     // log data store end

        //     return response()->json(['status' => 'success', 'message' => 'Cleaner Assigned Successfully']);
        // }

        return response()->json();
    }

    // cleaner details

    public function cleaner_details($cleaner_type, $cleaner_id)
    {
        if($cleaner_type == "team")
        {
            $data['team'] = DB::table('xin_team')->where('team_id', $cleaner_id)->first();

            $temp_emp = explode(",", $data['team']->employee_id);

            $name_arr = [];

            foreach($temp_emp as $item)
            {
                $xin_employees = DB::table('xin_employees')->where('user_id', $item)->first();

                if($xin_employees)
                {
                    $name = $xin_employees->first_name . " " . $xin_employees->last_name;
                }
                else
                {
                    $name = "";
                }

                $name_arr[] = $name;
            }

            $data['team']->employee_name = implode(",", $name_arr);
            $data['team']->cleaner_type = $cleaner_type;
        }
        else if($cleaner_type == "individual")
        {
            $data['employee'] = DB::table('xin_employees')
                                    ->where('xin_employees.user_id', $cleaner_id)
                                    ->leftJoin('company', 'xin_employees.company_id', '=', 'company.id')
                                    ->select(
                                        'xin_employees.*',
                                        'company.company_name as company_name'
                                    )
                                    ->first();

            $data['employee']->name = $data['employee']->first_name . " " . $data['employee']->last_name;
            $data['employee']->cleaner_type = $cleaner_type;
        }

        $data['cleaner_type'] = $cleaner_type;
        $data['cleaner_id'] = $cleaner_id;

        // $data['schedule'] = ScheduleModel::where('cleaner_type', $cleaner_type)->where('employee_id', $cleaner_id)->get();
    
        // return $data;

        return view('admin.scedule.cleaner-details', $data);
    }

    // cleaner_schedule_get_table_data

    // public function cleaner_upcoming_schedule_get_table_data(Request $request)
    // {
    //     $cleaner_id = $request->cleaner_id;
    //     $cleaner_type = $request->cleaner_type;

    //     $data['schedule'] = ScheduleDetails::where('tble_schedule_details.cleaner_type', $cleaner_type)
    //                                         ->where('tble_schedule_details.employee_id', $cleaner_id)
    //                                         ->whereDate('tble_schedule_details.schedule_date', '>=', date('Y-m-d'))
    //                                         ->leftJoin('tble_schedule', 'tble_schedule.id', '=', 'tble_schedule_details.tble_schedule_id')
    //                                         ->select('tble_schedule.*', 'tble_schedule_details.schedule_date as schedule_date', 'tble_schedule_details.startTime as schedule_dt_start_time', 'tble_schedule_details.endTime as schedule_dt_end_time')
    //                                         ->get();

    //     $new_data = [];

    //     foreach($data['schedule'] as $key => $item)
    //     {
    //         $customer = Crm::find($item->customer_id);

    //         if($customer)
    //         {
    //             $item->customer_name = $customer->customer_name;
    //             $item->individual_company_name = $customer->individual_company_name;
    //         }
    //         else
    //         {
    //             $item->customer_name = "";
    //             $item->individual_company_name = "";
    //         }

    //         // invoice no

    //         $SalesOrder = SalesOrder::where('sales_order_no', $item->sales_order_no)->first();
    //         $quotation = Quotation::find($SalesOrder->quotation_id??'');

    //         $item->invoice_no = $quotation->invoice_no??'';
            
    //         $new_data[] = [
    //             $key+1,
    //             $item->sales_order_no,
    //             $item->invoice_no,
    //             $item->customer_name,
    //             $item->individual_company_name,
    //             $item->address,
    //             $item->total_session,
    //             $item->weekly_freq,
    //             date('d-m-Y', strtotime($item->schedule_date)),
    //             date('h:i A', strtotime(($item->schedule_dt_start_time))) . " - " . date('h:i A', strtotime(($item->schedule_dt_end_time))),
    //         ];
    //     }

    //     $output = [
    //         "draw" => request()->draw,
    //         "recordsTotal" => $data['schedule']->count(),
    //         "recordsFiltered" => $data['schedule']->count(),
    //         "data" => $new_data
    //     ];

    //     echo json_encode($output);
    // }

    public function cleaner_upcoming_schedule_get_table_data(Request $request)
    {
        $cleaner_id = $request->cleaner_id;
        $cleaner_type = $request->cleaner_type;

        $data['schedule'] = DB::table('tble_schedule_employee')
                                ->where('tble_schedule_employee.cleaner_type', $cleaner_type)
                                ->where('tble_schedule_employee.employee_id', $cleaner_id)
                                ->whereDate('tble_schedule_employee.schedule_date', '>=', date('Y-m-d'))
                                ->Join('tble_schedule_details', 'tble_schedule_details.id', '=', 'tble_schedule_employee.tble_schedule_details_id')
                                ->Join('tble_schedule', 'tble_schedule.id', '=', 'tble_schedule_employee.tble_schedule_id')
                                ->select('tble_schedule.*', 'tble_schedule_details.schedule_date as schedule_date', 'tble_schedule_details.startTime as schedule_dt_start_time', 'tble_schedule_details.endTime as schedule_dt_end_time', 'tble_schedule_employee.employee_id as new_employee_id')
                                ->orderBy('tble_schedule_employee.schedule_date', 'asc')
                                ->get();

        $new_data = [];

        foreach($data['schedule'] as $key => $item)
        {
            $customer = Crm::find($item->customer_id);

            if($customer)
            {
                $item->customer_name = $customer->customer_name;
                $item->individual_company_name = $customer->individual_company_name;
            }
            else
            {
                $item->customer_name = "";
                $item->individual_company_name = "";
            }

            // invoice no

            $SalesOrder = SalesOrder::where('sales_order_no', $item->sales_order_no)->first();
            $quotation = Quotation::find($SalesOrder->quotation_id??'');

            $item->invoice_no = $quotation->invoice_no??'';
            
            $new_data[] = [
                $key+1,
                $item->sales_order_id,
                $item->invoice_no,
                $item->customer_name,
                $item->individual_company_name,
                $item->address,
                $item->total_session,
                $item->weekly_freq,
                date('d-m-Y', strtotime($item->schedule_date)),
                date('h:i A', strtotime(($item->schedule_dt_start_time))) . " - " . date('h:i A', strtotime(($item->schedule_dt_end_time))),
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['schedule']->count(),
            "recordsFiltered" => $data['schedule']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    // cleaner_past_schedule_get_table_data

    // public function cleaner_past_schedule_get_table_data(Request $request)
    // {
    //     $cleaner_id = $request->cleaner_id;
    //     $cleaner_type = $request->cleaner_type;

    //     $data['schedule'] = ScheduleDetails::where('tble_schedule_details.cleaner_type', $cleaner_type)
    //                                         ->where('tble_schedule_details.employee_id', $cleaner_id)
    //                                         ->whereDate('tble_schedule_details.schedule_date', '<', date('Y-m-d'))
    //                                         ->leftJoin('tble_schedule', 'tble_schedule.id', '=', 'tble_schedule_details.tble_schedule_id')
    //                                         ->select('tble_schedule.*', 'tble_schedule_details.schedule_date as schedule_date', 'tble_schedule_details.startTime as schedule_dt_start_time', 'tble_schedule_details.endTime as schedule_dt_end_time')
    //                                         ->get();
                            
    //     $new_data = [];

    //     foreach($data['schedule'] as $key => $item)
    //     {
    //         $customer = Crm::find($item->customer_id);

    //         if($customer)
    //         {
    //             $item->customer_name = $customer->customer_name;
    //             $item->individual_company_name = $customer->individual_company_name;
    //         }
    //         else
    //         {
    //             $item->customer_name = "";
    //             $item->individual_company_name = "";
    //         };

    //         // invoice no

    //         $SalesOrder = SalesOrder::where('sales_order_no', $item->sales_order_no)->first();
    //         $quotation = Quotation::find($SalesOrder->quotation_id??'');

    //         $item->invoice_no = $quotation->invoice_no??'';
            
    //         $new_data[] = [
    //             $key+1,
    //             $item->sales_order_no,
    //             $item->invoice_no,
    //             $item->customer_name,
    //             $item->individual_company_name,
    //             $item->address,
    //             $item->total_session,
    //             $item->weekly_freq,
    //             date('d-m-Y', strtotime($item->schedule_date)),
    //             date('h:i A', strtotime(($item->schedule_dt_start_time))) . " - " . date('h:i A', strtotime(($item->schedule_dt_end_time))),
    //         ];
    //     }

    //     $output = [
    //         "draw" => request()->draw,
    //         "recordsTotal" => $data['schedule']->count(),
    //         "recordsFiltered" => $data['schedule']->count(),
    //         "data" => $new_data
    //     ];

    //     echo json_encode($output);
    // }

    public function cleaner_past_schedule_get_table_data(Request $request)
    {
        $cleaner_id = $request->cleaner_id;
        $cleaner_type = $request->cleaner_type;

        $data['schedule'] = DB::table('tble_schedule_employee')
                                ->where('tble_schedule_employee.cleaner_type', $cleaner_type)
                                ->where('tble_schedule_employee.employee_id', $cleaner_id)
                                ->whereDate('tble_schedule_employee.schedule_date', '<', date('Y-m-d'))
                                ->Join('tble_schedule_details', 'tble_schedule_details.id', '=', 'tble_schedule_employee.tble_schedule_details_id')
                                ->Join('tble_schedule', 'tble_schedule.id', '=', 'tble_schedule_employee.tble_schedule_id')
                                ->select('tble_schedule.*', 'tble_schedule_details.schedule_date as schedule_date', 'tble_schedule_details.startTime as schedule_dt_start_time', 'tble_schedule_details.endTime as schedule_dt_end_time', 'tble_schedule_employee.employee_id as new_employee_id')
                                ->orderBy('tble_schedule_employee.schedule_date', 'asc')
                                ->get();
                            
        $new_data = [];

        foreach($data['schedule'] as $key => $item)
        {
            $customer = Crm::find($item->customer_id);

            if($customer)
            {
                $item->customer_name = $customer->customer_name;
                $item->individual_company_name = $customer->individual_company_name;
            }
            else
            {
                $item->customer_name = "";
                $item->individual_company_name = "";
            };

            // invoice no

            $SalesOrder = SalesOrder::where('sales_order_no', $item->sales_order_no)->first();
            $quotation = Quotation::find($SalesOrder->quotation_id??'');

            $item->invoice_no = $quotation->invoice_no??'';
            
            $new_data[] = [
                $key+1,
                $item->sales_order_id,
                $item->invoice_no,
                $item->customer_name,
                $item->individual_company_name,
                $item->address,
                $item->total_session,
                $item->weekly_freq,
                date('d-m-Y', strtotime($item->schedule_date)),
                date('h:i A', strtotime(($item->schedule_dt_start_time))) . " - " . date('h:i A', strtotime(($item->schedule_dt_end_time))),
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['schedule']->count(),
            "recordsFiltered" => $data['schedule']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    // cancel job

    public function cancel_job(Request $request)
    {
        // return $request->all();

        $schedule_details_id = $request->schedule_details_id;

        $ScheduleDetails = ScheduleDetails::find($schedule_details_id);

        if($ScheduleDetails)
        {
            $ScheduleDetails->job_status = 3;
            $ScheduleDetails->save();

            $schedule_id = $ScheduleDetails->tble_schedule_id;
            $sales_order_id = $ScheduleDetails->sales_order_id;

            $check_ScheduleDetails = ScheduleDetails::where('tble_schedule_id', $schedule_id)
                                                    ->where('sales_order_id', $sales_order_id)
                                                    ->where('job_status', 0);

            if($check_ScheduleDetails->exists())
            {
                $check_ScheduleDetails_2 = ScheduleDetails::where('tble_schedule_id', $schedule_id)
                                            ->where('sales_order_id', $sales_order_id)
                                            ->whereIn('job_status', [1, 2]);

                if($check_ScheduleDetails_2->exists())
                {
                    $update_data = [
                        'job_status' => 1
                    ];
                }
                else
                {
                    $update_data = [
                        'job_status' => 0
                    ];
                }              
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

            // log data store start

            LogController::store('schedule', 'Job cancelled', $schedule_id);

            // log data store end

            return response()->json(['status'=>'success', 'message'=>'Job Cancelled']);
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Data not found']);
        }
    }

    // reset job

    public function reset_job(Request $request)
    {
        // return $request->all();

        $schedule_details_id = $request->schedule_details_id;

        $ScheduleDetails = ScheduleDetails::find($schedule_details_id);

        if($ScheduleDetails)
        {
            $ScheduleDetails->job_status = 0;

            if($request->action_type == "complete_reset")
            {
                $ScheduleDetails->manually_completed = 0;
            }

            $ScheduleDetails->save();

            $schedule_id = $ScheduleDetails->tble_schedule_id;
            $sales_order_id = $ScheduleDetails->sales_order_id;

            $check_ScheduleDetails = ScheduleDetails::where('tble_schedule_id', $schedule_id)
                                                    ->where('sales_order_id', $sales_order_id)
                                                    ->where('job_status', 0);

            if($check_ScheduleDetails->exists())
            {
                $check_ScheduleDetails_2 = ScheduleDetails::where('tble_schedule_id', $schedule_id)
                                            ->where('sales_order_id', $sales_order_id)
                                            ->whereIn('job_status', [1, 2]);

                if($check_ScheduleDetails_2->exists())
                {
                    $update_data = [
                        'job_status' => 1
                    ];
                }
                else
                {
                    $update_data = [
                        'job_status' => 0
                    ];
                }              
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

            // log data store start

            LogController::store('schedule', 'Job Reset', $schedule_id);

            // log data store end

            return response()->json(['status'=>'success', 'message'=>'Job Reset']);
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Data not found']);
        }
    }

    // complete job

    public function complete_job(Request $request)
    {
        // return $request->all();

        $schedule_details_id = $request->schedule_details_id;

        $ScheduleDetails = ScheduleDetails::find($schedule_details_id);

        if($ScheduleDetails)
        {
            $ScheduleDetails->job_status = 2;
            $ScheduleDetails->manually_completed = 1;
            $ScheduleDetails->save();

            $schedule_id = $ScheduleDetails->tble_schedule_id;
            $sales_order_id = $ScheduleDetails->sales_order_id;

            $check_ScheduleDetails = ScheduleDetails::where('tble_schedule_id', $schedule_id)
                                                    ->where('sales_order_id', $sales_order_id)
                                                    ->where('job_status', 0);

            if($check_ScheduleDetails->exists())
            {
                $check_ScheduleDetails_2 = ScheduleDetails::where('tble_schedule_id', $schedule_id)
                                            ->where('sales_order_id', $sales_order_id)
                                            ->whereIn('job_status', [1, 2]);

                if($check_ScheduleDetails_2->exists())
                {
                    $update_data = [
                        'job_status' => 1
                    ];
                }
                else
                {
                    $update_data = [
                        'job_status' => 0
                    ];
                }              
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

            // log data store start

            LogController::store('schedule', 'Manually Job Completed', $schedule_id);

            // log data store end

            return response()->json(['status'=>'success', 'message'=>'Job Completed']);
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Data not found']);
        }
    }
}
