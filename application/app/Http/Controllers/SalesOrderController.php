<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\LeadServices;
use App\Models\Company;
use App\Models\Crm;
use App\Models\LanguageSpoken;
use App\Models\LeadPrice;
use App\Models\LeadSchedule;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\ServiceAddress;
use App\Models\Territory;
use App\Models\TermCondition;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ScheduleModel;
use App\Models\Services;
use App\Models\Tax;
use App\Models\User;
use App\Models\ZoneSetting;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Svg\Tag\Rect;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrder = SalesOrder::select(
            'sales_order.*',          
            'customers.id as custm_id',
            'customers.customer_name',
            'customers.individual_company_name',
            'customers.email',
            'customers.mobile_number',
            'customers.customer_type',
            'customers.renewal',
            'customers.pending_invoice_limit',
            \DB::raw('(SELECT MAX(id) FROM tble_schedule WHERE tble_schedule.sales_order_no = sales_order.sales_order_no) as tble_schedule_id')
        )
        ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
        ->orderBy('sales_order.created_at', 'desc')
        ->get();

        foreach ($salesOrder as $key => $value) 
        {
            $quotation = Quotation::find($value->quotation_id);

            // total amount
            $value->total_amount = $quotation->grand_total ?? 0;

            // service address
            $service_address = ServiceAddress::find($quotation->service_address ?? '');  
            $value->service_address = $service_address->address??'' .", ". $service_address->unit_number??'';
            
            // remarks
            $schedule = ScheduleModel::where('sales_order_id', $value->id)->first();

            if(!empty($schedule->remarks))
            {
                $value->remarks = $schedule->remarks ?? '';
            }
            else
            {
                $value->remarks = $value->remarks ?? $quotation->remarks;
            }

            // Created By
            $value->created_by = $value->created_by_name;

            // balance job
            $value->balance_job = count(ScheduleDetails::where('sales_order_id', $value->id)
                                                ->where('job_status', 0)
                                                ->get());

            // unassigned date

            $unassigned_ScheduleDetails = ScheduleDetails::where('sales_order_id', $value->id)
                            ->orderByRaw('ABS(DATEDIFF(schedule_date, ?)) ASC', date('Y-m-d'))
                            ->whereNull('employee_id')
                            ->first();

            $value->unassigned_date = $unassigned_ScheduleDetails->schedule_date ?? '';   

            // pending invoice

            $value->total_pending_invoice = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
                                                ->where('customer_id', $value->id)
                                                ->WhereNotNull('invoice_no')
                                                ->count();  
                    
            // status order
            if($value->status == 0)
            {
                $value->status_order = 1;
            }                              
            else if($value->status == 1)
            {
                $value->status_order = 3;
            }  
            else if($value->status == 2)
            {
                $value->status_order = 2;
            }   
        }

        // Sort the sales orders first by unassigned date, then by status
        $salesOrder = $salesOrder->sortBy(function ($item) {
            // Then sort by status
            // return $item->status ?? PHP_INT_MAX;
            return $item->status_order ?? PHP_INT_MAX;
        })->sortBy(function ($item) {
            // Sort by unassigned date
            return strtotime($item->unassigned_date) ?: PHP_INT_MAX;
        })->values();   
        
        // return $salesOrder;

        $salesOrderGroupedByCompany = $salesOrder->groupBy('company_id')->map(function ($orders) {
            return $orders->values();
        });
        
        // return $salesOrderGroupedByCompany;


        // get user role id
        $roles_id = $this->get_user_roles_id();

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

        $company = Company::get();
        
        return view('admin.salesOrder.index', compact('users', 'get_team', 'salesOrder', 'salesOrderGroupedByCompany', 'company', 'employeeNames'));
    }

    public static function getEmployeeNames($teams)
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

    public static function get_user_roles_id()
    {
        $xin_user_roles = DB::table('xin_user_roles')
                            ->whereIn('role_name', ['Cleaner', 'Driver', 'Superviser'])
                            ->pluck('role_id')
                            ->toArray();

        return $xin_user_roles;
    }

    public function filter_sales_order($temp = "", $year = "")
    {
        $salesOrder = SalesOrder::select(
            'sales_order.*',          
            'customers.id as custm_id',
            'customers.customer_name',
            'customers.individual_company_name',
            'customers.email',
            'customers.mobile_number',
            'customers.customer_type',
            'customers.renewal',
            'customers.pending_invoice_limit',
            \DB::raw('(SELECT MAX(id) FROM tble_schedule WHERE tble_schedule.sales_order_no = sales_order.sales_order_no) as tble_schedule_id')
        )
        ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
        ->orderBy('sales_order.created_at', 'desc');

        if(!empty($temp))
        {
            if($temp == "today")
            {
                $salesOrder = $salesOrder->whereDate('sales_order.created_at', date('Y-m-d'));
            }
            else if($temp == "this-month")
            {
                $salesOrder = $salesOrder->whereBetween('sales_order.created_at', 
                [
                    Carbon::now()->startOfMonth(), 
                    Carbon::now()->endOfMonth()
                ]);
            }
            else if($temp == "unassigned")
            {
                $salesOrder = $salesOrder->where('sales_order.cleaner_assigned_status', 0);
            }
            else if($temp == "partially-assigned")
            {
                $salesOrder = $salesOrder->where('sales_order.cleaner_assigned_status', 2);
            }
        }

        if(!empty($year))
        {
            $salesOrder = $salesOrder->whereYear('sales_order.created_at', $year);
        }

        $salesOrder = $salesOrder->get();

        foreach ($salesOrder as $key => $value) 
        {
            $quotation = Quotation::find($value->quotation_id);

            // total amount
            $value->total_amount = $quotation->grand_total ?? 0;

            // service address
            $service_address = ServiceAddress::find($quotation->service_address ?? '');  
            $value->service_address = $service_address->address??'' .", ". $service_address->unit_number??'';

            // remarks
            $schedule = ScheduleModel::where('sales_order_id', $value->id)->first();

            if(!empty($schedule->remarks))
            {
                $value->remarks = $schedule->remarks ?? '';
            }
            else
            {
                $value->remarks = $value->remarks ?? $quotation->remarks;
            }

            // Created By
            $value->created_by = $value->created_by_name;

            // balance job
            $value->balance_job = count(ScheduleDetails::where('sales_order_id', $value->id)
                                                ->where('job_status', 0)
                                                ->get());

            // unassigned date

            $unassigned_ScheduleDetails = ScheduleDetails::where('sales_order_id', $value->id)
                                    ->orderByRaw('ABS(DATEDIFF(schedule_date, ?)) ASC', date('Y-m-d'))
                                    ->whereNull('employee_id')
                                    ->first();

            $value->unassigned_date = $unassigned_ScheduleDetails->schedule_date ?? '';   

            // pending invoice

            $value->total_pending_invoice = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
                                                ->where('customer_id', $value->id)
                                                ->WhereNotNull('invoice_no')
                                                ->count();

            // status order

            if($value->status == 0)
            {
                $value->status_order = 1;
            }                              
            else if($value->status == 1)
            {
                $value->status_order = 3;
            }  
            else if($value->status == 2)
            {
                $value->status_order = 2;
            }   
        }

        // Sort the sales orders first by unassigned date, then by status
        $salesOrder = $salesOrder->sortBy(function ($item) {
            // Then sort by status
            // return $item->status ?? PHP_INT_MAX;
            return $item->status_order ?? PHP_INT_MAX;
        })->sortBy(function ($item) {
            // Sort by unassigned date
            return strtotime($item->unassigned_date) ?: PHP_INT_MAX;
        })->values();  

        // return $salesOrder;

        $salesOrderGroupedByCompany = $salesOrder->groupBy('company_id')->map(function ($orders) {
            return $orders->values();
        });

        // return $salesOrderGroupedByCompany;


        // get user role id
        $roles_id = $this->get_user_roles_id();

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
        
        $company = Company::get();

        return view('admin.salesOrder.index', compact('users', 'get_team', 'salesOrder', 'salesOrderGroupedByCompany', 'company', 'employeeNames'));
    }

    public function view(Request $request)
    {
        $salesOrder = SalesOrder::select('sales_order.*', 'customers.customer_name', 'customers.territory', 'customers.email', 'customers.mobile_number', 'customers.customer_type', 'customers.language_spoken', 'customers.individual_company_name', 'customers.status as customer_status')
                            ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
                            ->where('sales_order.id', $request->salesOrderId)
                            ->first();

        $LanguageSpoken = LanguageSpoken::find($salesOrder->language_spoken);
        $salesOrder->language_name = $LanguageSpoken->language_name ?? '';

        $quotation = Quotation::where('id', $salesOrder->quotation_id)->first();

        if($quotation)
        {
            $service_address = ServiceAddress::where('id', $quotation->service_address)->first();
            $billing_address = BillingAddress::where('id', $quotation->billing_address)->first();
        }
        else
        {
            $service_address = "";
            $billing_address = "";
        }

        $company = Company::get();
        $territory = Territory::get();
       
        $serviceDetails = QuotationServiceDetail::where('quotation_id', $salesOrder->quotation_id)->get(); 

        foreach($serviceDetails as $item)
        {
            $get_service_details = Services::find($item->service_id);
            if($get_service_details)
            {
                $item->total_session = $item->total_session ? $item->total_session : $get_service_details->total_session;
            }
        }

        $assign_cleaner = DB::table('tble_schedule')->where('sales_order_no', $salesOrder->sales_order_no)->get();
        $companyDetail = Company::where('id', $quotation->company_id)->first();
        $termCondition = TermCondition::where('company_id', $companyDetail->id)->get();
        $priceInfo = LeadPrice::get();

        // return $salesOrder;

        return view('admin.salesOrder.view', compact('salesOrder', 'companyDetail', 'territory', 'assign_cleaner', 'service_address', 'billing_address', 'company', 'serviceDetails', 'termCondition', 'priceInfo'));
    }

    public function edit(Request $request)
    {
        $salesOrder = SalesOrder::select('sales_order.id', 'sales_order.customer_id', 'sales_order.quotation_id', 'sales_order.sales_order_no', 'sales_order.created_at', 'sales_order.status as sales_status', 'customers.id as custm_id', 'customers.customer_name', 'customers.territory', 'customers.email', 'customers.mobile_number', 'customers.customer_type')
            ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')->first();

        $data = SalesOrder::leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')->where('sales_order.id', $request->salesOrderId)->first();

        $service_address = ServiceAddress::where('customer_id', $salesOrder->customer_id)->first();
        $billing_address = BillingAddress::where('customer_id', $salesOrder->customer_id)->first();

        $company = Company::get();
        $territory = Territory::get();
        $serviceDetails = LeadServices::where('lead_id', $salesOrder->lead_id)->get();

        $assign_cleaner = DB::table('tble_schedule')->where('sales_order_no', $salesOrder->sales_order_no)->get();

        $quotation = Quotation::where('id', $salesOrder->quotation_id)->first();
        $companyDetail = Company::where('id', $quotation->company_id)->first();
        // echo"<pre>"; print_r($companyDetail); exit;
        $termCondition = TermCondition::where('company_id', $companyDetail->id)->get();
        $priceInfo = LeadPrice::get();
        return view('admin.salesOrder.edit-salesOrder', compact('salesOrder', 'companyDetail', 'territory', 'assign_cleaner', 'service_address', 'data', 'billing_address', 'company', 'serviceDetails', 'termCondition', 'priceInfo'));
    }

    // assign cleaner on sales order

    public function getAddress($id)
    {
        $sales_order = SalesOrder::where('id', $id)->first();

        if ($sales_order) 
        {
            $quotation_id = $sales_order->quotation_id;
            $customer_data = Crm::where('id', $sales_order->customer_id)->first();
            
            $session = QuotationServiceDetail::where('quotation_id', $quotation_id)->where('service_type', 'service')->get();
            foreach ($session as $item) 
            {
                $get_service_details = Services::find($item->service_id);
                if ($get_service_details) 
                {                   
                    $item->total_session = $item->total_session ? $item->total_session : $get_service_details->total_session;         
                }
            }
            
            $get_service_id = Services::where('id', $session[0]->service_id ?? '')->first();
          
            $get_total_session = $session[0]->total_session ? $session[0]->total_session : ($get_service_id->total_session ?? '');      
            $get_weekly_freq = $get_service_id->weekly_freq ?? '';
            $get_hour = $get_service_id->hour_session ?? '';

            // man power start

            $service_id_arr = $session->pluck('service_id')->toarray();

            $db_all_services = Services::whereIn('id', $service_id_arr)->get();

            $man_power = 0;
            foreach($db_all_services as $loop_item)
            {
                $man_power += $loop_item->man_power;
            }

            // man power end

            $quotation = Quotation::where('id', $quotation_id)->first();

            if ($quotation) 
            {
                $service_address = $quotation->service_address;
                $schedule_date = $quotation->schedule_date;
                $time_of_cleaning = $quotation->time_of_cleaning;

                $serviceAddress = ServiceAddress::where('id', $service_address)->first();

                if ($serviceAddress) 
                {
                    return response()->json([
                        'status' => 'success',
                        'address' => $serviceAddress->address,
                        'postal_code' => $serviceAddress->postal_code,
                        'unit_number' => $serviceAddress->unit_number,
                        'customer_id' => $serviceAddress->customer_id,
                        'sales_order_id' => $sales_order->id,
                        'sales_order_no' => $sales_order->sales_order_no,
                        'schedule_date' => $schedule_date,
                        'time_of_cleaning' =>  $time_of_cleaning,
                        'get_total_session' =>  $get_total_session,
                        'weekly_freq' =>  $get_weekly_freq,
                        'get_hour' =>  $get_hour,
                        'man_power' =>  $man_power,
                        'customer_remark' =>  $customer_data->customer_remark,
                        'remarks' => $quotation->remarks,
                        'invoice_amount' => $quotation->grand_total,
                        'balance_amount' => PaymentController::get_balance_amount($quotation->id),
                        'invoice_no' => $sales_order->invoice_no,
                    ]);
                }
            }
            else
            {
                return response()->json(['status' => 'failed', 'message' => 'Quotation not found']);
            }
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'Sales order not found']);
        }

        // return response()->json(['address' => 'Error fetching address']);
    }

    // schedule_date_table_details

    public function schedule_date_table_details(Request $request)
    {
        // return $request->all();

        $sales_order_id = $request->sales_order_id;
        $sales_order_no = $request->sales_order_no;
        $sch_date = $request->sch_date;
        $cleaner_type = $request->cleaner_type;
        $startTime = $request->startTime;
        $endTime = $request->endTime;

        // get user role id
        $roles_id = $this->get_user_roles_id();

        $users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color', 'xin_employees.zipcode')
            ->whereIn('xin_employees.user_role_id', $roles_id)
            ->get();

        $get_team = DB::table('xin_team')->get();
        $employeeNames = $this->getEmployeeNames($get_team);

        // start

        $sch_indv_emp = [];
        $sch_team_emp = [];

        for($i=0 ; $i<count($sch_date); $i++)
        {                                              
            $sch_team_emp[$i] = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $request->sales_order_id)
                                    ->whereDate('tble_schedule_details.schedule_date', $sch_date[$i])
                                    // ->where(function ($query) use ($request) {
                                    //     $query->where(function ($query1) use ($request) {
                                    //         $query1->whereTime('tble_schedule_details.startTime', '<=', $request->startTime);
                                    //         $query1->whereTime('tble_schedule_details.endTime', '>=', $request->startTime);
                                    //     })
                                    //     ->orWhere(function ($query2) use ($request) {
                                    //         $query2->whereTime('tble_schedule_details.startTime', '<=', $request->endTime);
                                    //         $query2->whereTime('tble_schedule_details.endTime', '>=', $request->endTime);
                                    //     }) ;
                                    // })      
                                    ->where(function ($query) use ($request) {
                                        $query->where(function ($query1) use ($request) {
                                            $query1->whereTime('tble_schedule_details.startTime', '<', $request->endTime)
                                                   ->whereTime('tble_schedule_details.endTime', '>', $request->startTime);
                                        });
                                    })                    
                                    ->where('tble_schedule_details.job_status', 0)                                                      
                                    ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                    ->where('tble_schedule_details.cleaner_type', 'team')
                                    ->pluck('tble_schedule_employee.employee_id')
                                    ->toArray();

            $sch_indv_emp[$i] = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $request->sales_order_id)
                                    ->whereDate('tble_schedule_details.schedule_date', $sch_date[$i])
                                    // ->where(function ($query) use ($request) {
                                    //     $query->where(function ($query1) use ($request) {
                                    //         $query1->whereTime('tble_schedule_details.startTime', '<=', $request->startTime);
                                    //         $query1->whereTime('tble_schedule_details.endTime', '>=', $request->startTime);
                                    //     })
                                    //     ->orWhere(function ($query2) use ($request) {
                                    //         $query2->whereTime('tble_schedule_details.startTime', '<=', $request->endTime);
                                    //         $query2->whereTime('tble_schedule_details.endTime', '>=', $request->endTime);
                                    //     }) ;
                                    // })     
                                    ->where(function ($query) use ($request) {
                                        $query->where(function ($query1) use ($request) {
                                            $query1->whereTime('tble_schedule_details.startTime', '<', $request->endTime)
                                                   ->whereTime('tble_schedule_details.endTime', '>', $request->startTime);
                                        });
                                    })                      
                                    ->where('tble_schedule_details.job_status', 0)                                                      
                                    ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                    ->where('tble_schedule_details.cleaner_type', 'individual')
                                    ->pluck('tble_schedule_employee.employee_id')
                                    ->toArray();   
        }

        // end

        $data['sch_date'] = $sch_date;
        $data['users'] = $users;
        $data['get_team'] = $get_team;
        $data['employeeNames'] = $employeeNames;
        $data['cleaner_type'] = $cleaner_type;
        $data['startTime'] = $startTime;
        $data['endTime'] = $endTime;
        $data['sch_team_emp'] = $sch_team_emp;
        $data['sch_indv_emp'] = $sch_indv_emp;

        // return $data;

        return view('admin.salesOrder.partial.schedule-date-table-details', $data);
    }

    public function edit_schedule_date_table_details(Request $request)
    {
        // return $request->all();

        $sales_order_id = $request->sales_order_id;
        $sales_order_no = $request->sales_order_no;
        $sch_date = $request->sch_date;
        $cleaner_type = $request->cleaner_type;
        $startTime = $request->startTime;
        $endTime = $request->endTime;

        sort($sch_date);

        // get user role id
        $roles_id = $this->get_user_roles_id();

        $users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color', 'xin_employees.zipcode')
            ->whereIn('xin_employees.user_role_id', $roles_id)
            ->get();

        $get_team = DB::table('xin_team')->get();
        $employeeNames = $this->getEmployeeNames($get_team);

        // start

        $sch_indv_emp = [];
        $sch_team_emp = [];
        $sch_driver_emp = [];

        for($i=0 ; $i<count($sch_date); $i++)
        {           
            $Schedule_details = ScheduleDetails::where('sales_order_id', $sales_order_id)
                                                ->whereDate('schedule_date', $sch_date[$i])
                                                ->first();   

            $sch_team_emp[$i] = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $sales_order_id)
                                    ->whereDate('tble_schedule_details.schedule_date', $sch_date[$i])
                                    // ->where(function ($query) use ($Schedule_details, $startTime, $endTime) {
                                    //     $query->where(function ($query1) use ($Schedule_details, $startTime) {
                                    //         $query1->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->startTime ?? $startTime);
                                    //         $query1->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->startTime ?? $startTime);
                                    //     })
                                    //     ->orWhere(function ($query2) use ($Schedule_details, $endTime) {
                                    //         $query2->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->endTime ?? $endTime);
                                    //         $query2->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->endTime ?? $endTime);
                                    //     }) ;
                                    // })      
                                    ->where(function ($query) use ($Schedule_details, $startTime, $endTime) {
                                        $query->where(function ($query1) use ($Schedule_details, $startTime, $endTime) {
                                            $query1->whereTime('tble_schedule_details.startTime', '<', $Schedule_details->endTime ?? $endTime)
                                                   ->whereTime('tble_schedule_details.endTime', '>', $Schedule_details->startTime ?? $startTime);
                                        });
                                    })                   
                                    ->where('tble_schedule_details.job_status', 0)                                                      
                                    ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                    ->where('tble_schedule_details.cleaner_type', 'team')
                                    ->pluck('tble_schedule_employee.employee_id')
                                    ->toArray();

            $sch_indv_emp[$i] = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $sales_order_id)
                                    ->whereDate('tble_schedule_details.schedule_date', $sch_date[$i])
                                    // ->where(function ($query) use ($Schedule_details, $startTime, $endTime) {
                                    //     $query->where(function ($query1) use ($Schedule_details, $startTime) {
                                    //         $query1->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->startTime ?? $startTime);
                                    //         $query1->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->startTime ?? $startTime);
                                    //     })
                                    //     ->orWhere(function ($query2) use ($Schedule_details, $endTime) {
                                    //         $query2->whereTime('tble_schedule_details.startTime', '<=', $Schedule_details->endTime ?? $endTime);
                                    //         $query2->whereTime('tble_schedule_details.endTime', '>=', $Schedule_details->endTime ?? $endTime);
                                    //     }) ;
                                    // })   
                                    ->where(function ($query) use ($Schedule_details, $startTime, $endTime) {
                                        $query->where(function ($query1) use ($Schedule_details, $startTime, $endTime) {
                                            $query1->whereTime('tble_schedule_details.startTime', '<', $Schedule_details->endTime ?? $endTime)
                                                   ->whereTime('tble_schedule_details.endTime', '>', $Schedule_details->startTime ?? $startTime);
                                        });
                                    })                       
                                    ->where('tble_schedule_details.job_status', 0)                                                      
                                    ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                    ->where('tble_schedule_details.cleaner_type', 'individual')
                                    ->pluck('tble_schedule_employee.employee_id')
                                    ->toArray();   


            // check start

            if(!empty($Schedule_details->delivery_date))
            {
                $req_delivery_time = $Schedule_details->delivery_time ?? '08:00';
                $req_delivery_end_time = Carbon::parse($req_delivery_time)->addHour()->format('H:i');

                $driver_emp = [];

                $check_ScheduleDetails = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $sales_order_id)
                    ->whereDate('tble_schedule_details.delivery_date', $Schedule_details->delivery_date ?? '')                         
                    ->where('tble_schedule_details.job_status', 0)     
                    ->get();
                
                foreach($check_ScheduleDetails as $item)
                {
                    $item->delivery_end_time = Carbon::parse($item->delivery_time)->addHour()->format('H:i');
                    $item->delivery_before_time = Carbon::parse($item->delivery_time)->subHour()->format('H:i');

                    if((strtotime($req_delivery_time) >= strtotime($item->delivery_time) && strtotime($req_delivery_time) < strtotime($item->delivery_end_time)) 
                        || (strtotime($item->delivery_time) >= strtotime($req_delivery_time) && strtotime($item->delivery_time) < strtotime($req_delivery_end_time)))
                    {
                        $driver_emp[] = $item->driver_emp_id;
                    }
                }
                
                $sch_driver_emp[$i] = $driver_emp;
            }
            else
            {
                $sch_driver_emp[$i] = [];
            }

            // check end
        }

        // end

        $data['sales_order_id'] = $sales_order_id;
        $data['sch_date'] = $sch_date;
        $data['users'] = $users;
        $data['get_team'] = $get_team;
        $data['employeeNames'] = $employeeNames;
        $data['cleaner_type'] = $cleaner_type;
        $data['startTime'] = $startTime;
        $data['endTime'] = $endTime;
        $data['sch_team_emp'] = $sch_team_emp;
        $data['sch_indv_emp'] = $sch_indv_emp;
        $data['sch_driver_emp'] = $sch_driver_emp;

        // return $data;

        return view('admin.salesOrder.partial.edit-schedule-date-table-details', $data);
    }

    // schedule date check cleaner exists

    public function schedule_date_check_cleaner_exists(Request $request)
    {
        // return $request->all();

        $sales_order_id = $request->sales_order_id;
        $sales_order_no = $request->sales_order_no;
        $sch_date = $request->table_schedule_date;
        $cleaner_type = $request->cleaner_type;
        $startTime = $request->table_startTime;
        $endTime = $request->table_endTime;

        // get user role id
        $roles_id = $this->get_user_roles_id();

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

        $sch_team_emp = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $sales_order_id)
                                    ->whereDate('tble_schedule_details.schedule_date', $sch_date)
                                    // ->where(function ($query) use ($request,) {
                                    //     $query->where(function ($query1) use ($request) {
                                    //         $query1->whereTime('tble_schedule_details.startTime', '<=', $request->table_startTime);
                                    //         $query1->whereTime('tble_schedule_details.endTime', '>=', $request->table_startTime);
                                    //     })
                                    //     ->orWhere(function ($query2) use ($request) {
                                    //         $query2->whereTime('tble_schedule_details.startTime', '<=', $request->table_endTime);
                                    //         $query2->whereTime('tble_schedule_details.endTime', '>=', $request->table_endTime);
                                    //     }) ;
                                    // })         
                                    ->where(function ($query) use ($request) {
                                        $query->where(function ($query1) use ($request) {
                                            $query1->whereTime('tble_schedule_details.startTime', '<', $request->table_endTime)
                                                   ->whereTime('tble_schedule_details.endTime', '>', $request->table_startTime);
                                        });
                                    })                 
                                    ->where('tble_schedule_details.job_status', 0)                                                      
                                    ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                    ->where('tble_schedule_details.cleaner_type', 'team')
                                    ->pluck('tble_schedule_employee.employee_id')
                                    ->toArray();

        $sch_indv_emp = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $request->sales_order_id)
                                    ->whereDate('tble_schedule_details.schedule_date', $sch_date)
                                    // ->where(function ($query) use ($request) {
                                    //     $query->where(function ($query1) use ($request) {
                                    //         $query1->whereTime('tble_schedule_details.startTime', '<=', $request->table_startTime);
                                    //         $query1->whereTime('tble_schedule_details.endTime', '>=', $request->table_startTime);
                                    //     })
                                    //     ->orWhere(function ($query2) use ($request) {
                                    //         $query2->whereTime('tble_schedule_details.startTime', '<=', $request->table_endTime);
                                    //         $query2->whereTime('tble_schedule_details.endTime', '>=', $request->table_endTime);
                                    //     }) ;
                                    // })     
                                    ->where(function ($query) use ($request) {
                                        $query->where(function ($query1) use ($request) {
                                            $query1->whereTime('tble_schedule_details.startTime', '<', $request->table_endTime)
                                                   ->whereTime('tble_schedule_details.endTime', '>', $request->table_startTime);
                                        });
                                    })                    
                                    ->where('tble_schedule_details.job_status', 0)                                                      
                                    ->join('tble_schedule_employee', 'tble_schedule_employee.tble_schedule_details_id', '=', 'tble_schedule_details.id')
                                    ->where('tble_schedule_details.cleaner_type', 'individual')
                                    ->pluck('tble_schedule_employee.employee_id')
                                    ->toArray(); 
                                    
        $data['sch_date'] = $sch_date;
        $data['users'] = $users;
        $data['get_team'] = $get_team;
        $data['employeeNames'] = $employeeNames;
        $data['cleaner_type'] = $cleaner_type;
        $data['startTime'] = $startTime;
        $data['endTime'] = $endTime;
        $data['sch_team_emp'] = $sch_team_emp;
        $data['sch_indv_emp'] = $sch_indv_emp;

        return response()->json($data);
    }

    // unassign

    public function unassign(Request $request)
    {
        // return $request->all();

        $sales_order_id = $request->unassign_sales_order_id;

        $SalesOrder = SalesOrder::find($sales_order_id);

        if($SalesOrder)
        {
            ScheduleModel::where('sales_order_id', $sales_order_id)->delete();
            ScheduleDetails::where('sales_order_id', $sales_order_id)->delete();
            DB::table('tble_schedule_employee')->where('sales_order_id', $sales_order_id)->delete();

            $SalesOrder->status = 0;
            $SalesOrder->cleaner_assigned_status = 0;
            $SalesOrder->job_status = 0;
            $SalesOrder->save();

            // log data store start

            LogController::store('sales_order', 'Sales Order Unassigned', $sales_order_id);

            // log data store end

            return response()->json(['status' => 'success', 'message' => 'Unassigned Successfully']);
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'Sales order not found']);
        }
    }

    // create

    public function create(Request $request)
    {
        // customer
        $customersResidential = Crm::where('customers.customer_type', 'residential_customer_type')
                                ->limit(3)
                                ->orderBy('customers.created_at', 'desc')
                                ->get();

        foreach ($customersResidential as $item) 
        {
            $res_serv_addr = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

            if ($res_serv_addr) {
                $postal_code = $res_serv_addr->postal_code;
                $shortPostalCode = substr($postal_code, 0, 2);

                $zone = ZoneSetting::whereRaw('FIND_IN_SET(?, REPLACE(postal_code, " ", ""))', [$shortPostalCode])->first();

                $zone_color = $zone ? $zone->zone_color : '';
            } else {
                $zone_color = "";
                $shortPostalCode = "";
            }

            $item->zone_color = $zone_color;
            $item->shortPostalCode = $shortPostalCode;
        }

        $data['customersResidential'] = $customersResidential;

        $customersCommercial = Crm::where('customers.customer_type', 'commercial_customer_type')
                                    ->limit(3)
                                    ->orderBy('customers.created_at', 'desc')
                                    ->get();

        foreach ($customersCommercial as $item) 
        {
            $res_serv_addr = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

            if ($res_serv_addr) {
                $postal_code = $res_serv_addr->postal_code;
                $shortPostalCode = substr($postal_code, 0, 2);

                $zone = ZoneSetting::whereRaw('FIND_IN_SET(?, REPLACE(postal_code, " ", ""))', [$shortPostalCode])->first();

                $zone_color = $zone ? $zone->zone_color : '';
            } else {
                $zone_color = "";
                $shortPostalCode = "";
            }

            $item->zone_color = $zone_color;
            $item->shortPostalCode = $shortPostalCode;
        }

        $data['customersCommercial'] = $customersCommercial;

        // company list
        $data['companyList'] = Company::get();

        // tax
        $today_date = date('Y-m-d');
        $data['tax'] = Tax::whereDate('from_date', '<=', $today_date)
                            ->whereDate('to_date', '>=', $today_date)
                            ->first();

        return view('admin.salesOrder.create', $data);
    }

    // preview

    public function get_preview(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();
        $customer = Crm::where('id', $request->customer_id)->first();

        $payment_terms = PaymentTerms::find($customer->payment_terms);

        if($payment_terms)
        {
            $customer->payment_terms_value = $payment_terms->payment_terms;
        }
        else
        {
            $customer->payment_terms_value = "";
        }

        $db_service_address = ServiceAddress::find($request->service_address_id);

        if ($db_service_address) {
            $service_address = $db_service_address->address;
        } else {
            $service_address = "";
        }

        $term_condition = TermCondition::where('company_id', $request->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        // quotation

        if($request->filled('quotation_id'))
        {
            $quotation_id = $request->quotation_id;
            $quotation = Quotation::find($quotation_id);
            $issue_by = $quotation->created_by_name;
        }
        else
        {
            $issue_by = Auth::user()->first_name . " " . Auth::user()->last_name;
        }

        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;
        $data['imagePath'] = $imagePath;
        $data['issue_by'] = $issue_by;
        $data['service_address'] = $service_address;
        $data['quotation'] = $quotation ?? '';

        return view('admin.salesOrder.partial.preview', $data);
    }

    // store

    public function store(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
            ]
        );

        if ($validator->fails()) 
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $company = Company::find($request->company_id);
            $comp_short_name = $company->short_name ?? '';

            // quotation no
            $quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";

            $lead_data = Quotation::where('company_id', $request->company_id)->orderBy('created_at', 'desc')->get();

            if ($lead_data->isEmpty()) 
            {
                $quotation_no .= "000001";
            } 
            else 
            {
                // $last_quotation_no = $lead_data[0]->quotation_no;
                // $quotation_no .= sprintf("%06d", (int)substr($last_quotation_no, 7) + 1);

                $last_quotation_no = [];

                foreach($lead_data as $od)
                {
                    $last_quotation_no[] = explode("-", $od->quotation_no)[2];
                }

                $quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
            }

            // return $quotation_no;

            // invoice no
            $temp_arr = explode("-", $quotation_no);
            $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
            $invoice_no = implode("-", $temp_arr);

            // quotation start

            $quotationDetails = new Quotation();
            
            $quotationDetails->customer_id = $request->customer_id;
            $quotationDetails->company_id = $request->company_id;
            $quotationDetails->service_address = $request->input('service-address-radio');
            $quotationDetails->billing_address = $request->input('billing-address-radio');
            $quotationDetails->amount = $request->amount ?? 0.00;
            $quotationDetails->discount = $request->discount ?? 0.00;
            $quotationDetails->tax = $request->tax ?? 0.00;
            $quotationDetails->tax_percent = $request->tax_percent ?? 0.00;
            $quotationDetails->grand_total = $request->grand_total ?? 0.00;
            $quotationDetails->time_of_cleaning = $request->time_of_cleaning;
            $quotationDetails->quotation_no = $quotation_no;
            $quotationDetails->created_by = Auth::user()->id;
            $quotationDetails->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

            if($request->filled('date_of_cleaning'))
            {
                $quotationDetails->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
            }
            else
            {
                $quotationDetails->schedule_date = null;
            }

            $quotationDetails->invoice_no = $invoice_no;
            $quotationDetails->invoice_date = date('Y-m-d');
            $quotationDetails->status = 4;
            $quotationDetails->payment_advice = 2;
            $quotationDetails->quotation_type = 2;
            $quotationDetails->remarks = $request->quotation_remarks;

            $quotationDetails->save();

            // quotation end

            // quotation service details start

            $total_amount = 0;

            $preview_service_id = $request->preview_service_id;
            $preview_service_product_code = $request->preview_service_product_code;
            $preview_service_name = $request->preview_service_name;
            $preview_service_desc = $request->preview_service_desc;
            $preview_service_qty = $request->preview_service_qty;
            $preview_service_unitPrice = $request->preview_service_unitPrice;
            $preview_service_discount = $request->preview_service_discount;
            $preview_service_total_session = $request->preview_service_total_session;

            for($i=0; $i<count($preview_service_id); $i++)
            {
                $db_service = Services::find($preview_service_id[$i]);

                if($db_service)
                {
                    $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
                    $service_discount_amount = $service_total_amount * ($preview_service_discount[$i]/100);
                    $gross_amount = $service_total_amount - $service_discount_amount;

                    $service = new QuotationServiceDetail();

                    $service->quotation_id = $quotationDetails->id;
                    $service->service_id = $preview_service_id[$i];
                    $service->product_code = $preview_service_product_code[$i];
                    $service->name = $preview_service_name[$i];
                    $service->description = $preview_service_desc[$i];
                    $service->unit_price = $preview_service_unitPrice[$i];
                    $service->quantity = $preview_service_qty[$i];
                    $service->discount = $preview_service_discount[$i];
                    $service->gross_amount = $gross_amount;
                    $service->total_session = $preview_service_total_session[$i];
                    $service->service_type = "service";

                    $service->save();

                    $total_amount += $gross_amount;
                }
            }

            if($request->filled('preview_add_ons_service_name'))
            {
                $preview_add_ons_product_code = $request->preview_add_ons_product_code;
                $preview_add_ons_service_name = $request->preview_add_ons_service_name;
                $preview_add_ons_service_desc = $request->preview_add_ons_service_desc;
                $preview_add_ons_service_qty = $request->preview_add_ons_service_qty;
                $preview_add_ons_service_unitPrice = $request->preview_add_ons_service_unitPrice;
                $preview_add_ons_service_discount = $request->preview_add_ons_service_discount;

                for($i=0; $i<count($preview_add_ons_service_name); $i++)
                {
                    $service_total_amount = $preview_add_ons_service_unitPrice[$i] * $preview_add_ons_service_qty[$i];
                    $service_discount_amount = $service_total_amount * ($preview_add_ons_service_discount[$i]/100);
                    $gross_amount = $service_total_amount - $service_discount_amount;

                    $service = new QuotationServiceDetail();

                    $service->quotation_id = $quotationDetails->id;
                    $service->product_code = $preview_add_ons_product_code[$i];
                    $service->name = $preview_add_ons_service_name[$i];
                    $service->description = $preview_add_ons_service_desc[$i];
                    $service->unit_price = $preview_add_ons_service_unitPrice[$i];
                    $service->quantity = $preview_add_ons_service_qty[$i];
                    $service->discount = $preview_add_ons_service_discount[$i];
                    $service->gross_amount = $gross_amount;
                    $service->service_type = "addons";

                    $service->save();

                    $total_amount += $gross_amount;
                }
            }

            // quotation service details end

            $discount_Type = $request->discount_type;

            if($discount_Type == "percentage")
            {
                $discount = $request->persentage_discount;
                $discount_amount = $total_amount * ($discount/100);
            }
            else
            {
                $discount = $request->amount_discount;
                $discount_amount = $discount;
            }

            if($request->filled('add_tax_check'))
            {
                $tax = $request->tax;
                $tax_type = "exclusive";
            }
            else
            {
                // $tax = 0;
                $tax = $request->tax;
                $tax_type = "inclusive";
            }

            $total = $total_amount - $discount_amount;

            if($tax_type == "exclusive")
            {
                $tax_amt = $total * $tax/100;
                $grand_total = $total + $tax_amt;
            }
            else if($tax_type == "inclusive")
            {
                $tax_amt = ($total / (100 + $tax)) * $tax;
                $grand_total = $total;
            }

            $quotationDetails->discount_type = $discount_Type;
            $quotationDetails->discount = $discount;
            $quotationDetails->tax = $tax_amt;
            $quotationDetails->tax_percent = $tax;
            $quotationDetails->tax_type = $tax_type;
            $quotationDetails->amount = $total_amount;
            $quotationDetails->grand_total = $grand_total;
            $quotationDetails->save();

            $quotation = Quotation::find($quotationDetails->id);

            // Create sales order

            $sales_order = new SalesOrder();
            $sales_order->sales_order_no = rand(12334, 99999);
            $sales_order->customer_id = $quotation->customer_id;
            $sales_order->company_id = $quotation->company_id;
            $sales_order->quotation_id = $quotation->id;
            $sales_order->lead_id = $quotation->lead_id;
            $sales_order->invoice_no = $quotation->invoice_no;
            $sales_order->invoice_date = Carbon::now();
            $sales_order->status = 0;
            $sales_order->created_by_id = Auth::user()->id;
            $sales_order->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

            $sales_order->save();

            // log data store start

            LogController::store('sales_order', 'Sales Order Created', $sales_order->id);
            LogController::store('invoice', 'Invoice Created '.$sales_order->invoice_no, $sales_order->invoice_no);

            // log data store end
    
            return response()->json(['status' => 'success', 'message' => 'Sales Order Created successfully!', 'sales_order_id'=>$sales_order->id]);
        }
    }

    // view assign cleaner

    public function view_assign_cleaner(Request $request)
    {
        $sales_order_id = $request->sales_order_id;

        $data['sales_order'] = SalesOrder::find($sales_order_id);

        $data['ScheduleModel'] = ScheduleModel::where('sales_order_id', $sales_order_id)->first();

        if ($data['ScheduleModel']) 
        {
            $data['ScheduleModel']->selected_days = explode(',', $data['ScheduleModel']->selected_days);
            $data['ScheduleModel']->invoice_amount = Quotation::find($data['sales_order']->quotation_id)->grand_total ?? 0;
            $data['ScheduleModel']->balance_amount = PaymentController::get_balance_amount($data['sales_order']->quotation_id);
        }

        $data['ScheduleDetails'] = ScheduleDetails::where('sales_order_id', $sales_order_id)
                                                    ->orderBy('schedule_date', 'asc')
                                                    ->get();

        foreach($data['ScheduleDetails'] as $item)
        {
            if($item->cleaner_type == "team")
            {
                $employee_name_arr = [];

                $xin_team = DB::table('xin_team')->where('team_id', $item->employee_id)->first();

                if($xin_team)
                {
                    $item->team_name = $xin_team->team_name ?? '';
               
                    $employee_id_arr = explode(',', $xin_team->employee_id);
                        
                    $xin_employees = DB::table('xin_employees')->whereIn('user_id', $employee_id_arr)->get();
    
                    if(!$xin_employees->isEmpty())
                    {
                        foreach($xin_employees as $list)
                        {
                            $employee_name_arr[] = $list->first_name . " " . $list->last_name;
                        }
        
                        $item->employee_name = implode("\n", $employee_name_arr);
                    }
                    else        
                    {
                        $item->employee_name = "";
                    }
                }
                else
                {
                    $item->team_name = "";
                    $item->employee_name = "";
                }
            }
            else if($item->cleaner_type == "individual")
            {
                // superviser

                $superviser_xin_employees = DB::table('xin_employees')->where('user_id', $item->superviser_emp_id)->first();

                if($superviser_xin_employees)
                {
                    $item->super_viser_employee_name = $superviser_xin_employees->first_name . " " . $superviser_xin_employees->last_name;
                }
                else
                {
                    $item->super_viser_employee_name = "";
                }   

                // employees
                
                $employee_name_arr = [];
                $emp_arr = explode(',', $item->employee_id);

                $xin_employees = DB::table('xin_employees')->whereIn('user_id', $emp_arr)->get();

                if(!$xin_employees->isEmpty())
                {
                    foreach($xin_employees as $list)
                    {
                        $employee_name_arr[] = $list->first_name . " " . $list->last_name;
                    }
    
                    $item->employee_name = implode(",", $employee_name_arr);
                }
                else        
                {
                    $item->employee_name = "";
                }
            }

            // driver start

            $driver_xin_employees = DB::table('xin_employees')->where('user_id', $item->driver_emp_id)->first();

            if($driver_xin_employees)
            {
                $item->driver_employee_name = $driver_xin_employees->first_name . " " . $driver_xin_employees->last_name;
            }
            else
            {
                $item->driver_employee_name = "";
            }   

            if(isset($item->delivery_date) || isset($item->delivery_time) || isset($item->driver_emp_id) || isset($item->delivery_remarks))
            {
                $item->driver_display = true;
            }
            else
            {
                $item->driver_display = false;
            }

            // driver end
        }

        // return $data;

        return view('admin.salesOrder.partial.view-assign-cleaner', $data);
    }

    // check renewal

    public function check_renewal(Request $request)
    {
        // return $request->all();

        $sales_order_id = $request->sales_order_id;

        $sales_order = SalesOrder::find($sales_order_id);

        if($sales_order)
        {
            $customer_id = $sales_order->customer_id;

            $customer = Crm::find($customer_id);

            if($customer)
            {
                $total_pending_invoice = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
                                                    ->where('customer_id', $customer_id)
                                                    ->WhereNotNull('invoice_no')
                                                    ->count();

                $customer_pending_invoice_limit = $customer->pending_invoice_limit;   
                
                return response()->json(['status'=>'success', 'message'=>'', 'customer_pending_invoice_limit' => $customer_pending_invoice_limit, 'total_pending_invoice'=>$total_pending_invoice]);                          
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Customer not found']);
            }   
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Sales order not found']);
        }        
    }

    // renewal

    public function renewal(Request $request)
    {
        // return $request->all();

        $sales_order_id = $request->renewal_sales_order_id;

        $SalesOrder = SalesOrder::find($sales_order_id);

        if($SalesOrder)
        {
            $quotation_id = $SalesOrder->quotation_id;

            $db_quotation = Quotation::find($quotation_id);

            if($db_quotation)
            {
                $company = Company::find($db_quotation->company_id);
                $comp_short_name = $company->short_name ?? '';

                // quotation no
                $quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";

                $lead_data = Quotation::where('company_id', $db_quotation->company_id)->orderBy('created_at', 'desc')->get();

                if ($lead_data->isEmpty()) 
                {
                    $quotation_no .= "000001";
                } 
                else 
                {
                    // $last_quotation_no = $lead_data[0]->quotation_no;
                    // $quotation_no .= sprintf("%06d", (int)substr($last_quotation_no, 7) + 1);

                    $last_quotation_no = [];

                    foreach($lead_data as $od)
                    {
                        $last_quotation_no[] = explode("-", $od->quotation_no)[2];
                    }

                    $quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
                }

                // return $quotation_no;

                // invoice no
                $temp_arr = explode("-", $quotation_no);
                $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                $invoice_no = implode("-", $temp_arr);

                // quotation start

                $quotation = new Quotation();
                
                $quotation->customer_id = $db_quotation->customer_id;
                $quotation->company_id = $db_quotation->company_id;
                $quotation->service_address = $db_quotation->service_address;
                $quotation->billing_address = $db_quotation->billing_address;
                $quotation->amount = $db_quotation->amount;
                $quotation->discount = $db_quotation->discount;
                $quotation->discount_type = $db_quotation->discount_type;
                $quotation->tax = $db_quotation->tax;
                $quotation->tax_percent = $db_quotation->tax_percent;
                $quotation->tax_type = $db_quotation->tax_type;
                $quotation->grand_total = $db_quotation->grand_total;
                $quotation->quotation_no = $quotation_no;
                $quotation->created_by = Auth::user()->id;
                $quotation->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;
                $quotation->invoice_no = $invoice_no;
                $quotation->invoice_date = date('Y-m-d');
                $quotation->status = 4;
                $quotation->payment_advice = 2;
                $quotation->quotation_type = 2;
                $quotation->remarks = $db_quotation->quotation_remarks;

                $quotation->save();

                // quotation end

                // quotation service details start

                $db_quotation_service = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

                foreach($db_quotation_service as $item)
                {
                    $quotation_service = new QuotationServiceDetail();

                    $quotation_service->quotation_id = $quotation->id;
                    $quotation_service->service_id = $item->service_id;
                    $quotation_service->product_code = $item->product_code;
                    $quotation_service->name = $item->name;
                    $quotation_service->description = $item->description;
                    $quotation_service->unit_price = $item->unit_price;
                    $quotation_service->quantity = $item->quantity;
                    $quotation_service->discount = $item->discount;
                    $quotation_service->gross_amount = $item->gross_amount;
                    $quotation_service->service_type = $item->service_type;

                    $quotation_service->save();
                }

                // quotation service details end

                // Create sales order

                $sales_order = new SalesOrder();
                $sales_order->sales_order_no = rand(12334, 99999);
                $sales_order->customer_id = $quotation->customer_id;
                $sales_order->company_id = $quotation->company_id;
                $sales_order->quotation_id = $quotation->id;
                $sales_order->lead_id = $quotation->lead_id;
                $sales_order->invoice_no = $quotation->invoice_no;
                $sales_order->invoice_date = Carbon::now();
                $sales_order->status = 0;
                $sales_order->created_by_id = Auth::user()->id;
                $sales_order->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                if($request->filled('renewal_remarks'))
                {
                    $sales_order->renewal_remarks = $request->renewal_remarks;
                }

                $sales_order->save();

                // log data store start

                LogController::store('sales_order', 'Sales Order Renewed', $sales_order_id);
                LogController::store('sales_order', 'Sales Order Renewed from invoice no '.$SalesOrder->invoice_no, $sales_order->id);
                LogController::store('invoice', 'Invoice Created '.$sales_order->invoice_no, $sales_order->invoice_no);
                
                // log data store end

                return response()->json(['status' => 'success', 'message' => 'Sales Order Renewed Successfully and Invoice no. is '.$sales_order->invoice_no, 'sales_order_id'=>$sales_order->id]);
            }
            else
            {
                return response()->json(['status' => 'failed', 'message' => 'Data not found']);
            }          
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'Sales order not found']);
        }
    }

    // log report

    public function log_report($sales_order_id)
    {
        $data['sales_order'] = SalesOrder::find($sales_order_id);

        $log_details = DB::table('log_details')
                        ->where('module', 'sales_order')
                        ->where('ref_no', $sales_order_id)
                        ->paginate(30);

        $data['log_details'] = $log_details;

        // return $data;

        return view('admin.salesOrder.log-report', $data);
    }

    // delivery date check driver exists

    public function delivery_date_check_driver_exists(Request $request)
    {
        // return $request->all();

        $sales_order_id = $request->sales_order_id;
        $sales_order_no = $request->sales_order_no;
        $delivery_date = $request->table_delivery_date;
        $req_delivery_time = $request->table_delivery_time;
        $req_delivery_end_time = Carbon::parse($req_delivery_time)->addHour()->format('H:i');

        // get user role id
        $roles_id = $this->get_user_roles_id();

        $users = DB::table('xin_employees')
            ->leftJoin('xin_team', 'xin_employees.employee_id', '=', 'xin_team.employee_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select('xin_employees.user_id', 'xin_employees.username', DB::raw("CONCAT(xin_employees.first_name, ' ', xin_employees.last_name) AS full_name"), 'xin_team.team_name', 'zone_settings.zone_color')
            ->whereIn('xin_employees.user_role_id', $roles_id)
            ->get();

        $check_ScheduleDetails = ScheduleDetails::where('tble_schedule_details.sales_order_id', '!=', $sales_order_id)
                            ->whereDate('tble_schedule_details.delivery_date', $delivery_date)                         
                            ->where('tble_schedule_details.job_status', 0)     
                            ->get();

        $driver_emp = [];
        foreach($check_ScheduleDetails as $item)
        {
            $item->delivery_end_time = Carbon::parse($item->delivery_time)->addHour()->format('H:i');
            $item->delivery_before_time = Carbon::parse($item->delivery_time)->subHour()->format('H:i');

            if((strtotime($req_delivery_time) >= strtotime($item->delivery_time) && strtotime($req_delivery_time) < strtotime($item->delivery_end_time)) 
                || (strtotime($item->delivery_time) >= strtotime($req_delivery_time) && strtotime($item->delivery_time) < strtotime($req_delivery_end_time)))
            {
                $driver_emp[] = $item->driver_emp_id;
            }
        }
                                    
        $data['delivery_date'] = $delivery_date;
        $data['users'] = $users;
        $data['delivery_time'] = $req_delivery_time;
        $data['driver_emp'] = $driver_emp;

        return response()->json($data);
    }

    public function edit_sales_order($sales_order_id)
    {
        $sales_order = SalesOrder::find($sales_order_id);

        if($sales_order)
        {
            $quotation_id = $sales_order->quotation_id;

            $quotation = Quotation::find($quotation_id);
            $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

            foreach($quotation_details as $item)
            {
                $get_service_details = Services::find($item->service_id);
                if($get_service_details)
                {
                    $item->hour_session = $get_service_details->hour_session;
                    $item->total_session = $item->total_session ? $item->total_session : $get_service_details->total_session;
                    $item->weekly_freq = $get_service_details->weekly_freq;
                }
                else
                {
                    $item->hour_session = "";
                    $item->total_session = "";
                    $item->weekly_freq = "";
                }
            }

            $customerId = $quotation->customer_id;

            $customer = Crm::leftJoin('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
                            ->select(
                                'customers.*',
                                'customers.id as customer_id',
                                'language_spoken.language_name as language_name',
                            )
                            ->where('customers.id', $customerId)
                            ->first();

            $addresses = ServiceAddress::where('customer_id', $customerId)->get();
            $billingaddresses = BillingAddress::where('customer_id', $customerId)->get();
        
            $companyList = Company::get();

            $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();
            $service_date = array();
            foreach ($get_current_month_service as $item) {
                $service_date[] = array(
                    'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
                    'service' => $item->service_name
                );
            }
            $dates = json_encode((object)$service_date);

            // tax

            // $tax = Tax::first();

            $today_date = date('Y-m-d');
            $tax = Tax::whereDate('from_date', '<=', $today_date)
                        ->whereDate('to_date', '>=', $today_date)
                        ->first();

            return view('admin.salesOrder.edit', compact('sales_order', 'quotation', 'quotation_details', 'customer', 'tax', 'companyList', 'addresses', 'billingaddresses', 'dates'));
        }
        else
        {
            abort(404);
        }
    }

    // public function update(Request $request)
    // {
    //     // return $request->all();

    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'customer_id' => 'required',
    //             'date_of_cleaning' => 'nullable',
    //             'time_of_cleaning' => 'nullable',
    //             'billing-address-radio' => 'required',
    //             'service-address-radio' => 'required',
    //             'preview_service_id' => 'required',
    //         ],
    //         [
    //             'customer_id.required' => 'Select Customer First',
    //             'date_of_cleaning.required' => 'Select Date',
    //             'time_of_cleaning.required' => 'Select Time',
    //             'billing-address-radio.required' => 'Select Billing address',
    //             'service-address-radio.required' => 'Select Service address',
    //             'preview_service_id.required' => 'Select Service',
    //         ]
    //     );

    //     if ($validator->fails()) 
    //     {
    //         return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
    //     }
    //     else
    //     {
    //         $sales_order_id = $request->sales_order_id;
    //         $sales_order = SalesOrder::find($sales_order_id);

    //         if($sales_order)
    //         {
    //             $quotation_id = $request->quotation_id;

    //             // quotation start

    //             $quotation = Quotation::where('id', $quotation_id)->first();
    //             $quotation->customer_id = $request->customer_id;
    //             $quotation->company_id = $request->company_id;
    //             $quotation->service_address = $request->input('service-address-radio');
    //             $quotation->billing_address = $request->input('billing-address-radio');

    //             if($request->filled('date_of_cleaning'))
    //             {
    //                 $quotation->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
    //             }
    //             else
    //             {
    //                 $quotation->schedule_date = null;
    //             }

    //             $quotation->time_of_cleaning = $request->time_of_cleaning;
    //             $quotation->remarks = $request->edit_quotation_remarks;

    //             $quotation->save();

    //             // quotation end

    //             $total_amount = 0;

    //             // quotation service start

    //             QuotationServiceDetail::where('quotation_id', $quotation_id)->delete();

    //             $preview_service_id = $request->preview_service_id;
    //             $preview_service_product_code = $request->preview_service_product_code;
    //             $preview_service_name = $request->preview_service_name;
    //             $preview_service_desc = $request->preview_service_desc;
    //             $preview_service_qty = $request->preview_service_qty;
    //             $preview_service_unitPrice = $request->preview_service_unitPrice;
    //             $preview_service_discount = $request->preview_service_discount;
    //             $preview_service_total_session = $request->preview_service_total_session;

    //             for($i=0; $i<count($preview_service_id); $i++)
    //             {
    //                 $db_service = Services::find($preview_service_id[$i]);

    //                 if($db_service)
    //                 {
    //                     $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
    //                     $service_discount_amount = $service_total_amount * ($preview_service_discount[$i]/100);
    //                     $gross_amount = $service_total_amount - $service_discount_amount;

    //                     $service = new QuotationServiceDetail();

    //                     $service->quotation_id = $quotation_id;
    //                     $service->service_id = $preview_service_id[$i];
    //                     $service->product_code = $preview_service_product_code[$i];
    //                     $service->name = $preview_service_name[$i];
    //                     $service->description = $preview_service_desc[$i];
    //                     $service->unit_price = $preview_service_unitPrice[$i];
    //                     $service->quantity = $preview_service_qty[$i];
    //                     $service->discount = $preview_service_discount[$i];
    //                     $service->gross_amount = $gross_amount;
    //                     $service->total_session = $preview_service_total_session[$i];
    //                     $service->service_type = "service";

    //                     $service->save();

    //                     $total_amount += $gross_amount;
    //                 }
    //             }

    //             if($request->filled('preview_add_ons_service_name'))
    //             {
    //                 $preview_add_ons_product_code = $request->preview_add_ons_product_code;
    //                 $preview_add_ons_service_name = $request->preview_add_ons_service_name;
    //                 $preview_add_ons_service_desc = $request->preview_add_ons_service_desc;
    //                 $preview_add_ons_service_qty = $request->preview_add_ons_service_qty;
    //                 $preview_add_ons_service_unitPrice = $request->preview_add_ons_service_unitPrice;
    //                 $preview_add_ons_service_discount = $request->preview_add_ons_service_discount;

    //                 for($i=0; $i<count($preview_add_ons_service_name); $i++)
    //                 {
    //                     $service_total_amount = $preview_add_ons_service_unitPrice[$i] * $preview_add_ons_service_qty[$i];
    //                     $service_discount_amount = $service_total_amount * ($preview_add_ons_service_discount[$i]/100);
    //                     $gross_amount = $service_total_amount - $service_discount_amount;

    //                     $service = new QuotationServiceDetail();

    //                     $service->quotation_id = $quotation_id;
    //                     $service->product_code = $preview_add_ons_product_code[$i];
    //                     $service->name = $preview_add_ons_service_name[$i];
    //                     $service->description = $preview_add_ons_service_desc[$i];
    //                     $service->unit_price = $preview_add_ons_service_unitPrice[$i];
    //                     $service->quantity = $preview_add_ons_service_qty[$i];
    //                     $service->discount = $preview_add_ons_service_discount[$i];
    //                     $service->gross_amount = $gross_amount;
    //                     $service->service_type = "addons";

    //                     $service->save();

    //                     $total_amount += $gross_amount;
    //                 }
    //             }

    //             // quotation service end

    //             $discount_Type = $request->discount_type;

    //             if($discount_Type == "percentage")
    //             {
    //                 $discount = $request->persentage_discount;
    //                 $discount_amount = $total_amount * ($discount/100);
    //             }
    //             else
    //             {
    //                 $discount = $request->amount_discount;
    //                 $discount_amount = $discount;
    //             }

    //             if($request->filled('add_tax_check'))
    //             {
    //                 $tax = $request->tax;
    //                 $tax_type = "exclusive";
    //             }
    //             else
    //             {
    //                 // $tax = 0;
    //                 $tax = $request->tax;
    //                 $tax_type = "inclusive";
    //             }

    //             $total = $total_amount - $discount_amount;

    //             if($tax_type == "exclusive")
    //             {
    //                 $tax_amt = $total * $tax/100;
    //                 $grand_total = $total + $tax_amt;
    //             }
    //             else if($tax_type == "inclusive")
    //             {
    //                 $tax_amt = ($total / (100 + $tax)) * $tax;
    //                 $grand_total = $total;
    //             }

    //             $deposit_amount = PaymentController::get_deposit_amount($quotation_id);

    //             if($deposit_amount == 0)
    //             {
    //                 $payment_status = "unpaid";
    //             }
    //             else if($grand_total == $deposit_amount)
    //             {
    //                 $payment_status = "paid";
    //             }
    //             else if($grand_total > $deposit_amount)
    //             {
    //                 $payment_status = "partial_paid";
    //             }
    //             else
    //             {
    //                 $payment_status = "overpaid";
    //             }

    //             $quotation->discount_type = $discount_Type;
    //             $quotation->discount = $discount;
    //             $quotation->tax = $tax_amt;
    //             $quotation->tax_percent = $tax;
    //             $quotation->tax_type = $tax_type;
    //             $quotation->amount = $total_amount;
    //             $quotation->grand_total = $grand_total;
    //             $quotation->payment_status = $payment_status;
    //             $quotation->save();

    //             // schedule start

    //             $session = QuotationServiceDetail::where('quotation_id', $quotation_id)->where('service_type', 'service')->get();
    //             foreach ($session as $item) 
    //             {
    //                 $get_service_details = Services::find($item->service_id);
    //                 if ($get_service_details) 
    //                 {                   
    //                     $item->total_session = $item->total_session ? $item->total_session : $get_service_details->total_session;         
    //                 }
    //             }

    //             $get_service_id = Services::where('id', $session[0]->service_id ?? '')->first();
          
    //             $get_total_session = $session[0]->total_session ? $session[0]->total_session : ($get_service_id->total_session ?? '');      
    //             $get_weekly_freq = $get_service_id->weekly_freq ?? '';
    //             $get_hour = $get_service_id->hour_session ?? '';

    //             // man power start

    //             $service_id_arr = $session->pluck('service_id')->toarray();

    //             $db_all_services = Services::whereIn('id', $service_id_arr)->get();

    //             $man_power = 0;
    //             foreach($db_all_services as $loop_item)
    //             {
    //                 $man_power += $loop_item->man_power;
    //             }

    //             // man power end               

    //             // schedule end              

    //             $schedule = ScheduleModel::where('sales_order_id', $sales_order_id)->first();

    //             if($schedule)
    //             {          
    //                 $db_quotation = Quotation::where('id', $quotation_id)->first(); 
    //                 $service_address = ServiceAddress::find($db_quotation->service_address);

    //                 $serviceDetails = QuotationServiceDetail::where('quotation_id', $quotation_id)->get(); 

    //                 $schedule->service_id = $serviceDetails[0]->service_id;
    //                 $schedule->postalCode = $service_address->postal_code;
    //                 $schedule->unitNo = $service_address->unit_number;
    //                 $schedule->address = $service_address->address;
    //                 $schedule->total_session = $get_total_session;
    //                 $schedule->weekly_freq = $get_weekly_freq;
    //                 $schedule->man_power = $man_power;
    //                 $schedule->remarks = $db_quotation->remarks;

    //                 $schedule->save();
    //             }

    //             // log data store start

    //             LogController::store('sales_order', 'Sales Order Updated', $sales_order_id ?? '');

    //             // log data store end

    //             return response()->json(['status' => 'success', 'message' => 'Sales Order Updated successfully!']);
    //         }
    //         else
    //         {
    //             return response()->json(['status' => 'failed', 'message' => 'Sales Order Not Found!']);
    //         }
    //     }
    // }

    public function update(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
            ]
        );

        if ($validator->fails()) 
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $sales_order_id = $request->sales_order_id;
            $sales_order = SalesOrder::find($sales_order_id);

            if($sales_order)
            {
                if($sales_order->job_status == 2)
                {
                    return response()->json(['status' => 'failed', 'message' => 'Sales Order already completed!']);
                }
                else if($sales_order->job_status == 3)
                {
                    return response()->json(['status' => 'failed', 'message' => 'Sales Order already cancelled!']);
                }

                $scheduleDetailsQuery = ScheduleDetails::where('sales_order_id', $sales_order_id);
    
                if ($scheduleDetailsQuery->exists()) 
                {
                    $allSchedules = $scheduleDetailsQuery->get();
                    $done_ScheduleDetails = $allSchedules->whereIn('job_status', [1, 2, 3]);
                    $count_done_ScheduleDetails = count($done_ScheduleDetails);
                    
                    $request_service_total_session = (int) $request->preview_service_total_session[0];

                    if ($request_service_total_session < $count_done_ScheduleDetails) 
                    {
                        return response()->json(['status' => 'failed', 'message' => 'Session cannot be decreased because ' . $count_done_ScheduleDetails . ' sessions are already completed/cancelled/in progress']);
                    }

                    $desc_allSchedules = ScheduleDetails::where('sales_order_id', $sales_order_id)->orderBy('schedule_date', 'desc')->get();
                    $asc_allSchedules = ScheduleDetails::where('sales_order_id', $sales_order_id)->orderBy('schedule_date', 'asc')->get();

                    // If more sessions exist than required, find excess sessions
                    if (count($asc_allSchedules) > $request_service_total_session) 
                    {
                        $excessSessions = $asc_allSchedules->slice($request_service_total_session);
                        
                        // Check if any of the excess sessions have job_status 1, 2, or 3
                        $protectedSessions = $excessSessions->whereIn('job_status', [1, 2, 3]);

                        if (count($protectedSessions) > 0) 
                        {
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Cannot remove session(s) because some are already in progress, completed, or cancelled.'
                            ]);
                        }
                    }                   
                }

                $quotation_id = $request->quotation_id;

                // quotation start

                $quotation = Quotation::where('id', $quotation_id)->first();
                $quotation->customer_id = $request->customer_id;
                $quotation->company_id = $request->company_id;
                $quotation->service_address = $request->input('service-address-radio');
                $quotation->billing_address = $request->input('billing-address-radio');

                if($request->filled('date_of_cleaning'))
                {
                    $quotation->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
                }
                else
                {
                    $quotation->schedule_date = null;
                }

                $quotation->time_of_cleaning = $request->time_of_cleaning;
                $quotation->remarks = $request->edit_quotation_remarks;

                $quotation->save();

                // quotation end

                $total_amount = 0;

                // quotation service start

                QuotationServiceDetail::where('quotation_id', $quotation_id)->delete();

                $preview_service_id = $request->preview_service_id;
                $preview_service_product_code = $request->preview_service_product_code;
                $preview_service_name = $request->preview_service_name;
                $preview_service_desc = $request->preview_service_desc;
                $preview_service_qty = $request->preview_service_qty;
                $preview_service_unitPrice = $request->preview_service_unitPrice;
                $preview_service_discount = $request->preview_service_discount;
                $preview_service_total_session = $request->preview_service_total_session;

                for($i=0; $i<count($preview_service_id); $i++)
                {
                    $db_service = Services::find($preview_service_id[$i]);

                    if($db_service)
                    {
                        $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
                        $service_discount_amount = $service_total_amount * ($preview_service_discount[$i]/100);
                        $gross_amount = $service_total_amount - $service_discount_amount;

                        $service = new QuotationServiceDetail();

                        $service->quotation_id = $quotation_id;
                        $service->service_id = $preview_service_id[$i];
                        $service->product_code = $preview_service_product_code[$i];
                        $service->name = $preview_service_name[$i];
                        $service->description = $preview_service_desc[$i];
                        $service->unit_price = $preview_service_unitPrice[$i];
                        $service->quantity = $preview_service_qty[$i];
                        $service->discount = $preview_service_discount[$i];
                        $service->gross_amount = $gross_amount;
                        $service->total_session = $preview_service_total_session[$i];
                        $service->service_type = "service";

                        $service->save();

                        $total_amount += $gross_amount;
                    }
                }

                if($request->filled('preview_add_ons_service_name'))
                {
                    $preview_add_ons_product_code = $request->preview_add_ons_product_code;
                    $preview_add_ons_service_name = $request->preview_add_ons_service_name;
                    $preview_add_ons_service_desc = $request->preview_add_ons_service_desc;
                    $preview_add_ons_service_qty = $request->preview_add_ons_service_qty;
                    $preview_add_ons_service_unitPrice = $request->preview_add_ons_service_unitPrice;
                    $preview_add_ons_service_discount = $request->preview_add_ons_service_discount;

                    for($i=0; $i<count($preview_add_ons_service_name); $i++)
                    {
                        $service_total_amount = $preview_add_ons_service_unitPrice[$i] * $preview_add_ons_service_qty[$i];
                        $service_discount_amount = $service_total_amount * ($preview_add_ons_service_discount[$i]/100);
                        $gross_amount = $service_total_amount - $service_discount_amount;

                        $service = new QuotationServiceDetail();

                        $service->quotation_id = $quotation_id;
                        $service->product_code = $preview_add_ons_product_code[$i];
                        $service->name = $preview_add_ons_service_name[$i];
                        $service->description = $preview_add_ons_service_desc[$i];
                        $service->unit_price = $preview_add_ons_service_unitPrice[$i];
                        $service->quantity = $preview_add_ons_service_qty[$i];
                        $service->discount = $preview_add_ons_service_discount[$i];
                        $service->gross_amount = $gross_amount;
                        $service->service_type = "addons";

                        $service->save();

                        $total_amount += $gross_amount;
                    }
                }

                // quotation service end

                $discount_Type = $request->discount_type;

                if($discount_Type == "percentage")
                {
                    $discount = $request->persentage_discount;
                    $discount_amount = $total_amount * ($discount/100);
                }
                else
                {
                    $discount = $request->amount_discount;
                    $discount_amount = $discount;
                }

                if($request->filled('add_tax_check'))
                {
                    $tax = $request->tax;
                    $tax_type = "exclusive";
                }
                else
                {
                    // $tax = 0;
                    $tax = $request->tax;
                    $tax_type = "inclusive";
                }

                $total = $total_amount - $discount_amount;

                if($tax_type == "exclusive")
                {
                    $tax_amt = $total * $tax/100;
                    $grand_total = $total + $tax_amt;
                }
                else if($tax_type == "inclusive")
                {
                    $tax_amt = ($total / (100 + $tax)) * $tax;
                    $grand_total = $total;
                }

                $deposit_amount = PaymentController::get_deposit_amount($quotation_id);

                if($deposit_amount == 0)
                {
                    $payment_status = "unpaid";
                }
                else if($grand_total == $deposit_amount)
                {
                    $payment_status = "paid";
                }
                else if($grand_total > $deposit_amount)
                {
                    $payment_status = "partial_paid";
                }
                else
                {
                    $payment_status = "overpaid";
                }

                $quotation->discount_type = $discount_Type;
                $quotation->discount = $discount;
                $quotation->tax = $tax_amt;
                $quotation->tax_percent = $tax;
                $quotation->tax_type = $tax_type;
                $quotation->amount = $total_amount;
                $quotation->grand_total = $grand_total;
                $quotation->payment_status = $payment_status;
                $quotation->save();

                // schedule start

                $session = QuotationServiceDetail::where('quotation_id', $quotation_id)->where('service_type', 'service')->get();
                foreach ($session as $item) 
                {
                    $get_service_details = Services::find($item->service_id);
                    if ($get_service_details) 
                    {                   
                        $item->total_session = $item->total_session ? $item->total_session : $get_service_details->total_session;         
                    }
                }

                $get_service_id = Services::where('id', $session[0]->service_id ?? '')->first();
          
                $get_total_session = $session[0]->total_session ? $session[0]->total_session : ($get_service_id->total_session ?? '');      
                $get_weekly_freq = $get_service_id->weekly_freq ?? '';
                $get_hour = $get_service_id->hour_session ?? '';

                // man power start

                $service_id_arr = $session->pluck('service_id')->toarray();

                $db_all_services = Services::whereIn('id', $service_id_arr)->get();

                $man_power = 0;
                foreach($db_all_services as $loop_item)
                {
                    $man_power += $loop_item->man_power;
                }

                // man power end               

                $schedule = ScheduleModel::where('sales_order_id', $sales_order_id)->first();

                if($schedule)
                {          
                    $db_quotation = Quotation::where('id', $quotation_id)->first(); 
                    $service_address = ServiceAddress::find($db_quotation->service_address);

                    $serviceDetails = QuotationServiceDetail::where('quotation_id', $quotation_id)->get(); 

                    $schedule->service_id = $serviceDetails[0]->service_id;
                    $schedule->postalCode = $service_address->postal_code;
                    $schedule->unitNo = $service_address->unit_number;
                    $schedule->address = $service_address->address;
                    $schedule->total_session = $get_total_session;
                    $schedule->weekly_freq = $get_weekly_freq;
                    $schedule->man_power = $man_power;
                    $schedule->remarks = $db_quotation->remarks;

                    $schedule->save();
                }

                // schedule end         

                if ($schedule && $scheduleDetailsQuery->exists()) 
                {   
                    // If more sessions exist than required, find excess sessions
                    if (count($asc_allSchedules) > $request_service_total_session) 
                    {
                        $excessSessions = $asc_allSchedules->slice($request_service_total_session);

                        ScheduleDetails::whereIn('id', $excessSessions->pluck('id'))->delete();
                        DB::table('tble_schedule_employee')->whereIn('tble_schedule_details_id', $excessSessions->pluck('id'))->delete();

                        $db_sch_date_arr = ScheduleDetails::where('sales_order_id', $sales_order_id)->orderBy('schedule_date', 'asc')->pluck('schedule_date')->toArray();
                                         
                        $schedule->days = implode(', ', $db_sch_date_arr);
                        $schedule->endDate = $db_sch_date_arr[count($db_sch_date_arr)-1];
                        $schedule->save();                                                               
                    }        

                    // If sessions need to be increased, generate and save new sessions
                    else if (count($allSchedules) < $request_service_total_session) 
                    {
                        $newSessionCount = $request_service_total_session - count($allSchedules);
                        $startDate = $desc_allSchedules[0]->schedule_date ?? ''; // Ensure the frontend sends start_date
                        $startTime = $schedule->startTime ?? ''; // Ensure the frontend sends start_time
                        $endTime = $schedule->endTime ?? ''; // Ensure the frontend sends end_time
                        $selectedDays = $schedule->selected_days ?? ''; // ['Monday', 'Wednesday', 'Friday']

                        // Generate new session dates using the JS logic converted into PHP
                        $newSessions = $this->generateSessionDates($startDate, $selectedDays, $newSessionCount);

                        // Save new sessions in DB
                        foreach ($newSessions as $loop_new_session)
                        {
                            ScheduleDetails::insert([
                                'tble_schedule_id' => $schedule->id ?? '',
                                'sales_order_id' => $sales_order_id,
                                'sales_order_no' => $sales_order->sales_order_no,
                                'schedule_date' => $loop_new_session,
                                'schedule_day' => date('l', strtotime($loop_new_session)),
                                'startTime' => $startTime,
                                'endTime' => $endTime,
                                'pay_amount' => 0,
                                'cleaner_type' => $schedule->cleaner_type ?? '', // or get from request
                            ]);
                        }

                        $db_sch_date_arr = ScheduleDetails::where('sales_order_id', $sales_order_id)->orderBy('schedule_date', 'asc')->pluck('schedule_date')->toArray();
                        
                        $schedule->days = implode(', ', $db_sch_date_arr);
                        $schedule->endDate = $db_sch_date_arr[count($db_sch_date_arr)-1];
                        $schedule->save();                        
                    }                                                                 
                
                    // status change start

                    $flag_status = 0;

                    if($schedule->cleaner_type == "individual")
                    {
                        $ScheduleDetails = ScheduleDetails::where('sales_order_id', $sales_order_id)->get();

                        foreach($ScheduleDetails as $loop_sch_details)
                        {
                            $tble_schedule_employee = DB::table('tble_schedule_employee')
                                                            ->where('sales_order_id', $sales_order_id)
                                                            ->where('tble_schedule_details_id', $loop_sch_details->id)
                                                            ->whereDate('schedule_date', $loop_sch_details->schedule_date)
                                                            ->get();

                            if($schedule->man_power != count($tble_schedule_employee))
                            {
                                $flag_status = 1;
                            }
                        }                      
                    }

                    $db_sch_date_arr = ScheduleDetails::where('sales_order_id', $sales_order_id)->orderBy('schedule_date', 'asc')->pluck('schedule_date')->toArray();
                            
                    $total_count = count($db_sch_date_arr);
                    $assign_count = count(ScheduleDetails::where('tble_schedule_id', $schedule->id ?? '')->whereNotNull('employee_id')->get());

                    if($total_count == 0)
                    {
                        $sales_order->status = 0;
                        $sales_order->cleaner_assigned_status = 0;
                        $sales_order->save();
                   
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
                                $sales_order->status = 2;
                                $sales_order->cleaner_assigned_status = 2;
                                $sales_order->save();
                                      
                                $schedule->status = 2;                           
                                $schedule->cleaner_assigned_status = 2;
                                $schedule->save();                            
                            }
                            else
                            {                              
                                $sales_order->status = 1;
                                $sales_order->cleaner_assigned_status = 1;
                                $sales_order->save();
                             
                                $schedule->status = 1;
                                $schedule->cleaner_assigned_status = 1;
                                $schedule->save();                              
                            }
                        }
                        else
                        {
                            $sales_order->status = 2;
                            $sales_order->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                            $sales_order->save();
                         
                            $schedule->status = 2;
                            $schedule->cleaner_assigned_status = ($assign_count == 0) ? 0 : 2;
                            $schedule->save();                           
                        }
                    }    

                    // status change end
                }

                // log data store start

                LogController::store('sales_order', 'Sales Order Updated', $sales_order_id ?? '');

                // log data store end

                return response()->json(['status' => 'success', 'message' => 'Sales Order Updated successfully!']);
            }
            else
            {
                return response()->json(['status' => 'failed', 'message' => 'Sales Order Not Found!']);
            }
        }
    }

    /**
     * Generate session dates based on start date, selected days, and number of sessions
    */
    public static function generateSessionDates($startDate, $selectedDays, $sessionCount)
    {
        // Ensure $selectedDays is an array
        if (!is_array($selectedDays)) {
            $selectedDays = explode(',', $selectedDays);
        }

        // Normalize selected day names
        $selectedDays = array_map('strtolower', $selectedDays);

        // Day name map
        $dayMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];

        $current = \Carbon\Carbon::parse($startDate)->addDay(); // Skip the start date
        $dates = [];

        while (count($dates) < $sessionCount) {
            $currentDayName = strtolower($dayMap[$current->dayOfWeek]);

            if (in_array($currentDayName, $selectedDays)) {
                $dates[] = $current->format('Y-m-d');
            }

            $current->addDay(); // move to next day
        }

        return $dates;
    }
}
