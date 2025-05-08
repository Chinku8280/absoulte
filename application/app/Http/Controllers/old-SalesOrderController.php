<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\LeadServices;
use App\Models\Company;
use App\Models\Crm;
use App\Models\LeadPrice;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;

use App\Models\ServiceAddress;
use App\Models\Territory;
use App\Models\TermCondition;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ScheduleModel;
use App\Models\Services;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrder = SalesOrder::select(
            'sales_order.id',
            'sales_order.status',
            'sales_order.customer_id',
            'sales_order.sales_order_no',
            'sales_order.created_at',
            'sales_order.invoice_no',
            'customers.id as custm_id',
            'customers.customer_name',
            'customers.individual_company_name',
            'customers.email',
            'customers.mobile_number',
            'customers.customer_type',
            \DB::raw('(SELECT MAX(id) FROM tble_schedule WHERE tble_schedule.sales_order_no = sales_order.sales_order_no) as tble_schedule_id')
        )
            ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
            ->get();

        $data = [];

        foreach ($salesOrder as $key => $value) {
            $record = SalesOrder::leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
                ->where('sales_order.id', $value->id)
                ->first();

            $data[] = $record;
        }

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
        $company = Company::get();
        // dd($users);
        return view('admin.salesOrder.index', compact('users', 'get_team', 'data', 'salesOrder', 'company', 'employeeNames'));
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

        // dd($employeeNames);
        return $employeeNames;
    }


    // public function view(Request $request)
    // {
    //     // $salesOrder = SalesOrder::select('sales_order.id', 'sales_order.customer_id', 'sales_order.lead_id', 'sales_order.quotation_id', 'sales_order.sales_order_no', 'sales_order.created_at', 'sales_order.status as sales_status', 'customers.id as custm_id', 'customers.customer_name', 'customers.territory', 'customers.email', 'customers.mobile_number', 'customers.customer_type')
    //     //     ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')->first();

    //     $salesOrder = SalesOrder::select('sales_order.id', 'sales_order.customer_id', 'sales_order.lead_id', 'sales_order.quotation_id', 'sales_order.sales_order_no', 'sales_order.created_at', 'sales_order.status as sales_status', 'customers.id as custm_id', 'customers.customer_name', 'customers.territory', 'customers.email', 'customers.mobile_number', 'customers.customer_type', 'customers.territory')
    //         ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
    //         ->where('sales_order.id', $request->salesOrderId)
    //         ->first();

    //     //dd($salesOrder);

    //     $service_address = ServiceAddress::where('customer_id', $salesOrder->customer_id)->first();
    //     $billing_address = BillingAddress::where('customer_id', $salesOrder->customer_id)->first();
    //     $company = Company::get();
    //     $territory = Territory::get();

    //     if ($salesOrder->lead_id === null) {
    //         // If lead_id is null, check QuotationServiceDetail
    //         $serviceDetails = QuotationServiceDetail::where('quotation_id', $salesOrder->quotation_id)->get();
    //     } elseif ($salesOrder->lead_id !== null) {
    //         // If lead_id is not null, check LeadServices
    //         $serviceDetails = LeadServices::where('lead_id', $salesOrder->lead_id)->get();
    //     }
    //     // $serviceDetails = LeadServices::where('lead_id', $salesOrder->lead_id)->get();
    //     //  dd($serviceDetails);
    //     $assign_cleaner = DB::table('tble_schedule')->where('sales_order_no', $salesOrder->sales_order_no)->get();

    //     $quotation = Quotation::where('id', $salesOrder->quotation_id)->first();
    //     $companyDetail = Company::where('id', $quotation->company_id)->first();
    //     // echo"<pre>"; print_r($companyDetail); exit;
    //     $termCondition = TermCondition::where('company_id', $companyDetail->id)->get();
    //     $priceInfo = LeadPrice::get();
    //     //dd($salesOrder);
    //     return view('admin.salesOrder.view', compact('salesOrder', 'companyDetail', 'territory', 'assign_cleaner', 'service_address', 'billing_address', 'company', 'serviceDetails', 'termCondition', 'priceInfo'));
    // }

    public function view(Request $request)
    {
        // $salesOrder = SalesOrder::select('sales_order.id', 'sales_order.customer_id', 'sales_order.lead_id', 'sales_order.quotation_id', 'sales_order.sales_order_no', 'sales_order.created_at', 'sales_order.status as sales_status', 'customers.id as custm_id', 'customers.customer_name', 'customers.territory', 'customers.email', 'customers.mobile_number', 'customers.customer_type')
        //     ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')->first();

        $salesOrder = SalesOrder::select('sales_order.id', 'sales_order.customer_id', 'sales_order.lead_id', 'sales_order.quotation_id', 'sales_order.sales_order_no', 'sales_order.created_at', 'sales_order.status as sales_status', 'customers.id as custm_id', 'customers.customer_name', 'customers.territory', 'customers.email', 'customers.mobile_number', 'customers.customer_type', 'customers.territory')
            ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
            ->where('sales_order.id', $request->salesOrderId)
            ->first();

        //dd($salesOrder);

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
        
        // $serviceDetails = LeadServices::where('lead_id', $salesOrder->lead_id)->get();
        //  dd($serviceDetails);
        $assign_cleaner = DB::table('tble_schedule')->where('sales_order_no', $salesOrder->sales_order_no)->get();

        $quotation = Quotation::where('id', $salesOrder->quotation_id)->first();
        $companyDetail = Company::where('id', $quotation->company_id)->first();
        // echo"<pre>"; print_r($companyDetail); exit;
        $termCondition = TermCondition::where('company_id', $companyDetail->id)->get();
        $priceInfo = LeadPrice::get();
        //dd($salesOrder);
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
    public function getAddress($id)
    {
        $quotation = SalesOrder::where('id', $id)->first();

        if ($quotation) {
            $quotation_id = $quotation->quotation_id;
            $customer_data = Crm::where('id', $quotation->customer_id)->first();
            $session = QuotationServiceDetail::where('quotation_id', $quotation_id)->first();
            $get_service_id = Services::where('id', $session->service_id)->first();
            $get_total_session = $get_service_id->total_session;
            $get_weekly_freq = $get_service_id->weekly_freq;
            $get_hour = $get_service_id->hour_session;
            $serviceId = Quotation::where('id', $quotation_id)->first();
            if ($serviceId) {
                $service_address = $serviceId->service_address;
                $schedule_date = $serviceId->schedule_date;
                $time_of_cleaning = $serviceId->time_of_cleaning;

                $serviceAddress = ServiceAddress::where('id', $service_address)->first();
                //  dd($serviceAddress);
                if ($serviceAddress) {
                    return response()->json([
                        'address' => $serviceAddress->address,
                        'postal_code' => $serviceAddress->postal_code,
                        'unit_number' => $serviceAddress->unit_number,
                        'customer_id' => $serviceAddress->customer_id,
                        'sales_order_no' => $quotation->sales_order_no,
                        'schedule_date' => $schedule_date,
                        'time_of_cleaning' =>  $time_of_cleaning,
                        'get_total_session' =>  $get_total_session,
                        'weekly_freq' =>  $get_weekly_freq,
                        'get_hour' =>  $get_hour,
                        'customer_remark' =>  $customer_data->customer_remark
                    ]);
                }
            }
        }

        return response()->json(['address' => 'Error fetching address']);
    }
}
