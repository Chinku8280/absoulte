<?php

namespace App\Http\Controllers;

use App\Models\Crm;
use App\Models\Lead;
use App\Models\LeadPaymentInfo;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller {

    public function old_home() 
    {
        // return Carbon::now()->month;

        $data['heading_name']  = 'Dashboard';

        $data['pending_leads'] = Lead::join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
                                        ->where('lead_customer_details.status', 2)
                                        ->orderBy('lead_customer_details.created_at', 'desc')
                                        ->select('lead_customer_details.*', 'customers.customer_name as customer_name', 'customers.individual_company_name as individual_company_name', 'customers.customer_type as customer_type', 'customers.mobile_number as mobile_number',)
                                        ->get();

        // todays sales order

        $data['todays_sales_order'] = SalesOrder::wheredate('sales_order.created_at', date('Y-m-d'))
                                                    ->join('quotations', 'quotations.id', '=', 'sales_order.quotation_id')
                                                    ->select('sales_order.*', 'quotations.grand_total')
                                                    ->get();

        // this month sales order

        $data['this_month_sales_order'] = SalesOrder::whereBetween('sales_order.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                                                        ->join('quotations', 'quotations.id', '=', 'sales_order.quotation_id')
                                                        ->select('sales_order.*', 'quotations.grand_total')
                                                        ->get();

        // todays sales order amount

        $data['todays_total_amount'] = 0;

        foreach($data['todays_sales_order'] as $item)
        {
            $data['todays_total_amount'] += $item->grand_total;
        }

        // this month sales order amount

        $data['this_month_total_amount'] = 0;

        foreach($data['this_month_sales_order'] as $item)
        {
            $data['this_month_total_amount'] += $item->grand_total;
        }

        // total customers

        $data['total_customers'] = count(Crm::all());

        // total active customers

        $data['total_active_customers'] = count(Crm::where('status', 1)->get());

        // total sales order
        $data['total_sales_order'] = count(SalesOrder::all());

        // total services
        $data['total_services'] = count(Services::all());

        // total residential customers
        $data['total_residential_customers'] = count(Crm::where('customer_type', 'residential_customer_type')->get());

        // total commercial customers
        $data['total_commercial_customers'] = count(Crm::where('customer_type', 'commercial_customer_type')->get());


        // chart start

        $year_data = [];

        for($year=2023; $year<=date('Y'); $year++)
        {        
            $year_data[] = $year;
        }

        $data['year_data'] = $year_data;

        // chart end

        // return $data;

        return view( 'dashboard.old-dashboard', $data);
    }

    // view dashboard

    public function index() 
    {
        // todays sales order

        $data['todays_sales_order'] = SalesOrder::wheredate('sales_order.created_at', date('Y-m-d'))
                                                    ->join('quotations', 'quotations.id', '=', 'sales_order.quotation_id')
                                                    ->select('sales_order.*', 'quotations.grand_total')
                                                    ->get();

        // this month sales order

        $data['this_month_sales_order'] = SalesOrder::whereBetween('sales_order.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                                                        ->join('quotations', 'quotations.id', '=', 'sales_order.quotation_id')
                                                        ->select('sales_order.*', 'quotations.grand_total')
                                                        ->get();

        // todays sales order amount

        $data['todays_total_amount'] = 0;

        foreach($data['todays_sales_order'] as $item)
        {
            $data['todays_total_amount'] += $item->grand_total;
        }

        // this month sales order amount

        $data['this_month_total_amount'] = 0;

        foreach($data['this_month_sales_order'] as $item)
        {
            $data['this_month_total_amount'] += $item->grand_total;
        }

        // total customers

        $data['total_customers'] = count(Crm::all());

        // total active customers

        $data['total_active_customers'] = count(Crm::where('status', 1)->get());

        // total sales order
        $data['total_sales_order'] = count(SalesOrder::all());

        // total services
        $data['total_services'] = count(Services::all());

        // total residential customers
        $data['total_residential_customers'] = count(Crm::where('customer_type', 'residential_customer_type')->get());

        // total commercial customers
        $data['total_commercial_customers'] = count(Crm::where('customer_type', 'commercial_customer_type')->get());

        $data['pending_leads'] = Lead::join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
                                        ->where('lead_customer_details.status', 2)
                                        ->orderBy('lead_customer_details.created_at', 'desc')
                                        ->select('lead_customer_details.*', 'customers.customer_name as customer_name', 'customers.individual_company_name as individual_company_name', 'customers.customer_type as customer_type', 'customers.mobile_number as mobile_number',)
                                        ->get();

        // sales order chart year start

        $year_data = [];

        for($year=2023; $year<=date('Y'); $year++)
        {        
            $year_data[] = $year;
        }

        $data['year_data'] = $year_data;

        // sales order chart year end

        // Scheduled Jobs (days)
        $today_schedule_job = ScheduleDetails::where('schedule_date', date('Y-m-d'))->get();
        $data['today_total_schedule_job'] = count($today_schedule_job);

        // Completed Scheduled Jobs (days)
        $today_completed_schedule_job = ScheduleDetails::where('schedule_date', date('Y-m-d'))->where('job_status', 2)->get();
        $data['today_total_completed_schedule_job'] = count($today_completed_schedule_job);

        // Pending Scheduled Jobs (days)
        $today_pending_schedule_job = ScheduleDetails::where('schedule_date', date('Y-m-d'))->where('job_status', 0)->get();
        $data['today_total_pending_schedule_job'] = count($today_pending_schedule_job);

        // Outstanding Payments

        $total_amount = Quotation::WhereNotNull('invoice_no')->sum('grand_total');

        $payment_amount = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
                                        ->WhereNotNull('quotations.invoice_no')
                                        ->where('lead_payment_detail.payment_status', 1)
                                        ->sum('lead_payment_detail.payment_amount');

        // Outstanding Payments
        $data['outstanding_amount'] = $total_amount - $payment_amount;  
        
        // Payments Received
        $data['received_payment'] = $payment_amount;

        // total invoice
        $data['total_invoice'] = count(SalesOrder::all());

        // due payment && pending payment
        $due_pending_payment_sales_order = SalesOrder::join('customers', 'customers.id', '=', 'sales_order.customer_id')
                                ->leftJoin('payment_terms', 'payment_terms.id', '=', 'customers.payment_terms')
                                ->join('quotations', 'quotations.id', '=', 'sales_order.quotation_id')
                                ->whereIn('quotations.payment_status', ['unpaid', 'partial_paid'])
                                ->select('sales_order.*', 'quotations.grand_total', 'quotations.payment_status', 
                                'customers.customer_type', 'customers.customer_name', 
                                'customers.individual_company_name', 'customers.payment_terms as payment_terms_id',
                                'payment_terms.payment_terms', 'payment_terms.payment_terms_value')
                                ->get();

        $due_payment = 0;
        $pending_payment = 0;

        foreach($due_pending_payment_sales_order as $item)
        {
            if(!empty($item->payment_terms_value))
            {
                if($item->payment_terms_value > 0)
                {
                    $item->due_date = date('Y-m-d', strtotime($item->created_at . ' + ' . $item->payment_terms_value . ' days'));
                }
                else
                {
                    $item->due_date = "";
                }
            }
            else
            {
                $item->due_date = "";
            }

            if(!empty($item->due_date))
            {
                if(date('Y-m-d') > date('Y-m-d', strtotime($item->due_date)))
                {
                    $due_payment += 1;
                }
                else
                {              
                    $pending_payment += 1;
                }
            }
        }

        $data['due_pending_payment_sales_order'] = $due_pending_payment_sales_order;
        $data['pending_payment'] = $pending_payment;
        $data['due_payment'] = $due_payment;

        // draft lead
        $data['total_draft_leads'] = count(Lead::where('status', 0)->whereYear('created_at', date('Y'))->get());

        // leads
        $data['total_leads'] = count(Lead::where('status', '!=', 0)->whereYear('created_at', date('Y'))->get());

        // Sent Quotation
        $data['total_sent_quotation'] = count(Quotation::where('status', 3)->where('payment_advice', 2)->where('quotation_type', 1)->whereYear('created_at', date('Y'))->get());

        // In Progress Quotation
        $data['total_progress_quotation'] = count(Quotation::where('status', 2)->where('quotation_type', 1)->whereYear('created_at', date('Y'))->get());

        // Expiring Quotation
        $pending_quotation = Quotation::where('status', 1)->where('quotation_type', 1)->whereYear('created_at', date('Y'))->get();
        $expired_quotation = 0;

        foreach($pending_quotation as $item)
        {
            $item->expire_date = date('Y-m-d', strtotime($item->created_at. ' + 14 days'));

            if(date('Y-m-d') > date('Y-m-d', strtotime($item->expire_date)))
            {
                $expired_quotation += 1;
            }
        }

        $data['total_expired_quotation'] = $expired_quotation;

        // Approved Quotation
        $data['total_approved_quotation'] = count(Quotation::where('status', 4)->where('quotation_type', 1)->whereYear('created_at', date('Y'))->get());

        $data['pending_quotation'] = Quotation::leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
                                                ->where('quotations.status', 1)
                                                ->where('quotations.quotation_type', 1)
                                                ->select('quotations.*', 'customers.customer_type', 
                                                'customers.customer_name', 'customers.individual_company_name')
                                                ->orderBy('quotations.created_at', 'desc')
                                                ->get();

        foreach($data['pending_quotation'] as $item)
        {
            $item->expire_date = date('Y-m-d', strtotime($item->created_at. ' + 14 days'));
        }

        // Unassigned Sales Orders
        $data['total_unassigned_sales_order'] = count(SalesOrder::where('cleaner_assigned_status', 0)->whereYear('created_at', date('Y'))->get());

        // Partially Assigned Sales Orders
        $data['total_partially_assigned_sales_order'] = count(SalesOrder::where('cleaner_assigned_status', 2)->whereYear('created_at', date('Y'))->get());

        // Compleing Jobs
        $data['completing_jobs'] = SalesOrder::join('tble_schedule', 'tble_schedule.sales_order_id', '=', 'sales_order.id')
                                            ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
                                            ->select('sales_order.*', 'customers.customer_type', 'customers.customer_name', 'customers.individual_company_name')
                                            ->get();

        foreach($data['completing_jobs'] as $item)
        {
            $item->balance_job = count(ScheduleDetails::where('sales_order_id', $item->id)
                                                ->where('job_status', 0)
                                                ->get());
        }

        // return $data['completing_jobs'];

        // sales order unassign date start

        $salesOrder = SalesOrder::leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
                                ->where('sales_order.status', 2)
                                ->select(
                                    'sales_order.*',          
                                    'customers.customer_name',
                                    'customers.individual_company_name',                                  
                                    'customers.customer_type')
                                ->orderBy('sales_order.created_at', 'desc')
                                ->get();

        foreach ($salesOrder as $key => $value) 
        {
            $quotation = Quotation::find($value->quotation_id);

            // total amount
            $value->total_amount = $quotation->grand_total ?? 0;                
            
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

        $data['sales_order'] = $salesOrder;

        // sales order unassign date end

        // return $data;

        return view('dashboard.dashboard', $data);
    }

    // get_sales_order_chart_data

    public function get_sales_order_chart_data(Request $request)
    {
        // return $request->all();

        $year = $request->chart_filter;

        for($month=1; $month<=12; $month++)
        {
            if($month < 10)
            {
                $month = "0".$month;
            }

            $monthly_sales[] = count(SalesOrder::whereMonth('created_at', $month)
                                    ->whereYear('created_at', $year)
                                    ->get());

            $monthly_key[] = date("F", mktime(0, 0, 0, $month, 10));
        }

        $data['data'] = $monthly_sales;
        $data['key'] = $monthly_key;

        return response()->json($data);
    }

    // get_schedule_jobs_count

    public function get_schedule_jobs_count(Request $request)
    {
        $schedule_jobs_btn_value = $request->schedule_jobs_btn_value;

        if($schedule_jobs_btn_value == "day")
        {
            // Scheduled Jobs (days)
            $today_schedule_job = ScheduleDetails::where('schedule_date', date('Y-m-d'))->get();
            $data['total_schedule_job'] = count($today_schedule_job);

            // Completed Scheduled Jobs (days)
            $today_completed_schedule_job = ScheduleDetails::where('schedule_date', date('Y-m-d'))->where('job_status', 2)->get();
            $data['total_completed_schedule_job'] = count($today_completed_schedule_job);

            // Pending Scheduled Jobs (days)
            $today_pending_schedule_job = ScheduleDetails::where('schedule_date', date('Y-m-d'))->where('job_status', 0)->get();
            $data['total_pending_schedule_job'] = count($today_pending_schedule_job);
        }
        else if($schedule_jobs_btn_value == "week")
        {
            // Scheduled Jobs (week)
            $this_week_schedule_job = ScheduleDetails::whereBetween('schedule_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
            $data['total_schedule_job'] = count($this_week_schedule_job);

            // Completed Scheduled Jobs (week)
            $this_week_completed_schedule_job = ScheduleDetails::whereBetween('schedule_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('job_status', 2)->get();
            $data['total_completed_schedule_job'] = count($this_week_completed_schedule_job);

            // Pending Scheduled Jobs (week)
            $this_week_pending_schedule_job = ScheduleDetails::whereBetween('schedule_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('job_status', 0)->get();
            $data['total_pending_schedule_job'] = count($this_week_pending_schedule_job);
        }
        else if($schedule_jobs_btn_value == "month")
        {
            // Scheduled Jobs (month)
            $this_month_schedule_job = ScheduleDetails::whereBetween('schedule_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
            $data['total_schedule_job'] = count($this_month_schedule_job);

            // Completed Scheduled Jobs (month)
            $this_month_completed_schedule_job = ScheduleDetails::whereBetween('schedule_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('job_status', 2)->get();
            $data['total_completed_schedule_job'] = count($this_month_completed_schedule_job);

            // Pending Scheduled Jobs (month)
            $this_month_pending_schedule_job = ScheduleDetails::whereBetween('schedule_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('job_status', 0)->get();
            $data['total_pending_schedule_job'] = count($this_month_pending_schedule_job);
        }
        else
        {
            $data['total_schedule_job'] = 0;
            $data['total_completed_schedule_job'] = 0;
            $data['total_pending_schedule_job'] = 0;
        }

        return response()->json($data);
    }

    // get_leads_chart_data

    public function get_leads_chart_data(Request $request)
    {
        // return $request->all();

        $year = $request->chart_filter;

        // draft lead
        $data['total_draft_leads'] = count(Lead::where('status', 0)->whereYear('created_at', $year)->get());

        // leads
        $data['total_leads'] = count(Lead::where('status', '!=', 0)->whereYear('created_at', $year)->get());

        return response()->json($data);
    }

    // get_quotation_chart_data

    public function get_quotation_chart_data(Request $request)
    {
        // return $request->all();

        $year = $request->chart_filter;

        // Sent Quotation
        $data['total_sent_quotation'] = count(Quotation::where('status', 3)->where('payment_advice', 2)->where('quotation_type', 1)->whereYear('created_at', $year)->get());

        // In Progress Quotation
        $data['total_progress_quotation'] = count(Quotation::where('status', 2)->where('quotation_type', 1)->whereYear('created_at', $year)->get());

        // Expiring Quotation
        $pending_quotation = Quotation::where('status', 1)->where('quotation_type', 1)->whereYear('created_at', $year)->get();
        $expired_quotation = 0;

        foreach($pending_quotation as $item)
        {
            $item->expire_date = date('Y-m-d', strtotime($item->created_at. ' + 14 days'));

            if(date('Y-m-d') > date('Y-m-d', strtotime($item->expire_date)))
            {
                $expired_quotation += 1;
            }
        }

        $data['total_expired_quotation'] = $expired_quotation;

        // Approved Quotation
        $data['total_approved_quotation'] = count(Quotation::where('status', 4)->where('quotation_type', 1)->whereYear('created_at', $year)->get());

        return response()->json($data);
    }

    // get_job_order_chart_data

    public function get_job_order_chart_data(Request $request)
    {
        // return $request->all();

        $year = $request->chart_filter;

        // Unassigned Sales Orders
        $data['total_unassigned_sales_order'] = count(SalesOrder::where('cleaner_assigned_status', 0)->whereYear('created_at', $year)->get());

        // Partially Assigned Sales Orders
        $data['total_partially_assigned_sales_order'] = count(SalesOrder::where('cleaner_assigned_status', 2)->whereYear('created_at', $year)->get());
    
        $monthly_unassigned_sales_order_data = [];
        $monthly_partially_assigned_sales_order_data = [];

        for($month=1; $month<=12; $month++)
        {
            if($month < 10)
            {
                $month = "0".$month;
            }

            $monthly_unassigned_sales_order_data[] = count(SalesOrder::where('cleaner_assigned_status', 0)
                                                        ->whereMonth('created_at', $month)
                                                        ->whereYear('created_at', $year)
                                                        ->get());

            $monthly_partially_assigned_sales_order_data[] = count(SalesOrder::where('cleaner_assigned_status', 2)
                                                        ->whereMonth('created_at', $month)
                                                        ->whereYear('created_at', $year)
                                                        ->get());

            $monthly_key[] = date("F", mktime(0, 0, 0, $month, 10));
        }

        $data['month_key'] = $monthly_key;
        $data['monthly_unassigned_sales_order_data'] = $monthly_unassigned_sales_order_data;
        $data['monthly_partially_assigned_sales_order_data'] = $monthly_partially_assigned_sales_order_data;

        return response()->json($data);
    }

}
