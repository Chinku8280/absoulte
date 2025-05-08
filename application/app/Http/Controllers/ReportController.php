<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\Company;
use App\Models\CompanyInfo;
use App\Models\Crm;
use App\Models\LeadOfflinePaymentDetail;
use App\Models\LeadPaymentInfo;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use App\Models\ServiceAddress;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $data['company'] = Company::all();

        return view('admin.report.index', $data);
    }

    // report invoice table data

    public function report_invoice_table_data(Request $request)
    {
        $quotation = Quotation::WhereNotNull('quotations.invoice_no')
                                ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')       
                                ->leftJoin('company', 'company.id', '=', 'quotations.company_id')                                
                                ->select('quotations.*', 'company.company_name', 'customers.customer_type', 'customers.customer_name', 'customers.individual_company_name')
                                ->orderBy('quotations.created_at', 'desc');

        // filter start

        if($request->filled('filter_invoice_no_from') && $request->filled('filter_invoice_no_to'))
        {
            // $quotation = $quotation->where('quotations.invoice_no', $request->filter_invoice_no);

            $start_inv = $request->filter_invoice_no_from;
            $end_inv = $request->filter_invoice_no_to;

            $quotation = $quotation->whereBetween('quotations.invoice_no', [$start_inv, $end_inv]);
        }

        if($request->filled('filter_customer_name'))
        {
            $quotation = $quotation->where(function ($query) use ($request) {
                            $query->where('customers.customer_name', 'like', '%' . $request->filter_customer_name . '%')
                                ->orWhere('customers.individual_company_name', 'like', '%' . $request->filter_customer_name . '%');
                        });
        }

        if($request->filled('filter_invoice_date'))
        {
            $temp_date_arr = explode(' - ', $request->filter_invoice_date);

            $from_date = date('Y-m-d', strtotime($temp_date_arr[0]));
            $to_date = date('Y-m-d', strtotime($temp_date_arr[1]));

            $quotation = $quotation->whereBetween('quotations.invoice_date', [$from_date, $to_date]);
        }

        if($request->filled('filter_service_type'))
        {
            $quotation = $quotation->where('quotations.company_id', $request->filter_service_type);
        }

        // filter end

        $quotation = $quotation->get();

        $data['quotation'] = $quotation;

        $new_data = [];

        foreach ($data['quotation'] as $key => $item)
        {
            if ($item->invoice_no != "" && $item->invoice_no != null)
            {
                // lead payment detail start

                $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $item->id)->get();

                $deposit = 0;
                $balance = 0;
                foreach($lead_payment_detail as $list)
                {
                    if($list->payment_status == 1)
                    {
                        $deposit += $list->payment_amount;
                    }
                }
                $balance = $item->grand_total - $deposit;

                // lead payment detail end

                $action = '<a class="" href="'.route('finance.view-invoice', $item->id).'" title="View Invoice">
                                <span class="badge bg-info">View</span>
                            </a>';                      

                $new_data[] = [
                    $key+1,
                    $item->invoice_no,
                    $item->invoice_date ? date('d-m-Y', strtotime($item->invoice_date)) : '',
                    $item->company_name,
                    $item->schedule_date ? date('d-m-Y', strtotime($item->schedule_date)) : '',
                    ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->individual_company_name,
                    "$".number_format($item->amount, 2),
                    "$" . number_format($item->tax, 2) . " (". $item->tax_percent . "%)",
                    "$".number_format($item->grand_total, 2),
                    "$".number_format($balance, 2),
                    $item->created_by_name,
                    ucfirst(str_replace("_", " ", $item->payment_status)),
                    $action
                ];
            }
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['quotation']->count(),
            "recordsFiltered" => $data['quotation']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    // report sales order table data

    public function report_sales_order_table_data(Request $request)
    {
        $sales_order = SalesOrder::leftJoin('customers', 'customers.id', '=', 'sales_order.customer_id')          
                                ->leftJoin('company', 'company.id', '=', 'sales_order.company_id')                              
                                ->select('sales_order.*', 'company.company_name', 'customers.customer_type', 'customers.customer_name', 'customers.individual_company_name')
                                ->orderBy('created_at', 'desc');

        // filter start

        if($request->filled('filter_invoice_no_from') && $request->filled('filter_invoice_no_to'))
        {
            // $sales_order = $sales_order->where('sales_order.invoice_no', $request->filter_invoice_no);

            $start_inv = $request->filter_invoice_no_from;
            $end_inv = $request->filter_invoice_no_to;

            $sales_order = $sales_order->whereBetween('sales_order.invoice_no', [$start_inv, $end_inv]);
        }

        if($request->filled('filter_customer_name'))
        {
            $sales_order = $sales_order->where(function ($query) use ($request) {
                            $query->where('customers.customer_name', 'like', '%' . $request->filter_customer_name . '%')
                                ->orWhere('customers.individual_company_name', 'like', '%' . $request->filter_customer_name . '%');
                        });
        }

        if($request->filled('filter_invoice_date'))
        {
            $temp_date_arr = explode(' - ', $request->filter_invoice_date);

            $from_date = date('Y-m-d', strtotime($temp_date_arr[0]));
            $to_date = date('Y-m-d', strtotime($temp_date_arr[1]));

            $sales_order = $sales_order->whereBetween('sales_order.invoice_date', [$from_date, $to_date]);
        }

        if($request->filled('filter_service_type'))
        {
            $sales_order = $sales_order->where('sales_order.company_id', $request->filter_service_type);
        }

        // filter end

        $sales_order = $sales_order->get();

        $data['sales_order'] = $sales_order;

        $new_data = [];

        foreach ($data['sales_order'] as $key => $item) 
        {
            // status

            if ($item->cleaner_assigned_status == 1) {
                $status = '<span class="badge bg-success">Assigned</span>';
            }
            else if ($item->cleaner_assigned_status == 2)
            {
                $status = '<span class="badge bg-yellow">Partial</span>';
            }
            else if ($item->cleaner_assigned_status == 0)
            {
                $status = '<span class="badge bg-red">Unassigned</span>';
            }
            else {
                $status = '';
            }
            
            $quotation = Quotation::find($item->quotation_id);

            // discount
            if($quotation->discount_type == "percentage")
            {
                $discount_amt = $quotation->amount * ($quotation->discount / 100);
            }
            elseif($quotation->discount_type == "amount")
            {
                $discount_amt = $quotation->discount;
            }
            else
            {
                $discount_amt = 0;
            }

            // service address
            $service_address = ServiceAddress::find($quotation->service_address ?? '');  

            // billing address
            $billing_address = BillingAddress::find($quotation->billing_address ?? '');  
            
            // schedule
            $schedule = ScheduleModel::where('sales_order_id', $item->id)->first();

            if(!empty($schedule->days))
            {
                $schedule_days_arr = explode(', ', $schedule->days);
                $new_service_date_arr = [];
                foreach($schedule_days_arr as $list)
                {
                    $new_service_date_arr[] = date('d-m-y', strtotime($list));
                }
    
                $new_service_date = implode(', ', $new_service_date_arr);
            }
            else
            {
                $new_service_date = "";
            }

            // customer
            $customer = crm::find($item->customer_id);    
            
            if($item->customer_type == "commercial_customer_type")
            {
                $company_info = CompanyInfo::where('customer_id', $item->customer_id)->first();     
            }

            // customer service type
            $customer_service_types_arr = DB::table('customer_service_types')->where('customer_id', $item->customer_id)->pluck('service_type_id')->toarray();
            $service_types_arr = ServiceType::whereIn('id', $customer_service_types_arr)->pluck('service_type')->toarray();
            $customer_service_types = implode(', ', $service_types_arr);

            // cleaner name
            // $schedule_details = ScheduleDetails::where('sales_order_id', $item->id)
            //                                     ->WhereNotNull('employee_id')
            //                                     ->get();

            // $xin_employees_id = [];
            // $xin_teams_id = [];
            // $cleaner_type = "";

            // foreach($schedule_details as $list)
            // {             
            //     if($list->cleaner_type == "team")
            //     {
            //         if(!in_array($list->employee_id, $xin_employees_id))
            //         {
            //             $xin_teams_id[] = $list->employee_id;
            //         }
                    
            //         $cleaner_type = 'team';
            //     }
            //     elseif($list->cleaner_type == "individual")
            //     {
            //         if(!in_array($list->employee_id, $xin_employees_id))
            //         {
            //             $xin_employees_id[] = $list->employee_id;
            //         }
                    
            //         $cleaner_type = 'individual';
            //     }
            // }

            // if($cleaner_type == "team")
            // {
            //     $xin_team = DB::table('xin_team')->whereIn('team_id', $xin_teams_id)->pluck('team_name')->toArray();
            //     $cleaner_name = implode(", ", $xin_team);
            // }
            // else if($cleaner_type == "individual")
            // {
            //     $xin_employees = DB::table('xin_employees')->whereIn('user_id', $xin_employees_id)->get();

            //     $xin_employees_arr = [];

            //     foreach($xin_employees as $emp)
            //     {
            //         $xin_employees_arr[] = $emp->first_name . " " . $emp->last_name;
            //     }

            //     $cleaner_name = implode(", ", $xin_employees_arr);
            // }
            // else
            // {
            //     $cleaner_name = "";
            // }

            // payment mode
            // $LeadPaymentInfo = LeadPaymentInfo::where('quotation_id', $item->quotation_id)
            //                                     ->where('payment_status', 1)
            //                                     ->pluck('payment_method')
            //                                     ->toArray();

            // $payment_method = implode(', ', $LeadPaymentInfo);

            $LeadPaymentInfo = LeadPaymentInfo::where('quotation_id', $item->quotation_id)
                                                ->where('payment_status', 1)
                                                ->get();

            $payment_method = "";

            foreach ($LeadPaymentInfo as $list) 
            {
                $offline_payment_details = LeadOfflinePaymentDetail::where('lead_payment_id', $list->id)->get();

                if(!$offline_payment_details->isEmpty())
                {
                    $payment_options_arr = [];

                    foreach($offline_payment_details as $off_pay)
                    {
                        $payment_options_arr[] = $off_pay->payment_option;
                    }

                    $payment_options = implode(", ", $payment_options_arr);
                }
                else
                {
                    $payment_options = "";
                }

                if(!empty($payment_options))
                {
                    $payment_method .= ucfirst($list->payment_method) . " (". $payment_options .")" . ", ";
                }
                else
                {
                    $payment_method .= ucfirst($list->payment_method) . ", ";
                }
            }

            // job status

            if ($item->job_status == 2)
            {
                $job_status = '<span class="badge bg-success">Completed</span>';
            }
            else if ($item->job_status == 1)
            {
                $job_status = '<span class="badge bg-warning">Work in Progress</span>';
            }
            else if ($item->job_status == 0)
            {
                $job_status = '<span class="badge bg-blue">Pending</span>';
            }
            else if ($item->job_status == 3)
            {
                $job_status = '<span class="badge bg-red">Cancelled</span>';
            }
            else {
                $job_status = '';
            }

            $new_data[] = [
                $key + 1,        
                $item->invoice_no ?? '',  
                $status ?? '',      
                $new_service_date ?? '',
                (!empty($schedule->endDate) ? date('d-m-y', strtotime($schedule->endDate)) : ''),
                ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->individual_company_name,
                $item->id ?? '',
                // $cleaner_name ?? '',                
                $customer_service_types,              
                ($item->customer_type == "commercial_customer_type") ? $company_info->contact_name : '',
                $customer->mobile_number ?? '',
                $customer->email ?? '',
                $service_address->address??'' .", ". $service_address->unit_number??'',
                $billing_address->address??'' .", ". $service_address->unit_number??'',
                $service_address->zone ?? '',
                '',
                rtrim(trim($payment_method), ","),
                '',
                '',
                "$" . number_format($discount_amt ?? 0, 2),
                "$" . number_format($quotation->amount ?? 0, 2),
                "$" . number_format($quotation->tax ?? 0, 2) . " (". $quotation->tax_percent . "%)",
                "$" . number_format($quotation->grand_total ?? 0, 2),
                '',                       
                // $job_status ?? ''
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['sales_order']->count(),
            "recordsFiltered" => $data['sales_order']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    // job order details table data

    public function job_order_details_table_data(Request $request)
    {
        $job_order_details = ScheduleModel::leftJoin('customers', 'customers.id', '=', 'tble_schedule.customer_id')         
                                ->leftJoin('tble_schedule_details', 'tble_schedule_details.tble_schedule_id', '=', 'tble_schedule.id')    
                                ->leftJoin('sales_order', 'sales_order.id', '=', 'tble_schedule.sales_order_id')                  
                                ->leftJoin('company', 'company.id', '=', 'sales_order.company_id')                    
                                ->select('tble_schedule_details.*', 
                                        'tble_schedule.customer_id', 
                                        'tble_schedule.total_session',
                                        'tble_schedule.man_power',
                                        'sales_order.invoice_no', 
                                        'sales_order.company_id', 
                                        'sales_order.quotation_id', 
                                        'company.company_name', 
                                        'customers.customer_type', 
                                        'customers.customer_name', 
                                        'customers.individual_company_name')
                                ->orderBy('tble_schedule_details.schedule_date', 'asc');

        // filter start

        if($request->filled('filter_job_order_id'))
        {
            $job_order_details = $job_order_details->where('tble_schedule_details.id', $request->filter_job_order_id);
        }

        if($request->filled('filter_job_order_id_from') && $request->filled('filter_job_order_id_to'))
        {
            // $job_order_details = $job_order_details->where('tble_schedule_details.id', $request->filter_job_order_id);

            $start_job_order_id  = $request->filter_job_order_id_from;
            $end_job_order_id = $request->filter_job_order_id_to;

            $job_order_details = $job_order_details->whereBetween('tble_schedule_details.sales_order_id', [$start_job_order_id, $end_job_order_id]);
        }

        if($request->filled('filter_customer_name'))
        {
            $job_order_details = $job_order_details->where(function ($query) use ($request) {
                            $query->where('customers.customer_name', 'like', '%' . $request->filter_customer_name . '%')
                                ->orWhere('customers.individual_company_name', 'like', '%' . $request->filter_customer_name . '%');
                        });
        }

        if($request->filled('filter_service_date'))
        {
            $temp_date_arr = explode(' - ', $request->filter_service_date);

            $from_date = date('Y-m-d', strtotime($temp_date_arr[0]));
            $to_date = date('Y-m-d', strtotime($temp_date_arr[1]));

            $job_order_details = $job_order_details->whereBetween('tble_schedule_details.schedule_date', [$from_date, $to_date]);           
        }

        if($request->filled('filter_service_type'))
        {
            $job_order_details = $job_order_details->where('sales_order.company_id', $request->filter_service_type);
        }

        if($request->filled('filter_job_status'))
        {
            $job_order_details = $job_order_details->where('tble_schedule_details.job_status', $request->filter_job_status);
        }

        // New filter for $job_order_report_filter_stage
        if ($request->filled('filter_stage')) 
        {
            $filter_stage = $request->filter_stage;
            
            $job_order_details = $job_order_details->where(function ($query) use ($filter_stage) {
                if ($filter_stage == 3) {
                    $query->where('tble_schedule_details.job_status', 3); // Cancelled
                }
                else{
                    if ($filter_stage == 0) {
                        $query->whereNull('tble_schedule_details.employee_id') // Unassigned
                                ->where('tble_schedule_details.job_status', '!=', 3);
                    } elseif ($filter_stage == 1) {
                        $query->where(function ($subquery) {
                            $subquery->whereNotNull('tble_schedule_details.employee_id')
                                    ->whereColumn('tble_schedule.man_power', '=', DB::raw('LENGTH(tble_schedule_details.employee_id) - LENGTH(REPLACE(tble_schedule_details.employee_id, ",", "")) + 1')); // Assign
                        });
                    } elseif ($filter_stage == 2) {
                        $query->whereNotNull('tble_schedule_details.employee_id')
                            ->whereRaw('LENGTH(tble_schedule_details.employee_id) - LENGTH(REPLACE(tble_schedule_details.employee_id, ",", "")) + 1 < tble_schedule.man_power'); // Partial
                    }
                }           
            });
        }

        // filter end 

        $job_order_details = $job_order_details->get();

        // filter stage start

        // foreach ($job_order_details as $item) 
        // {
        //     if ($item->job_status == 3)
        //     {
        //         $item->stage = 3;            
        //     }
        //     else
        //     {
        //         if(empty($item->employee_id))
        //         {
        //             $item->stage = 0;
        //         }
        //         else
        //         {
        //             if($item->cleaner_type == "team")
        //             {
        //                 $item->stage = 1;
        //             }
        //             elseif($item->cleaner_type == "individual")
        //             {
        //                 $count_emp = count(explode(',', $item->employee_id));

        //                 if($item->man_power == $count_emp)
        //                 {
        //                     $item->stage = 1;
        //                 }
        //                 else
        //                 {
        //                     $item->stage = 2;
        //                 }
        //             }                   
        //         }
        //     }
        // }

        // if($request->filled('filter_stage'))
        // {
        //     $job_order_details = $job_order_details->where('stage', $request->filter_stage);
        // }     

        // filter stage end

        $data['job_order_details'] = $job_order_details;

        $new_data = [];

        $temp = [];

        foreach ($data['job_order_details'] as $key => $item) 
        {
            // job status

            if ($item->job_status == 2)
            {
                $status = '<span class="badge bg-success">Completed</span>';
            }
            else if ($item->job_status == 0)
            {
                $status = '<span class="badge bg-blue">Pending</span>';
            }
            else if ($item->job_status == 1)
            {
                $status = '<span class="badge bg-yellow">Work In Progress</span>';
            }
            else if ($item->job_status == 3)
            {
                $status = '<span class="badge bg-danger">Cancelled</span>';
            }
            else {
                $status = '';
            }

            // cleaner name

            if($item->cleaner_type == "team")
            {
                // $tble_schedule_employee = DB::table('tble_schedule_employee')
                //                                 ->where('tble_schedule_details_id', $item->id)
                //                                 ->first();
              
                // $team = DB::table('xin_team')->where('team_id', $tble_schedule_employee->employee_id)->first();

                $team = DB::table('xin_team')->where('team_id', $item->employee_id)->first();

                $emp_name = "";

                if($team)
                {
                    $temp_emp = explode(",", $team->employee_id);

                    $name_arr = [];

                    foreach($temp_emp as $list)
                    {
                        $xin_employees = DB::table('xin_employees')->where('user_id', $list)->first();

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

                    if(isset($name_arr))
                    {
                        $emp_name = implode(", ", $name_arr);
                    }
                }

                $item->employee_name = $emp_name;
            }
            elseif($item->cleaner_type == "individual")
            {
                $emp_arr = explode(',', $item->employee_id);

                // $emp_arr = DB::table('tble_schedule_employee')
                //                 ->where('tble_schedule_details_id', $item->id)
                //                 ->pluck('employee_id')
                //                 ->toarray();

                $xin_employees = DB::table('xin_employees')
                                    ->whereIn('xin_employees.user_id', $emp_arr)
                                    ->get();

                $emp_name_arr = [];

                foreach($xin_employees as $loop_emp)
                {                  
                    $name = $loop_emp->first_name . " " . $loop_emp->last_name;

                    $emp_name_arr[] = $name;                                    
                }
                
                $item->employee_name = implode(', ', $emp_name_arr);
            }

            // number of session / total sessions start

            $temp[$item->sales_order_id][] = [$item->schedule_date];

            $searchDate = $item->schedule_date;
            $search_key = $item->sales_order_id;

            // Extract the column of dates from the array
            $dates_arr = array_column($temp[$search_key], 0);

            // Find the position of the target date
            $position = array_search($searchDate, $dates_arr);

            // number of session / total sessions end

            // Stage (Assignment of cleaner) start

            if ($item->job_status == 3)
            {
                // $item->stage = 3;
                $stage = '<span class="badge bg-danger">Cancelled</span>';
            }
            else
            {
                if(empty($item->employee_id))
                {
                    // $item->stage = 0;
                    $stage = '<span class="badge bg-primary">Unassigned</span>';
                }
                else
                {
                    if($item->cleaner_type == "team")
                    {
                        // $item->stage = 1;
                        $stage = '<span class="badge bg-success">Assign</span>';
                    }
                    elseif($item->cleaner_type == "individual")
                    {
                        $count_emp = count(explode(',', $item->employee_id));

                        if($item->man_power == $count_emp)
                        {
                            // $item->stage = 1;
                            $stage = '<span class="badge bg-success">Assign</span>';
                        }
                        else
                        {
                            // $item->stage = 2;
                            $stage = '<span class="badge bg-warning">Partial</span>';
                        }
                    }                   
                }
            }

            // Stage (Assignment of cleaner) end

            // quotation
            $quotation = Quotation::find($item->quotation_id);
            
            // service address
            $service_address = ServiceAddress::find($quotation->service_address ?? ''); 

            // quotation service name
            $service_name_array = QuotationServiceDetail::where('quotation_id', $item->quotation_id)->pluck('name')->toArray();

            $new_data[] = [
                $key + 1,
                ($position !== false) ? ($position + 1) ."/".$item->total_session : '',
                date('d-m-Y', strtotime($item->schedule_date)),
                date('h:i A', strtotime(($item->startTime))) . " - " . date('h:i A', strtotime(($item->endTime))),
                $stage,
                ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->individual_company_name,
                $service_address->address??'' .", ". $service_address->unit_number??'',
                $item->invoice_no,
                implode(', ', $service_name_array),
                $item->remarks,
                "$".number_format($quotation->amount, 2),
                "$" . number_format($quotation->tax, 2) . " (". $quotation->tax_percent . "%)",
                "$".number_format($quotation->grand_total, 2),
                $item->company_name,
                $item->employee_name,
                $status,
                ucfirst($item->cleaner_type),
                $item->sales_order_id
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['job_order_details']->count(),
            "recordsFiltered" => $data['job_order_details']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    // log details table data

    public function log_details_table_data(Request $request)
    {
        $log_details = DB::table('log_details')->get();

        $data['log_details'] = $log_details;

        $new_data = [];

        foreach ($data['log_details'] as $key => $item) 
        { 
            $new_data[] = [
                $key + 1,
                ucfirst(str_replace("_", " ", $item->module)),
                $item->activity,
                $item->ref_no,
                $item->created_by_name,
                date('d-M-Y h:i A', strtotime($item->created_at))
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['log_details']->count(),
            "recordsFiltered" => $data['log_details']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }


    // reminder log report table data

    public function reminder_log_report_table_data(Request $request)
    {
        $result = DB::table('appointment_reminder_email')
                    ->leftJoin('customers', 'customers.id', '=', 'appointment_reminder_email.customer_id')
                    ->select('appointment_reminder_email.*', 'customers.customer_type', 'customers.customer_name', 'customers.individual_company_name')
                    ->get();

        $new_data = [];

        foreach ($result as $key => $item) 
        {
            $new_data[] = [
                $key + 1,
                $item->customer_type ?? '' == "residential_customer_type" ? $item->customer_name ?? '': $item->individual_company_name ?? '',
                $item->customer_email,
                date('d-m-Y', strtotime($item->schedule_date)),
                date('d-M-Y h:i A', strtotime($item->created_at))
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $result->count(),
            "recordsFiltered" => $result->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }
}
