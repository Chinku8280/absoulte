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
use DateTime;
use Google\Service\Monitoring\Custom;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class ScheduleConteroller extends Controller
{
    function index()
    {
        $users = ScheduleModel::select('tble_schedule.*', 'zs.zone_color')
            ->selectRaw('LEFT(tble_schedule.postalCode, 2) as shortPostalCode')
            ->leftJoin('zone_settings as zs', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(tble_schedule.postalCode, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
            })
            ->where('tble_schedule.status', 1)
            ->get();

        $order = SalesOrder::where('status', 0)->pluck('customer_id');
        $customer = \DB::table('customers')->whereIn('id', $order)->pluck('id');

        // $unassign_job = ServiceAddress::select('service_address.*', 'zs.zone_color', 'customers.customer_name')
        //     ->selectRaw('LEFT(service_address.postal_code, 2) as shortPostalCode')
        //     ->leftJoin('zone_settings as zs', function ($join) {
        //         $join->whereRaw('FIND_IN_SET(LEFT(service_address.postal_code, 2), REPLACE(zs.postal_code, " ", ""))');
        //     })
        //     ->leftJoin('customers', 'service_address.customer_id', '=', 'customers.id')
        //     ->whereIn('service_address.customer_id', $customer)
        //     ->where('service_address.default_address', '=', 1)
        //     ->get();

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

        // $unassign_job = ServiceAddress::select(
        //     'service_address.*',
        //     'zs.zone_color',
        //     'customers.customer_name',
        //     'customers.customer_type',
        //     'customers.individual_company_name',
        //     'sales_order.quotation_id',
        //     'quotations.schedule_date',
        //     'quotations.time_of_cleaning',
        //     'sales_order.id as sales_order_id',
        // )
        //     ->selectRaw('LEFT(service_address.postal_code, 2) as shortPostalCode')
        //     ->leftJoin('zone_settings as zs', function ($join) {
        //         $join->whereRaw('FIND_IN_SET(LEFT(service_address.postal_code, 2), REPLACE(zs.postal_code, " ", ""))');
        //     })
        //     ->leftJoin('customers', 'service_address.customer_id', '=', 'customers.id')
        //     ->leftJoin('sales_order', function ($join) {
        //         $join->on('service_address.customer_id', '=', 'sales_order.customer_id')
        //             ->where('sales_order.status', '=', 0);
        //     })
        //     ->leftJoin('quotations', 'sales_order.quotation_id', '=', 'quotations.id')
        //     ->whereIn('service_address.customer_id', $customer)
        //     ->where('service_address.default_address', '=', 1)
        //     ->leftJoin('quotation_services_details as qsd', function ($join) {
        //         $join->on('quotations.id', '=', 'qsd.quotation_id');
        //     })
        //     ->leftJoin('services', 'qsd.service_id', '=', 'services.id')
        //     ->distinct() // Use DISTINCT to avoid duplicate records
        //     ->get();

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


        $employees = DB::table('xin_employees')
            ->where('user_role_id', 10)
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.*', 'xin_team.team_name', 'zone_settings.zone_color')
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

        //dd($employeesWithFullNames);

        $resources = [];
        $events = [];
        if (empty($users)) {
            // Handle the case when there's no data in tble_Schedule
            foreach ($employeesWithFullNames as $employee) {
                $user_id = $employee->user_id;
                $full_name = $employee->full_name;
                $color = isset($employee->zone_color) ? $employee->zone_color : null;
                $title = $employee->team_name ?? $full_name;

                $resources[] = [
                    'id' => $user_id,
                    'title' => $title,
                    'color' => $color,
                ];
            }
        } else {
            // Merge resource data when $users is not empty
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
                ];
            }
        }

        // return $resources;
        
        $ServiceDetails = [];
        foreach ($users as $user) {
            $days = explode(',', $user->days);

            foreach ($days as $day) {
                $formattedDay = date('Y-m-d', strtotime($day));
                $startDateTimeString = $formattedDay . 'T' . $user->startTime;
                $endDateTimeString = $formattedDay . 'T' . $user->endTime;

                $customer = Crm::find($user->customer_id);
                $serviceDetails = SalesOrder::where('sales_order_no', $user->sales_order_no)->first();
                $leadId = $serviceDetails->lead_id ?? null;
                $quotationId = $serviceDetails->quotation_id ?? null;

                $ServiceDetails = [];
                if ($leadId == null) {
                    $quotationServiceDetails = QuotationServiceDetail::where('quotation_id', $quotationId)->get();
                    $ServiceDetails = $quotationServiceDetails->toArray();
                } else {
                    $leadServiceDetails = LeadServices::where('lead_id', $leadId)->get();
                    $ServiceDetails = $leadServiceDetails->toArray();
                }

                $start = $user->startTime;
                $end = $user->endTime;
                $timeRange = date("h:i A", strtotime($start)) . " to " . date("h:i A", strtotime($end));


                $fullName = '';

                if ($user->cleaner_type === 'individual') {
                    $employee = DB::table('xin_employees')
                        ->where('user_id', $user->employee_id)
                        ->first();

                    if ($employee) {
                        $fullName = $employee->first_name . ' ' . $employee->last_name;
                    }
                } elseif ($user->cleaner_type === 'team') {
                    $team = DB::table('xin_team')
                        ->where('employee_id', $user->employee_id)
                        ->first();

                    if ($team) {
                        $fullName = $team->team_name;
                    }
                }
                $events[] = [
                    'id' => $user->id,
                    'resourceId' => $user->employee_id,
                    'title' => $customer->customer_name ?? null,
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
                    'cleaningDate' => $user->startDate,
                    'cleaningTime' => $timeRange,
                    'sales_order_id' => $user->sales_order_id,
                ];
            }
        }
        // dd($events);

        // for assign cleaner

        $ac_get_team = DB::table('xin_team')->get();
        $ac_employeeNames = $this->getEmployeeNames2($ac_get_team);
        
        $ac_users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color')
            ->where('xin_employees.user_role_id', 10)
            ->get();

        return view('admin.scedule.index', compact('events', 'resources', 'unassign_job', 'ServiceDetails', 'ac_get_team', 'ac_users', 'ac_employeeNames'));
    }

    private function getEmployeeNames2($teams)
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

       // dd($employeeNames);
        return $employeeNames;
    }

    
    function create(Request $request)
    {
        // return $request->all();

        $sales = SalesOrder::where('sales_order_no', $request->input('sales_order_no'))->first();

        // $serviceDetails = LeadServices::where('lead_id', $sales->lead_id)->first();
        $serviceDetails = QuotationServiceDetail::where('quotation_id', $sales->quotation_id)->first();

        // $service = Services::where('id',$serviceDetails->service_id)->first();

        // $scheduleDetails = ScheduleModel::where('service_id', $serviceDetails->service_id)->get();
        // echo"<pre>"; print_r(count($scheduleDetails)); exit;

        // if(count($scheduleDetails) < $service->man_power_required){

        // schedule start

        $schedule = new ScheduleModel();
        $schedule->sales_order_no = $request->input('sales_order_no');
        $schedule->customer_id = $request->input('customer_id');
        $schedule->service_id = $serviceDetails->service_id ?? 0;
        $cleanerType = $request->input('cleaner_type');

        if ($cleanerType == 'team') {
            $schedule->cleaner_type = $request->input('cleaner_type');
            $schedule->name = $request->input('team_id');
            $schedule->employee_id = $request->input('team_id');
        } else if ($cleanerType == 'individual') {
            $schedule->cleaner_type = $request->input('cleaner_type');
            $schedule->name = $request->input('cleaner_id');
            $schedule->employee_id = $request->input('cleaner_id');
        }
        $schedule->startDate = $request->input('startDate');
        $schedule->endDate = $request->input('endDate');
        $schedule->postalCode = $request->input('postalCode');
        $schedule->unitNo = $request->input('unitNo');
        $schedule->address = $request->input('address');
        $schedule->total_session = $request->input('total_session');
        $schedule->weekly_freq = $request->input('weekly_freq');
        $schedule->startTime = $request->input('startTime');
        $schedule->endTime = $request->input('endTime');
        $selectedDays = $request->input('days');
        if ($selectedDays) {
            $schedule->selected_days = implode(',', $selectedDays);
        }
        $dates = explode(', ', $request->input('datepick'));
        $formattedDates = [];

        foreach ($dates as $date) {
            $dateObj = DateTime::createFromFormat('d/m/Y', $date);
            $formattedDate = $dateObj->format('m/d/Y');
            $formattedDates[] = $formattedDate;
        }
        $formattedDatesString = implode(', ', $formattedDates);
        $schedule->days = $formattedDatesString;
        $schedule->customer_remark = $request->input('customer_remark');
        $schedule->service_type =  $request->input('service_type');
        $schedule->status = '1';
        $schedule->save();

        // schedule end

        // schedule details start

        ScheduleDetails::where('sales_order_no', $request->input('sales_order_no'))->delete();

        foreach ($dates as $date) 
        {
            $dateObj = DateTime::createFromFormat('d/m/Y', $date);
            $formattedDate = $dateObj->format('Y-m-d');

            $schedule_details = new ScheduleDetails();
            $schedule_details->tble_schedule_id = $schedule->id;
            $schedule_details->sales_order_no = $request->input('sales_order_no');
            $schedule_details->schedule_date = $formattedDate;
            $schedule_details->save();
        }

        // schedule details end

        // sales order start

        $salesOrder = SalesOrder::where('sales_order_no', $request->input('sales_order_no'))->first();
        if ($salesOrder) {
            $salesOrder->status = '1';
            $salesOrder->save();
        }

        // sales order end

        return redirect()->back();

        // return redirect()->route('salesOrder');

        // }
        // else{

        //     return "Service Plan is Expired";
        // }

        // return response()->json(['message' => 'Form data saved successfully']);

    }

    public function fetch($id)
    {
        $users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color')
            ->where('xin_employees.user_role_id', 10)
            ->get();
        $get_team = DB::table('xin_team')->get();
        $employeeNames = $this->getEmployeeNames($get_team);        
        $cleaner_data = ScheduleModel::find($id);
        if ($cleaner_data) {

            $days_arr = explode(', ', $cleaner_data->days);
            $new_days_arr = [];
            foreach($days_arr as $key => $item)
            {
                $dateObj = DateTime::createFromFormat('d/m/Y', $item);
                $formattedDate = $dateObj->format('m/d/Y');
                $new_days_arr[] = $formattedDate;
            }

            $cleaner_data->new_days = implode(', ', $new_days_arr);

            $cleaner_data->selected_days = explode(',', $cleaner_data->selected_days);
        }

        return view('admin.salesOrder.edit', compact('cleaner_data', 'users', 'get_team', 'employeeNames'));
    }

    public function getDataFromSchedule($id)
    {
        $users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color')
            ->where('xin_employees.user_role_id', 10)
            ->get();
        $get_team = DB::table('xin_team')->get();
        $employeeNames = $this->getEmployeeNames($get_team);
        $cleaner_data = ScheduleModel::find($id);
        if ($cleaner_data) {
            $cleaner_data->selected_days = explode(',', $cleaner_data->selected_days);
        }

        $cleaner_data->customer_name = Crm::find($cleaner_data->customer_id)->customer_name;
        $cleaner_data->company_name = Crm::find($cleaner_data->customer_id)->individual_company_name;
        $cleaner_data->customer_type = Crm::find($cleaner_data->customer_id)->customer_type;

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

        // schedule start

        $cleaner_data = ScheduleModel::find($id);

        $cleaner_data->sales_order_no = $request->input('sales_order_no');
        $cleaner_data->customer_id = $request->input('customer_id');
        $cleaner_data->employee_id = $request->input('empId');
        $cleanerType = $request->input('cleaner_type');

        if ($cleanerType == 'team') {
            $cleaner_data->cleaner_type = $request->input('cleaner_type');
            $cleaner_data->employee_id = $request->input('team_id');
            $cleaner_data->name = $request->input('team_id');
        } else if ($cleanerType == 'individual') {
            $cleaner_data->cleaner_type = $request->input('cleaner_type');
            $cleaner_data->name = $request->input('cleaner_id');
            $cleaner_data->employee_id = $request->input('cleaner_id');
        }
        $cleaner_data->startDate = $request->input('startDate');
        $cleaner_data->endDate = $request->input('endDate');
        $cleaner_data->postalCode = $request->input('postalCode');
        $cleaner_data->unitNo = $request->input('unitNo');
        $cleaner_data->address = $request->input('address');
        $cleaner_data->total_session = $request->input('total_session');
        $cleaner_data->weekly_freq = $request->input('weekly_freq');
        $cleaner_data->startTime = $request->input('startTime');
        $cleaner_data->endTime = $request->input('endTime');
        // $cleaner_data->days = $request->input('datepick');
        $dates = explode(', ', $request->input('datepick'));
        $formattedDates = [];
        foreach ($dates as $date) {
            $dateObj = DateTime::createFromFormat('d/m/Y', $date);
            $formattedDate = $dateObj->format('m/d/Y');
            $formattedDates[] = $formattedDate;
        }
        $formattedDatesString = implode(', ', $formattedDates);
        $cleaner_data->days = $formattedDatesString;
        $cleaner_data->customer_remark = $request->input('customer_remark');
        $selectedDays = $request->input('days');
        if ($selectedDays) {
            $cleaner_data->selected_days = implode(',', $selectedDays);
        }
        $cleaner_data->save();

        // schedule end

        // schedule details start

        ScheduleDetails::where('tble_schedule_id', $id)
                        ->where('sales_order_no', $request->input('sales_order_no'))
                        ->delete();

        foreach ($dates as $date) 
        {
            $dateObj = DateTime::createFromFormat('d/m/Y', $date);
            $formattedDate = $dateObj->format('Y-m-d');

            $schedule_details = new ScheduleDetails();
            $schedule_details->tble_schedule_id = $id;
            $schedule_details->sales_order_no = $request->input('sales_order_no');
            $schedule_details->schedule_date = $formattedDate;
            $schedule_details->save();
        }

        // schedule details end

        return redirect()->back();
    }

    public function scheduleUpdate(Request $request, $id)
    {
        // $content = $request->getContent();
        //dd($request->all());

        // schedule start

        $cleaner_data = ScheduleModel::find($id);

        $cleaner_data->sales_order_no = $request->input('sales_order_no');
        $cleaner_data->customer_id = $request->input('customer_id');
        $cleanerType = $request->input('cleaner_type');

        if ($cleanerType == 'team') {
            // Set values for team
            $cleaner_data->cleaner_type = $request->input('cleaner_type');
            $cleaner_data->employee_id = $request->input('team_id');
            $cleaner_data->name = $request->input('team_id');
        } else if ($cleanerType == 'individual') {
            // Set values for individual
            $cleaner_data->cleaner_type = $request->input('cleaner_type');
            $cleaner_data->name = $request->input('cleaner_id');
            $cleaner_data->employee_id = $request->input('cleaner_id');
        }
        $cleaner_data->startDate = $request->input('startDate');
        $cleaner_data->endDate = $request->input('endDate');
        $cleaner_data->postalCode = $request->input('postalCode');
        $cleaner_data->unitNo = $request->input('unitNo');
        $cleaner_data->address = $request->input('address');
        $cleaner_data->total_session = $request->input('total_session');
        $cleaner_data->weekly_freq = $request->input('weekly_freq');
        $cleaner_data->startTime = $request->input('startTime');
        $cleaner_data->endTime = $request->input('endTime');
        //$cleaner_data->days = $request->input('cleaning_datePick');
        $dates = explode(', ', $request->input('cleaning_datePick'));
        $formattedDates = [];

        foreach ($dates as $date) {
            $dateObj = DateTime::createFromFormat('d/m/Y', $date);
            $formattedDate = $dateObj->format('m/d/Y');
            $formattedDates[] = $formattedDate;
        }
        $formattedDatesString = implode(', ', $formattedDates);
        $cleaner_data->days = $formattedDatesString;
        $cleaner_data->customer_remark = $request->input('customer_remark');
        $selectedDays = $request->input('days');
        if ($selectedDays) {
            //  $cleaner_data->selected_days = implode(',', $selectedDays);
            $cleaner_data->selected_days = implode(',', json_decode($request->input('days')));
        }
        $cleaner_data->save();

        // schedule start

        // schedule details start

        ScheduleDetails::where('tble_schedule_id', $id)
                        ->where('sales_order_no', $request->input('sales_order_no'))
                        ->delete();

        foreach ($dates as $date) 
        {
            $dateObj = DateTime::createFromFormat('d/m/Y', $date);
            $formattedDate = $dateObj->format('Y-m-d');

            $schedule_details = new ScheduleDetails();
            $schedule_details->tble_schedule_id = $id;
            $schedule_details->sales_order_no = $request->input('sales_order_no');
            $schedule_details->schedule_date = $formattedDate;
            $schedule_details->save();
        }

        // schedule details end

        return redirect()->back();
    }

    public function eventUpdate(Request $request)
    {
        // return $request->all();

        // dd($request->all());

        $emp = $request->data['empId'];      

        if ($request->data['flag'] == 'updateTime') {
            $schedule_id = $request->data['schedule_id'];

            $timeObj = \DateTime::createFromFormat('h:ia', $request->data['startTime']);
            $startTime = $timeObj->format('H:i:s');

            $timeObj1 = \DateTime::createFromFormat('h:ia', $request->data['endTime']);
            $endTime = $timeObj1->format('H:i:s');

            // $ScheduleModel = ScheduleModel::where('employee_id', $emp)->first();
            $ScheduleModel = ScheduleModel::where('id', $schedule_id)->first();
            $ScheduleModel->startTime =  $startTime;
            $ScheduleModel->endTime =  $endTime;
            $ScheduleModel->save();
        } 
        else {
            $resourceType = $request->data['resourceType'];
            if ($resourceType == 'individual') {
                $cleanerType = DB::table('xin_employees')->where('user_id', $emp)->first();
                $cleanrName = $cleanerType->first_name . ' ' . $cleanerType->last_name;
            } else {
                $cleanrName = DB::table('xin_team')->where('team_id', $emp)->pluck('team_name')->first();
            }

            $sales_order_id = $request->data['sales_order_id'];
            $customerID = $request->data['customerID'];
            $days = $request->data['days'];
            $dateTime = Carbon::parse($request->data['startTime']);
            $startTime = $dateTime->format('H:i:s');
            $endTime = date('H:i:s', strtotime($startTime.'+1 hour'));

            $date = Carbon::parse($request->data['dataDate']);
            $formattedDate = $date->format('Y-m-d');

            // $custAdd   = ServiceAddress::where('customer_id', $customerID)->first();
        
            $SalesOrder = SalesOrder::where('id', $sales_order_id)->first();
            $serviceDetail = json_decode($request->data['Service_Details'], true)[0];

            $quotation = Quotation::find($SalesOrder->quotation_id);
            $custAdd = ServiceAddress::where('id', $quotation->service_address ?? '')->first();

            // schedule start

            $ScheduleModel = new ScheduleModel();
            $ScheduleModel->customer_id = $customerID;
            $ScheduleModel->employee_id = $emp;
            $ScheduleModel->startDate = $formattedDate;
            $ScheduleModel->endDate = $formattedDate;
            $ScheduleModel->startTime = $startTime;
            $ScheduleModel->endTime = $endTime;
            // $ScheduleModel->days = $days;
            $ScheduleModel->days = $date->format('m/d/Y');
            $ScheduleModel->sales_order_no = $SalesOrder->sales_order_no;
            $ScheduleModel->sales_order_id = $sales_order_id;
            $ScheduleModel->postalCode = $custAdd->postal_code ?? '';
            $ScheduleModel->unitNo = $custAdd->unit_number ?? '';
            $ScheduleModel->address = $custAdd->address ?? '';
            $ScheduleModel->name = $cleanrName;
            $ScheduleModel->cleaner_type = $resourceType;
            $ScheduleModel->status = 1;
            $ScheduleModel->service_id = $serviceDetail['id'];
            $ScheduleModel->total_session = $serviceDetail['total_session'];
            $ScheduleModel->weekly_freq = $serviceDetail['weekly_freq'];

            $ScheduleModel->save();

            // schedule end

            // sales order start

            $order = SalesOrder::where(['id' => $sales_order_id, 'status' => 0])->first();
            if ($order) {
                $order->status = 1;
                $order->save();
            }

            // sales order end
        }


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

    //     $data['schedule'] = ScheduleModel::where('cleaner_type', $cleaner_type)
    //                                         ->where('employee_id', $cleaner_id)
    //                                         ->whereIn('job_status', [0, 1])
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

    //         if($item->job_status == 0)
    //         {
    //             $job_status = "Pending";
    //         }
    //         else if($item->job_status == 1)
    //         {
    //             $job_status = "Work in Progress";
    //         }
    //         if($item->job_status == 2)
    //         {
    //             $job_status = "Completed";
    //         }

    //         $new_days_arr = [];
    //         $days_arr = explode(', ', $item->days);
    //         foreach($days_arr as $list)
    //         {
    //             $datetime = DateTime::createFromFormat('m/d/Y', $list); 
    //             $new_days_arr[] = $datetime->format('d/m/Y');
    //         }

    //         $item->new_days = implode(', ', $new_days_arr);
            
    //         $new_data[] = [
    //             $key+1,
    //             $item->sales_order_no,
    //             $item->customer_name,
    //             $item->individual_company_name,
    //             $item->address,
    //             $item->total_session,
    //             $item->weekly_freq,
    //             $item->new_days,
    //             date('h:i A', strtotime(($item->startTime))) . " - " . date('h:i A', strtotime(($item->endTime))),
    //             $job_status
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

        $schedule = ScheduleModel::where('cleaner_type', $cleaner_type)
                                    ->where('employee_id', $cleaner_id)
                                    ->get();

        $schedule_id_arr = [];

        foreach($schedule as $item)
        {
            $schedule_id_arr[] = $item->id;
        }

        $data['schedule'] = ScheduleDetails::whereIn('tble_schedule_details.tble_schedule_id', $schedule_id_arr)
                                            ->whereDate('tble_schedule_details.schedule_date', '>=', date('Y-m-d'))
                                            ->leftJoin('tble_schedule', 'tble_schedule.id', '=', 'tble_schedule_details.tble_schedule_id')
                                            ->select('tble_schedule.*', 'tble_schedule_details.schedule_date as schedule_date')
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

            $new_days_arr = [];
            $days_arr = explode(', ', $item->days);
            foreach($days_arr as $list)
            {
                $datetime = DateTime::createFromFormat('m/d/Y', $list); 
                $new_days_arr[] = $datetime->format('d/m/Y');
            }

            $item->new_days = implode(', ', $new_days_arr);

            // invoice no

            $SalesOrder = SalesOrder::where('sales_order_no', $item->sales_order_no)->first();
            $quotation = Quotation::find($SalesOrder->quotation_id??'');

            $item->invoice_no = $quotation->invoice_no??'';
            
            $new_data[] = [
                $key+1,
                $item->sales_order_no,
                $item->invoice_no,
                $item->customer_name,
                $item->individual_company_name,
                $item->address,
                $item->total_session,
                $item->weekly_freq,
                date('d-m-Y', strtotime($item->schedule_date)),
                date('h:i A', strtotime(($item->startTime))) . " - " . date('h:i A', strtotime(($item->endTime))),
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

    //     $data['schedule'] = ScheduleModel::where('cleaner_type', $cleaner_type)
    //                                         ->where('employee_id', $cleaner_id)
    //                                         ->where('job_status', 2)
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

    //         if($item->job_status == 0)
    //         {
    //             $job_status = "Pending";
    //         }
    //         else if($item->job_status == 1)
    //         {
    //             $job_status = "Work in Progress";
    //         }
    //         if($item->job_status == 2)
    //         {
    //             $job_status = "Completed";
    //         }

    //         $new_days_arr = [];
    //         $days_arr = explode(', ', $item->days);
    //         foreach($days_arr as $list)
    //         {
    //             $datetime = DateTime::createFromFormat('m/d/Y', $list); 
    //             $new_days_arr[] = $datetime->format('d/m/Y');
    //         }

    //         $item->new_days = implode(', ', $new_days_arr);
            
    //         $new_data[] = [
    //             $key+1,
    //             $item->sales_order_no,
    //             $item->customer_name,
    //             $item->individual_company_name,
    //             $item->address,
    //             $item->total_session,
    //             $item->weekly_freq,
    //             $item->new_days,
    //             date('h:i A', strtotime(($item->startTime))) . " - " . date('h:i A', strtotime(($item->endTime))),
    //             $job_status
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

        $schedule = ScheduleModel::where('cleaner_type', $cleaner_type)
                                    ->where('employee_id', $cleaner_id)
                                    ->get();

        $schedule_id_arr = [];

        foreach($schedule as $item)
        {
            $schedule_id_arr[] = $item->id;
        }

        $data['schedule'] = ScheduleDetails::whereIn('tble_schedule_details.tble_schedule_id', $schedule_id_arr)
                                            ->whereDate('tble_schedule_details.schedule_date', '<', date('Y-m-d'))
                                            ->leftJoin('tble_schedule', 'tble_schedule.id', '=', 'tble_schedule_details.tble_schedule_id')
                                            ->select('tble_schedule.*', 'tble_schedule_details.schedule_date as schedule_date')
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

            $new_days_arr = [];
            $days_arr = explode(', ', $item->days);
            foreach($days_arr as $list)
            {
                $datetime = DateTime::createFromFormat('m/d/Y', $list); 
                $new_days_arr[] = $datetime->format('d/m/Y');
            }

            $item->new_days = implode(', ', $new_days_arr);

            // invoice no

            $SalesOrder = SalesOrder::where('sales_order_no', $item->sales_order_no)->first();
            $quotation = Quotation::find($SalesOrder->quotation_id??'');

            $item->invoice_no = $quotation->invoice_no??'';
            
            $new_data[] = [
                $key+1,
                $item->sales_order_no,
                $item->invoice_no,
                $item->customer_name,
                $item->individual_company_name,
                $item->address,
                $item->total_session,
                $item->weekly_freq,
                date('d-m-Y', strtotime($item->schedule_date)),
                date('h:i A', strtotime(($item->startTime))) . " - " . date('h:i A', strtotime(($item->endTime))),
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
}
