<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\Crm;
use App\Models\LanguageSpoken;
use App\Models\Lead;
use App\Models\LeadPaymentInfo;
use App\Models\LeadPriceInfo;
use App\Models\LeadServices;
use App\Models\PaymentTerms;
use App\Models\ServiceAddress;
use App\Models\Services;
use App\Models\Territory;
use App\Models\Company;
use App\Models\LeadPrice;
use App\Models\TermCondition;
use App\Models\LeadUpdateStatus;
use App\Models\LeadSchedule;
use App\Models\EmailTemplate;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\PaymentMethod;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\UploadTrait;
use App\Mail\InvoiceMail;
use App\Mail\SendLeadEmail;
use App\Models\LeadOfflinePaymentDetail;
use App\Models\QuotationServiceDetail;
use App\Models\ScheduleModel;
use App\Models\SourceSetting;
use App\Models\Tax;
use App\Models\ZoneSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Str;

class LeadsController extends Controller
{
    use UploadTrait;

    // public function index(Request $request)
    // {
    //     $heading_name  = 'Leads';
    //     // $leads = DB::table('lead_customer_details')
    //     // ->select('lead_customer_details.*', 'customers.customer_name', 'lead_payment_detail.payment_type')
    //     // ->join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
    //     // ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
    //     // ->get();

    //     $leads = Lead::join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
    //         ->join('lead_payment_detail', function ($join) {
    //             $join->on('lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
    //                 ->whereRaw('lead_payment_detail.id IN (
    //              SELECT MAX(id) FROM lead_payment_detail GROUP BY lead_id
    //          )');
    //         })
    //         ->select('lead_customer_details.id', 'lead_customer_details.status as lead_status', 'customers.customer_name as customer_name', 'customers.customer_type as customer_type', 'customers.mobile_number', 'lead_payment_detail.payment_type')
    //         ->orderBy('lead_customer_details.created_at', 'desc')
    //         ->get();
    //     // echo"<pre>"; print_r($leads); exit;
    //     $newLeads = $leads->where('status', 1);
    //     $pendingLeads = $leads->where('payment_type', 'advance');
    //     $approvedLeads = $leads->where('payment_type', 'full');
    //     $pendingCostomer = $leads->where('lead_status', 2);
    //     $pendingPeyment = $leads->where('lead_status', 3);

    //     $companyList = Company::get();
    //     $leadprice = LeadPrice::get();
    //     $service = Services::get();
    //     //  echo "<pre>";   print_r($pendingCostomer);exit;

    //     return view('admin.leads.index', compact('heading_name', 'pendingPeyment', 'newLeads', 'leads', 'pendingLeads', 'approvedLeads', 'pendingCostomer'));
    // }

    public function index(Request $request)
    {
        $heading_name  = 'Leads';

        // $leads = DB::table('lead_customer_details')
        // ->select('lead_customer_details.*', 'customers.customer_name', 'lead_payment_detail.payment_type')
        // ->join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
        // ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
        // ->get();

        $leads = Lead::join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
            ->leftJoin('lead_payment_detail', function ($join) {
                $join->on('lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
                    ->whereRaw('lead_payment_detail.id IN (
                 SELECT MAX(id) FROM lead_payment_detail GROUP BY lead_id
             )');
            })
            ->select('lead_customer_details.id', 'lead_customer_details.status as lead_status', 'lead_customer_details.schedule_date as schedule_date', 'lead_customer_details.pending_customer_approval_status', 'customers.customer_name as customer_name', 'customers.individual_company_name as individual_company_name', 'customers.customer_type as customer_type', 'customers.mobile_number', 'lead_payment_detail.payment_type')
            ->orderBy('lead_customer_details.created_at', 'desc')
            ->get();


        // Once a cleaner is assigned, the approved leads should be removed from this kanban view start

        foreach($leads as $item)
        {
            $SalesOrder = SalesOrder::where('lead_id', $item->id)->first();

            if($SalesOrder)
            {
                $item->sales_order_status = $SalesOrder->status;
                $item->cleaner_assigned_status = $SalesOrder->cleaner_assigned_status;
            }
            else
            {
                $item->sales_order_status = 0;
                $item->cleaner_assigned_status = 0;
            }
        }

        // Once a cleaner is assigned, the approved leads should be removed from this kanban view end

        $draftLeads = $leads->where('status', 0);
        $newLeads = $leads->whereIn('lead_status', [0, 1]);      
        $pendingCostomer = $leads->where('lead_status', 2);
        $pendingPayment = $leads->where('lead_status', 3);
        $approvedLeads = $leads->where('lead_status', 4)->where('cleaner_assigned_status', 0);
        $expired_leads = $leads->where('lead_status', 5);

        $companyList = Company::get();
        $leadprice = LeadPrice::get();
        $service = Services::get();

        // partial assigned sales order

        $sales_order = SalesOrder::where('cleaner_assigned_status', 2)->get();

        foreach($sales_order as $item)
        {
            $crm = Crm::find($item->customer_id);

            $item->customer_type = $crm->customer_type ?? '';
            $item->customer_name = $crm->customer_name ?? '';
            $item->individual_company_name = $crm->individual_company_name ?? '';
            $item->mobile_number = $crm->mobile_number ?? '';
        }

        $get_team = DB::table('xin_team')->get();
        $employeeNames = SalesOrderController::getEmployeeNames($get_team);

        return view('admin.leads.index', compact('heading_name', 'pendingPayment', 'newLeads', 'leads', 'approvedLeads', 'pendingCostomer', 'expired_leads', 'sales_order', 'get_team', 'employeeNames'));
    }


    // public function create()
    // {
    //     $companyList = Company::get();
    //     $service = Services::get();
    //     // $get_current_month_service = LeadSchedule::whereMonth('cleaning_date', date('m'))->get();
    //     $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();
    //     $service_date = array();
    //     foreach ($get_current_month_service as $item) {
    //         $service_date[] = array(
    //             'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
    //             'service' => $item->service_name
    //         );
    //     }
    //     $dates = json_encode((object)$service_date);
    //     $leadprice = LeadPrice::get();

    //     $subtotal = 0;
    //     $nettotal = 0;
    //     $discount = 0;

    //     // foreach ($leadprice as $price) {

    //     //     $subtotal += $price->sub_total;
    //     //     $nettotal += $price->net_total;
    //     //     $discount += $price->discount;
    //     // }

    //     $emailTemplates = EmailTemplate::get();
    //     //$customersResidential = Crm::where('customer_type', 'residential_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();
    //     // $customersResidential = Crm::select('customers.*', 'zs.zone_color')
    //     //     ->selectRaw('LEFT(sa.postal_code, 2) as shortPostalCode')
    //     //     ->leftJoin('service_address as sa', 'customers.id', '=', 'sa.customer_id')
    //     //     ->leftJoin('zone_settings as zs', function ($join) {
    //     //         $join->whereRaw('FIND_IN_SET(LEFT(sa.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
    //     //     })
    //     //     ->where('customers.customer_type', 'residential_customer_type')
    //     //     ->limit(4)
    //     //     ->orderBy('customers.created_at', 'desc')
    //     //     ->get();

    //     $customersResidential = Crm::select('customers.*', 'zs.zone_color')
    //                                 ->selectRaw('LEFT(sa.postal_code, 2) as shortPostalCode')
    //                                 ->leftJoin('service_address as sa', function ($join) {
    //                                     $join->on('customers.id', '=', 'sa.customer_id')
    //                                         ->whereRaw('sa.id = (SELECT MAX(id) FROM service_address WHERE customer_id = customers.id)');
    //                                 })
    //                                 ->leftJoin('zone_settings as zs', function ($join) {
    //                                     $join->whereRaw('FIND_IN_SET(LEFT(sa.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
    //                                 })
    //                                 ->where('customers.customer_type', 'residential_customer_type')
    //                                 ->limit(3)
    //                                 ->orderBy('customers.created_at', 'desc')
    //                                 ->get();

    //     // $customersCommercial = Crm::where('customer_type', 'commercial_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();

    //     $customersCommercial = Crm::select('customers.*', 'zs.zone_color')
    //                             ->selectRaw('LEFT(sa.postal_code, 2) as shortPostalCode')
    //                             ->leftJoin('service_address as sa', function ($join) {
    //                                 $join->on('customers.id', '=', 'sa.customer_id')
    //                                     ->whereRaw('sa.id = (SELECT MAX(id) FROM service_address WHERE customer_id = customers.id)');
    //                             })
    //                             ->leftJoin('zone_settings as zs', function ($join) {
    //                                 $join->whereRaw('FIND_IN_SET(LEFT(sa.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
    //                             })
    //                             ->where('customers.customer_type', 'commercial_customer_type')
    //                             ->limit(3)
    //                             ->orderBy('customers.created_at', 'desc')
    //                             ->get();

    //     $tax = Tax::first();

    //     // echo"<pre>"; print_r($emailTemplates); exit;
    //     return view('admin.leads.create', compact('customersResidential', 'customersCommercial', 'companyList', 'service', 'dates', 'leadprice', 'emailTemplates', 'subtotal', 'nettotal', 'discount', 'tax'));
    // }

    public function create()
    {
        $companyList = Company::get();
        $service = Services::get();
        // $get_current_month_service = LeadSchedule::whereMonth('cleaning_date', date('m'))->get();
        $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();
        $service_date = array();
        foreach ($get_current_month_service as $item) {
            $service_date[] = array(
                'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
                'service' => $item->service_name
            );
        }

        $dates = json_encode((object)$service_date);      
        
        $leadprice = LeadPrice::get();

        $emailTemplates = EmailTemplate::get();

        $customersResidential = Crm::where('customers.customer_type', 'residential_customer_type')
                                    ->limit(3)
                                    ->orderBy('customers.created_at', 'desc')
                                    ->get();

        foreach($customersResidential as $item)
        {
            $res_serv_addr = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

            if($res_serv_addr)
            {
                $postal_code = $res_serv_addr->postal_code;
                $shortPostalCode = substr($postal_code, 0, 2);

                $zone = ZoneSetting::whereRaw('FIND_IN_SET(?, REPLACE(postal_code, " ", ""))', [$shortPostalCode])->first();

                $zone_color = $zone ? $zone->zone_color : '';
            }
            else
            {
                $zone_color = "";
                $shortPostalCode = "";
            }

            $item->zone_color = $zone_color;
            $item->shortPostalCode = $shortPostalCode;
        }

        $customersCommercial = Crm::where('customers.customer_type', 'commercial_customer_type')
                                    ->limit(3)
                                    ->orderBy('customers.created_at', 'desc')
                                    ->get();

        foreach($customersCommercial as $item)
        {
            $res_serv_addr = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

            if($res_serv_addr)
            {
                $postal_code = $res_serv_addr->postal_code;
                $shortPostalCode = substr($postal_code, 0, 2);

                $zone = ZoneSetting::whereRaw('FIND_IN_SET(?, REPLACE(postal_code, " ", ""))', [$shortPostalCode])->first();

                $zone_color = $zone ? $zone->zone_color : '';
            }
            else
            {
                $zone_color = "";
                $shortPostalCode = "";
            }

            $item->zone_color = $zone_color;
            $item->shortPostalCode = $shortPostalCode;
        }

        // tax

        // $tax = Tax::first();

        $today_date = date('Y-m-d');
        $tax = Tax::whereDate('from_date', '<=', $today_date)
                    ->whereDate('to_date', '>=', $today_date)
                    ->first();

        // echo"<pre>"; print_r($emailTemplates); exit;
        return view('admin.leads.create', compact('customersResidential', 'customersCommercial', 'companyList', 'service', 'dates', 'leadprice', 'emailTemplates', 'tax'));
    }


    public function createCustomer()
    {
        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        $salutation_data = DB::table('constant_settings')->get();
        $source_data = SourceSetting::all();

        return view('admin.leads.customerCreate', compact('territory_list', 'spoken_language', 'payment_terms', 'salutation_data', 'source_data'));
    }

    // public function search(Request $request)
    // {
    //     $search = $request->search;

    //     //    if(!empty($search)){
    //     if ($request->type == '1') {
    //         // $customers = Crm::where('customers.customer_type', 'residential_customer_type')
    //         //     ->where(function ($query) use ($search) {
    //         //         $query->where('customers.customer_name', 'like', '%' . $search . '%')
    //         //             ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
    //         //     })
    //         //     ->get();
    //         $customers = Crm::select('customers.*', 'zs.zone_color')
    //             ->selectRaw('LEFT(sa.postal_code, 2) as shortPostalCode')
    //             ->leftJoin('service_address as sa', 'customers.id', '=', 'sa.customer_id')
    //             ->leftJoin('zone_settings as zs', function ($join) {
    //                 $join->whereRaw('FIND_IN_SET(LEFT(sa.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
    //             })
    //             ->where('customers.customer_type', 'residential_customer_type')
    //             ->where(function ($query) use ($search) {
    //                 $query->where('customers.customer_name', 'like', '%' . $search . '%')
    //                     ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
    //             })
    //             ->limit(3)
    //             ->orderBy('customers.created_at', 'desc')
    //             ->get();
    //     } else {
    //         // $customers = Crm::where('customers.customer_type', 'commercial_customer_type')
    //         //     ->where(function ($query) use ($search) {
    //         //         $query->where('customers.customer_name', 'like', '%' . $search . '%')
    //         //             ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
    //         //     })
    //         //     ->get();

    //         $customers = Crm::select('customers.*', 'zs.zone_color')
    //             ->selectRaw('LEFT(sa.postal_code, 2) as shortPostalCode')
    //             ->leftJoin('service_address as sa', 'customers.id', '=', 'sa.customer_id')
    //             ->leftJoin('zone_settings as zs', function ($join) {
    //                 $join->whereRaw('FIND_IN_SET(LEFT(sa.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
    //             })
    //             ->where('customers.customer_type', 'commercial_customer_type')
    //             ->where(function ($query) use ($search) {
    //                 $query->where('customers.customer_name', 'like', '%' . $search . '%')
    //                     ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
    //             })
    //             ->limit(3)
    //             ->orderBy('customers.created_at', 'desc')
    //             ->get();
    //     }
    //     //    }else{
    //     //         if ($request->type == '1') {
    //     //             $customers = Crm::where('customers.customer_type', 'residential_customer_type')
    //     //                 ->get();
    //     //         } else {
    //     //             $customers = Crm::where('customers.customer_type', 'commercial_customer_type')
    //     //                 ->get();
    //     //         }
    //     //    }

    //     return response()->json($customers);
    // }

    public function search(Request $request)
    {
        $search = $request->search;

        if ($request->type == '1')
        {
            $customers = Crm::where('customers.customer_type', 'residential_customer_type');

            if($request->filled('search'))
            {
                $customers = $customers->where(function ($query) use ($search) {
                                    $query->where('customers.customer_name', 'like', '%' . $search . '%')
                                            ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
                                });
            }
                                
            $customers = $customers->limit(3)
                                    ->orderBy('customers.created_at', 'desc')
                                    ->get();

            foreach($customers as $item)
            {
                $res_serv_addr = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

                if($res_serv_addr)
                {
                    $postal_code = $res_serv_addr->postal_code;
                    $shortPostalCode = substr($postal_code, 0, 2);

                    $zone = ZoneSetting::whereRaw('FIND_IN_SET(?, REPLACE(postal_code, " ", ""))', [$shortPostalCode])->first();

                    $zone_color = $zone ? $zone->zone_color : '';
                }
                else
                {
                    $zone_color = "";
                    $shortPostalCode = "";
                }

                $item->zone_color = $zone_color;
                $item->shortPostalCode = $shortPostalCode;
            }
        }
        else
        {
            $customers = Crm::where('customers.customer_type', 'commercial_customer_type');

            if($request->filled('search'))
            {
                $customers = $customers->where(function ($query) use ($search) {
                                    $query->where('customers.individual_company_name', 'like', '%' . $search . '%')
                                            ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
                                });
            }
                                
            $customers = $customers->limit(3)
                                    ->orderBy('customers.created_at', 'desc')
                                    ->get();

            foreach($customers as $item)
            {
                $res_serv_addr = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

                if($res_serv_addr)
                {
                    $postal_code = $res_serv_addr->postal_code;
                    $shortPostalCode = substr($postal_code, 0, 2);

                    $zone = ZoneSetting::whereRaw('FIND_IN_SET(?, REPLACE(postal_code, " ", ""))', [$shortPostalCode])->first();

                    $zone_color = $zone ? $zone->zone_color : '';
                }
                else
                {
                    $zone_color = "";
                    $shortPostalCode = "";
                }

                $item->zone_color = $zone_color;
                $item->shortPostalCode = $shortPostalCode;
            }
        }

        return response()->json($customers);
    }

    public function getCustomerDetails(Request $request)
    {
        $customerId = $request->input('id');
        // $customer = Crm::join('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
        //     ->select('customers.*', 'language_spoken.language_name as language_name')
        //     ->where('customers.id', $customerId)
        //     ->first();

        $customer = Crm::leftJoin('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
            ->select('customers.*', 'language_spoken.language_name as language_name')
            ->where('customers.id', $customerId)
            ->first();

        // $customer = Crm::find($customerId);
        return response()->json($customer);
    }
    public function searchSrvice(Request $request)
    {
        $searchTerm = $request->input('search');
        $selectedCompanyId = $request->input('company_id');

        if (!empty($searchTerm)) 
        {
            // $services = Services::where('company', $selectedCompanyId)
            //     ->where('service_name', 'like', '%' . $searchTerm . '%')
            //     ->orWhere('price', 'like', '%' . $searchTerm . '%')
            //     ->orWhere('product_code', 'like', '%' . $searchTerm . '%')
            //     ->get();

            $services = Services::where('company', $selectedCompanyId)
                                ->where(function ($query) use ($searchTerm) {
                                    $query->where('service_name', 'like', '%' . $searchTerm . '%')
                                        ->orWhere('price', 'like', '%' . $searchTerm . '%')
                                        ->orWhere('product_code', 'like', '%' . $searchTerm . '%');
                                })
                                ->get();

        } else {
            $services = Services::where('company', $selectedCompanyId)->get();
        }

        // dd($services);
        return response()->json($services);
    }
    public function getServiceAddress(Request $request)
    {
        $customerId = $request->input('customer_id');
        // dd($customerId);
        $serviceAddress = ServiceAddress::where('customer_id', $customerId)->get();
        return response()->json($serviceAddress);
    }

    public function BillingAddress(Request $request)
    {
        $serviceAddress = ServiceAddress::where('id', $request->service_address)->get();
        return $serviceAddress;
    }

    public function getBillingAddress(Request $request)
    {
        $customerId = $request->input('customer_id');
        $serviceAddress = BillingAddress::where('customer_id', $customerId)->get();
        return response()->json($serviceAddress);
    }
    public function storeServiceAddress(Request $request)
    {
        //  dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'person_incharge_name' => 'required|array',
                'person_incharge_name.*' => 'required|string|max:255',
                'contact_no' => 'required|array',
                'contact_no.*' => 'required|digits:8',
                'email_id' => 'required|array',
                'email_id.*' => 'required|email|max:255',
                'postal_code' => 'required|array',
                'postal_code.*' => 'required|string|max:10',
                'zone' => 'required|array',
                'zone.*' => 'required|string|max:255',
                'address' => 'required|array',
                'address.*' => 'required|string|max:255',
                'unit_number' => 'required|array',
                'unit_number.*' => 'required|string|max:255',
            ],
            [],
            [
                'person_incharge_name.*' => 'Service address Person Incharge name',
                'contact_no.*' => 'Service address contact no',
                'email_id.*' => 'Service address email id',
                'postal_code.*' => 'Service address postal code',
                'zone.*' => 'Service address zone',
                'address.*' => 'Service address',
                'unit_number.*' => 'Service address unit number',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $postalCodes = $request->input('postal_code');
        $addresses = $request->input('address');
        $unitNumbers = $request->input('unit_number');
        $contactNumbers = $request->input('contact_no');
        $emailIds  = $request->input('email_id');
        $zone =  $request->input('zone');
        $personInchargeNames  = $request->input('person_incharge_name');
        $territory  = $request->input('territory');



        if (!empty($postalCodes) && !empty($addresses) && !empty($unitNumbers) && !empty($contactNumbers) && !empty($emailIds) && !empty($personInchargeNames)) {
            $serviceAddresses = [];
            foreach ($postalCodes as $key => $postalCode) {
                $serviceAddresses[] = [
                    'customer_id' => $request->customer_id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[$key],
                    'unit_number' => $unitNumbers[$key],
                    'contact_no' => $contactNumbers[$key],
                    'email_id' => $emailIds[$key],
                    'person_incharge_name' => $personInchargeNames[$key],
                    'zone' => $zone[$key],
                    'territory' => $territory[$key],

                ];
            }
            ServiceAddress::insert($serviceAddresses);
        }
        return response()->json(['success' => 'Service Address added successfully!', 'serviceAddresses' => $serviceAddresses]);
    }
    public function storeBillingAddress(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'b_postal_code' => 'required|array',
                'b_postal_code.*' => 'required|string|max:10',
                // 'b_address' => 'required|array',
                'b_address.*' => 'required|string|max:255',
                'b_person_incharge_name.*' => 'required',
                'b_contact_no.*' => 'required|digits:8'
            ],
            [],
            [
                'b_postal_code.*' => 'Billing address Postal Code',
                'b_address.*' => 'Billing address',
                'b_person_incharge_name.*' => 'Billing address Person Incharge Name',
                'b_contact_no.*' => 'Billing address Contact no'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $postalCodes = $request->input('b_postal_code');
        $addresses = $request->input('b_address');
        $unitNumbers = $request->input('b_unit_number');
        $zone =  $request->input('b_zone');
        $person_name = $request->input('b_person_incharge_name');
        $contact_no = $request->input('b_contact_no');
        $b_email = $request->input('b_email');

        if (!empty($postalCodes) && !empty($addresses) && !empty($unitNumbers)) {
            $billingAddresses = [];
            foreach ($postalCodes as $key => $postalCode) {
                $billingAddresses[] = [
                    'customer_id' => $request->customer_id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[$key],
                    'unit_number' => $unitNumbers[$key],
                    'zone' => $zone[$key],
                    'person_incharge_name' => $person_name[$key],
                    'contact_no' => $contact_no[$key],
                    'email' => $b_email[$key],
                ];
            }
            BillingAddress::insert($billingAddresses);
        }
        return response()->json(['success' => 'Billing Address added successfully!', 'billingAddresses' => $billingAddresses]);
    }

    public function leadStore(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        // $leadId = $this->temp_lead_store($request);
        $leadId = $this->draft_lead_store($request);

        $db_quotation = Quotation::where('lead_id', $leadId)->first();

        // log data store start

        LogController::store('lead', 'Lead Created', $leadId);
        LogController::store('quotation', 'Quotation Created from Lead', $db_quotation->id ?? '');

        // log data store end

        return response()->json(['success' => 'Lead Created successfully!']);
    }

    public function leadStore_confirm(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        // $leadId = $this->temp_lead_store($request);
        $leadId = $this->draft_lead_store($request);

        // lead start

        $lead = Lead::find($leadId);
        $lead->status = 2;
        $lead->pending_customer_approval_status = 2;
        $lead->save();

        // lead end

        // quotation start

        $quotation = Quotation::where('lead_id', $leadId)->first();
        $quotation->status = 2;
        $quotation->save();

        // quotation end

        // log data store start

        LogController::store('lead', 'Lead Created and confirmed', $leadId);
        LogController::store('quotation', 'Quotation Created from Lead and confirmed', $quotation->id ?? '');

        // log data store end

        return response()->json(['success' => 'Lead Created successfully!']);
    }

    public function leadUpdateStore(Request $request)
    {
        // return $request->all();

        // dd($request->customer_id);

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Lead::where('id', $request->lead_id)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['status'=>'failed', 'message' => 'Lead already Approved']);
        }

        $leadId = $this->temp_lead_update($request);

        // lead start

        $lead = Lead::find($leadId);

        if($lead->status == 0)
        {
            $lead->status = 1;
            $lead->save();
        }

        // lead end

        // quotation start

        $quotation = Quotation::where('lead_id', $leadId)->first();

        if($quotation->status == 0)
        {
            $quotation->status = 1;
            $quotation->save();
        }

        // quotation end

        // log data store start

        LogController::store('lead', 'Lead Updated', $leadId);
        LogController::store('quotation', 'Quotation Updated from lead', $quotation->id ?? '');

        // log data store end

        return response()->json(['success' => 'Lead updated successfully!']);
    }

    public function leadUpdateStore_confirm(Request $request)
    {
        // return $request->all();

        // dd($request->customer_id);

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Lead::where('id', $request->lead_id)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['status'=>'failed', 'message' => 'Lead already Approved']);
        }

        $leadId = $this->temp_lead_update($request);

        // lead start

        $lead = Lead::find($leadId);
        $lead->status = 2;
        $lead->pending_customer_approval_status = 2;
        $lead->save();

        // lead end

        // quotation start

        $quotation = Quotation::where('lead_id', $leadId)->first();
        $quotation->status = 2;
        $quotation->save();

        // quotation end

        // log data store start

        LogController::store('lead', 'Lead Updated and confirmed', $leadId);
        LogController::store('quotation', 'Quotation Updated from lead and confirmed', $quotation->id ?? '');

        // log data store end

        return response()->json(['success' => 'Lead updated successfully!']);
    }


    // public function leadpaymentStore(Request $request)
    // {

    //     // dd($request->customer_id);

    //     $leadDetails = Lead::where('customer_id', $request->customer_id)->first();
    //     $leadDetails->service_address = $request->input('service_address');
    //     $leadDetails->billing_address = $request->input('billing_address');
    //     $leadDetails->schedule_date = $request->input('schedule_date');
    //     $leadDetails->amount = $request->input('total_amount_val');
    //     $leadDetails->tax = $request->input('tax');
    //     $leadDetails->grand_total = $request->input('grand_total_amount');
    //     $leadDetails->tax_percent = $request->input('tax_percent');
    //     $leadDetails->status = 3;
    //     $leadDetails->save();

    //     // $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    //     // $leadCustomerDetail =  new Lead([
    //     //     'unique_id' => $uniqueID,
    //     //     'customer_id' => $request->customer_id,
    //     //     'service_address' => $request->service_id,
    //     //     'billing_address' => $request->billing_address,
    //     //     'schedule_date' => $request->schedule_date,
    //     //     'amount' => $request->total_amount_val,
    //     //     'tax' => $request->tax,
    //     //     'grand_total' => $request->grand_total_amount,
    //     //     'tax_percent' => $request->tax_percent,
    //     //     'time_of_cleaning' => $request->time_of_cleaning,
    //     //     'date_of_cleaning' => $request->date_of_cleaning,
    //     //     'status' => 2,
    //     // ]);
    //     // $leadCustomerDetail->save();

    //     $serviceIds = explode(',', $request->input('service_ids'));
    //     $serviceDescription = explode(',', $request->input('service_descriptions'));
    //     $serviceUnitPrice = explode(',', $request->input('unit_price'));
    //     $serviceQuantity = explode(',', $request->input('quantities'));
    //     $serviceDiscount = explode(',', $request->input('discounts'));
    //     $serviceNames = explode(',', $request->input('service_names'));
    //     // Assuming all arrays have the same length
    //     $count = count($serviceIds);
    //     $leadId = $leadDetails->id;

    //     for ($i = 0; $i < $count; $i++) {
    //         $service = new LeadServices();

    //         $service->lead_id = $leadId;
    //         $service->service_id = $serviceIds[$i] ?? '';
    //         $service->name = $serviceNames[$i] ?? '';
    //         $service->description = $serviceDescription[$i] ?? " ";
    //         $service->unit_price = $serviceUnitPrice[$i];
    //         $service->quantity = $serviceQuantity[$i] ?? 0;
    //         $service->discount = $serviceDiscount[$i] ?? 0;
    //         // $grossAmount = ($service->unit_price * $service->quantity) * (1 - (floatval($service->discount) / 100));

    //         $service->gross_amount = 0;
    //         $service->save();
    //     }

    //     $leadPriceInfo  = new LeadPriceInfo([
    //         'lead_id' => $leadDetails->id,
    //         'deposite_type' => $request->deposite_type,
    //         'date_of_cleaning' => $request->cleaning_date,
    //         'time_of_cleaning' => $request->cleaning_time,

    //     ]);
    //     $leadPriceInfo->save();

    //     for ($i = 0; $i < $count; $i++) {
    //         $price = new LeadPrice();

    //         $price->service_id = $serviceIds[$i];
    //         $price->service = $serviceNames[$i];
    //         $price->unit_price = $serviceUnitPrice[$i];
    //         $price->qty = $serviceQuantity[$i] ?? 0;
    //         $price->discount = $serviceDiscount[$i] ?? 0;
    //         $sub_total = $price->unit_price;
    //         $net_total = $price->unit_price;
    //         // $net_total = ($price->unit_price * $price->qty) * (1 - (floatval($price->discount) / 100));

    //         $price->sub_total = $sub_total;
    //         $price->net_total = $net_total;
    //         $price->save();
    //     }

    //     $leadPaymentInfo = new LeadPaymentInfo();
    //     $leadPaymentInfo->lead_id = $leadDetails->id;
    //     $leadPaymentInfo->payment_type = $request->payment_option;
    //     $leadPaymentInfo->total_amount = $request->total_amount;
    //     $leadPaymentInfo->advance_amount = $request->advance_amount;


    //     if ($request->has('payment_file')) {
    //         // Get image file
    //         $image = $request->file('payment_file');

    //         $name = Str::slug($request->input('payment_option')) . '_' . time();
    //         $folder = '/uploads/images/';
    //         $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
    //         $this->uploadOne($image, $folder, 'public', $name);
    //         $leadPaymentInfo->file = $filePath;
    //     }
    //     if ($request->has('full_payment_file')) {
    //         // Get image file
    //         $image = $request->file('full_payment_file');

    //         $name = Str::slug($request->input('payment_option')) . '_' . time();
    //         $folder = '/uploads/images/';
    //         $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
    //         $this->uploadOne($image, $folder, 'public', $name);
    //         $leadPaymentInfo->file = $filePath;
    //     }
    //     $leadPaymentInfo->save();

    //     $quotation = new Quotation([
    //         'customer_id' => $request->customer_id,
    //         'company_id' => $request->company_id,
    //         'lead_id' => $leadDetails->id,
    //         'quotation_no' => rand(1234, 9999),
    //     ]);
    //     $quotation->save();

    //     // Mail::to($customer->email)->send(new SendLeadEmail($emailTemplate->title, $emailTemplate->subject,$emailTemplate->body,$company->company_name,$leadDetails->id,$attachment));
    //     // echo"<pre>"; print_r($leadDetails); exit;

    //     return response()->json(['success' => 'Payment updated successfully!']);
    // }


    public function getEmailData(Request $request)
    {
        $customer = Crm::where('customers.id', $request->customer_id)
                        ->leftJoin('constant_settings','constant_settings.id', '=', 'customers.saluation')
                        ->select('customers.*', 'constant_settings.salutation_name')
                        ->first();

        $data = EmailTemplate::where('id', $request->template_id)->first();
        $company = Company::where('id', $request->company_id)->first();
        
        $service_address = ServiceAddress::find($request->service_address_id);
        $billing_address = BillingAddress::find($request->billing_address_id);

        $option = "";

        if(!empty($customer->email))
        {
            $option .= '<option value="'.$customer->email.'">'.$customer->email.'</option>';
        }

        if(!empty($service_address->email_id ?? ''))
        {
            $option .= '<option value="'.$service_address->email_id.'">'.$service_address->email_id.'</option>';
        }

        if(!empty($billing_address->email ?? ''))
        {
            $option .= '<option value="'.$billing_address->email.'" selected>'.$billing_address->email.'</option>';
        }

        // lead start

        if($request->filled('lead_id') || $request->filled('quotation_id'))
        {
            if($request->filled('lead_id'))
            {
                $quotation = Quotation::where('lead_id', $request->lead_id)->first();
            }
            elseif($request->filled('quotation_id'))
            {
                $quotation = Quotation::find($request->quotation_id);
            }

            if(!empty($quotation->schedule_date))
            {
                $schedule_date = date('d-m-Y', strtotime($quotation->schedule_date)) ?? '';
            }

            if(!empty($quotation->schedule_date))
            {
                $job_day = date('l', strtotime($quotation->schedule_date)) ?? '';
            }

            if(!empty($quotation->time_of_cleaning))
            {
                $time_of_cleaning = date('h:i a', strtotime($quotation->time_of_cleaning)) ?? '';
            }

            // $grand_total = $quotation->grand_total ?? 0;
            $grand_total = $request->preview_grandTotal ?? $quotation->grand_total;

            if($request->filled('preview_discount'))
            {
                $discount_amount = $request->preview_discount ?? 0;
            }
            else
            {
                if($quotation->discount_type == "percentage")
                {
                    $discount_amount = $quotation->grand_total * ($quotation->discount/100);
                }
                else
                {
                    $discount_amount = $quotation->discount;
                }
            }
            
            $tax_amount = $request->preview_tax_amt ?? $quotation->tax;

            $payment_amount = $request->payment_amount ?? 0;

            $balance_amount = 0;
        
            if($grand_total == $payment_amount)
            {
                $balance_amount = 0;
            }
            else if($grand_total > $payment_amount)
            {
                $balance_amount = $grand_total - $payment_amount;
            }
            else
            {
                $balance_amount = 0;
            }

            if($request->filled('payment_option'))
            {
                $payment_option = $request->payment_option;
            }

            // only for received payment
            if($request->filled('received_payment_amount'))
            {
                $balance_amount = PaymentController::get_balance_amount($quotation->id) - $request->received_payment_amount ?? 0;
            }

            // quotation no        
            $quotation_no = $request->preview_quotation_no ?? $quotation->quotation_no;
        }
        else
        {
            $grand_total = $request->preview_grandTotal ?? 0;
            $discount_amount = $request->preview_discount ?? 0;
            $tax_amount = $request->preview_tax_amt ?? 0;
            $payment_amount = $request->payment_amount ?? 0;
            
            $balance_amount = 0;
        
            if($grand_total == $payment_amount)
            {
                $balance_amount = 0;
            }
            else if($grand_total > $payment_amount)
            {
                $balance_amount = $grand_total - $payment_amount;
            }
            else
            {
                $balance_amount = 0;
            }

            if($request->filled('payment_option'))
            {
                $payment_option = $request->payment_option;
            }

            // quotation no        
            $quotation_no = $request->preview_quotation_no ?? '';
        }

        // lead end

        $cust_remark_arr = explode(PHP_EOL, $customer->customer_remark ?? '');
        $cust_remarks = "";
        foreach ($cust_remark_arr as $list)
        {
            $cust_remarks .= $list . "<br>";
        }
                                                
        // email body start

        $data->body = str_replace("##SALUTATION##", $customer->salutation_name, $data->body);

        if($customer->customer_type == "residential_customer_type")
        {
            $data->body = str_replace("##CUSTOMER_NAME##", $customer->customer_name, $data->body);
        }
        else if($customer->customer_type == "commercial_customer_type")
        {
            $data->body = str_replace("##CUSTOMER_NAME##", $customer->customer_name, $data->body);
        }

        $data->body = str_replace("##SERVICE_ADDRESS##", $service_address->address ?? ''. ", Unit No: " .$service_address->unit_number ?? '', $data->body);
        $data->body = str_replace("##INVOICE_PAYMENT##", "$".number_format($grand_total ?? 0, 2), $data->body);
        $data->body = str_replace("##DISCOUNT_AMOUNT##", "$".number_format($discount_amount ?? 0, 2), $data->body);
        $data->body = str_replace("##GST_AMOUNT##", "$".number_format($tax_amount ?? 0, 2), $data->body);
        $data->body = str_replace("##DEPOSITS##", "$".number_format($payment_amount ?? 0, 2), $data->body);
        $data->body = str_replace("##BALANCE##", "$".number_format($balance_amount ?? 0, 2), $data->body);
        $data->body = str_replace("##PAYMENT_METHOD##", $payment_option ?? '', $data->body);
        $data->body = str_replace("##JOB_DATE##", $schedule_date ?? '', $data->body);
        $data->body = str_replace("##JOB_DAY##", $job_day ?? '', $data->body);
        $data->body = str_replace("##JOB_TIME##", $time_of_cleaning ?? '', $data->body);
        $data->body = str_replace("##CUSTOMER_REMARK##", $cust_remarks ?? '', $data->body);          
        $data->body = str_replace("##QUOTATION_NO##", $quotation_no ?? '', $data->body);

        // only for received payment start
        if($request->filled('received_payment_amount'))
        {            
            $data->body = str_replace("##RECEIVED_PAYMENT##", "$".number_format($request->received_payment_amount ?? 0, 2), $data->body);
        }

        if($request->filled('temp_invoice_no'))
        {
            $data->body = str_replace("##INVOICE_NO##", $request->temp_invoice_no ?? '', $data->body);
        }
        // only for received payment end

        $data->body = str_replace("##GOOGLE_REVIEW##", '<a href="http://www.absolute.sg/cleaning-google-review">Google Review</a>', $data->body);
        $data->body = str_replace("##FACEBOOK_REVIEW##", '<a href="http://www.absolute.sg/cleaning-facebook-review">Facebook Review</a>', $data->body);
        $data->body = str_replace("##FACEBOOK_LINK##", '<a href="http://www.facebook.com/absoluteservicesingapore/">Absolute Services Singapore</a>', $data->body);
        $data->body = str_replace("##VISIT_US##", '<a href="http://absolute.sg">absolute.sg</a>', $data->body);

        // email body end

        $emailTemplate = '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">To:</label>
                                        <select class="form-select" name="email_to" id="email_to">
                                            '.$option.'
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CC:</label>
                                        <input type="text" class="form-control" name="email_cc"
                                            id="email_cc" value="' . $data->cc . '">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">BCC:</label>
                                        <input type="text" class="form-control" name="email_bcc"
                                            id="email_bcc" value="' . $data->bcc . '">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Subject:</label>
                                        <input type="text" class="form-control" name="email_subject"
                                            id="email_subject" value="' . $data->subject . '">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Body:</label>
                                        <textarea class="form-control" name="email_body" id="email_body" rows="6" placeholder="Content..">' . $data->body . '</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button class="btn btn-info" onclick="emailSend(event)" id="email_confirm_btn">Confirm</button>
                                </div>
                            </div>';

        return $emailTemplate;
    }

    public function sendInvoiceEmail(Request $request)
    {

        $companyDetail = Company::where('id', $request->company_id)->first();
        $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();
        $customer = DB::table('customers')->where('id', $request->customer_id)->first();

        // echo"<pre>"; print_r($companyDetail);exit;

        $data['title'] = $emailTemplate->title;
        $data['subject'] = $emailTemplate->subject;
        $data['body'] = $emailTemplate->body;
        $data['company_name'] = $companyDetail->company_name;
        $data["email"] = $request->email;

        $company = array($companyDetail);

        $salesOrderNumber = rand(12345, 9999);
        $salesOrder =  new SalesOrder([
            'sales_order_no' => $salesOrderNumber,
            'customer_id' => $request->customer_id,
        ]);
        $salesOrder->save();

        $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $leadCustomerDetail =  new Lead([
            'unique_id' => $uniqueID,
            'customer_id' => $request->customer_id,
            'service_address' => $request->service_id,
            'billing_address' => $request->billing_address,
            'schedule_date' => $request->schedule_date,
            'amount' => $request->total_amount_val,
            'tax' => $request->tax,
            'grand_total' => $request->grand_total_amount,
            'tax_percent' => $request->tax_percent,
            'time_of_cleaning' => $request->time_of_cleaning,
            'date_of_cleaning' => $request->date_of_cleaning,
            'status' => 3,
        ]);
        $leadCustomerDetail->save();

        $leadPaymentInfo = new LeadPaymentInfo();
        $leadPaymentInfo->lead_id = $leadCustomerDetail->id;
        $leadPaymentInfo->payment_type = $request->payment_option;
        $leadPaymentInfo->total_amount = $request->total_amount_val;
        $leadPaymentInfo->advance_amount = $request->advance_amount;


        if ($request->has('payment_file')) {
            // Get image file
            $image = $request->file('payment_file');

            $name = Str::slug($request->input('payment_option')) . '_' . time();
            $folder = '/uploads/images/';
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $leadPaymentInfo->file = $filePath;
        }
        if ($request->has('full_payment_file')) {
            // Get image file
            $image = $request->file('full_payment_file');

            $name = Str::slug($request->input('payment_option')) . '_' . time();
            $folder = '/uploads/images/';
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $leadPaymentInfo->file = $filePath;
        }
        $leadPaymentInfo->save();

        //

        return response()->json(['success' => 'Lead updated successfully!']);
    }
    public function DefaultAddress(Request $request)
    {
        $customer = Crm::find($request->customer_id);

        if ($customer) {
            $customer->default_address = $request->address_id;
            $customer->save();

            return response()->json(['message' => 'Default address updated successfully']);
        } else {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }
    // public function destroy($id)
    // {
    //     $lead = Lead::find($id);

    //     if ($lead) {
    //         $lead->delete();
    //         return redirect()->back()->with('success', 'Lead deleted successfully.');
    //     } else {
    //         return redirect()->back()->with('error', 'Lead not found.');
    //     }
    // }

    public function destroy(Request $request)
    {
        if($request->filled('leadId'))
        {
            $id = $request->leadId;

            $lead = Lead::find($id);

            if ($lead)
            {
                if($lead->status == 3 || $lead->status == 4)
                {
                    return response()->json(['status'=>'failed', 'message'=>'Lead already approved']);
                }

                $lead->delete();

                if(LeadServices::where('lead_id', $id)->exists())
                {
                    LeadServices::where('lead_id', $id)->delete();
                }

                if(LeadPriceInfo::where('lead_id', $id)->exists())
                {
                    LeadPriceInfo::where('lead_id', $id)->delete();
                }

                if(Quotation::where('lead_id', $id)->exists())
                {
                    $get_quotation = Quotation::where('lead_id', $id)->first();
                    $get_quotation_id = $get_quotation->id;

                    Quotation::where('lead_id', $id)->delete();
                }

                if(QuotationServiceDetail::where('quotation_id', $get_quotation_id ?? '')->exists())
                {
                    QuotationServiceDetail::where('quotation_id', $get_quotation_id)->delete();
                }

                // log data store start

                LogController::store('lead', 'Lead Deleted', $id);
                LogController::store('quotation', 'Quotation Deleted from lead', $get_quotation_id ?? '');

                // log data store end

                return response()->json(['status'=>'success', 'message'=>'Lead deleted successfully']);            
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Lead not found']);
            }
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Lead not found']);
        }
    }

    public function updateStatus(request $request)
    {
        $leadId = $request->input('lead_id');
        $lead = Lead::where('id', $leadId)->first();
        $leadStatusUpdateData = LeadUpdateStatus::where('lead_id', $lead->id)->first();
        return view('admin.leads.updateStatus', compact('lead', 'leadStatusUpdateData'));
    }

    public function storeUpdateStatus(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'status' => 'required|string',
            // 'quotation_template' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $checkLeadId = LeadUpdateStatus::where('lead_id', $request->lead_id)->first();
        if ($checkLeadId) {
            LeadUpdateStatus::where('lead_id', $request->lead_id)->update([
                'lead_id' => $request->input('lead_id'),
                'status' => $request->input('status'),
                'quotation_templete' => $request->input('quotation_templete'),
                'comment' => $request->input('comment'),
            ]);
            return response()->json(['success' => 'Status Updated successfully!']);
        } else {
            LeadUpdateStatus::insert([
                'lead_id' => $request->input('lead_id'),
                'status' => $request->input('status'),
                'quotation_templete' => $request->input('quotation_templete'),
                'comment' => $request->input('comment'),
            ]);
            return response()->json(['success' => 'Status Updated successfully!']);
        }
    }

    // public function edit(request $request)
    // {
    //     $companyList = Company::get();
    //     // $get_current_month_service = LeadSchedule::whereMonth('cleaning_date', date('m'))->get();
    //     $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();
    //     $service_date = array();
    //     foreach ($get_current_month_service as $item) {
    //         $service_date[] = array(
    //             'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
    //             'service' => $item->service_name
    //         );
    //     }
    //     $dates = json_encode((object)$service_date);
    //     $leadprice = LeadPrice::get();

    //     $subtotal = 0;
    //     $nettotal = 0;
    //     $discount = 0;

    //     // foreach ($leadprice as $price) {

    //     //     $subtotal += $price->sub_total;
    //     //     $nettotal += $price->net_total;
    //     //     $discount += $price->discount;
    //     // }

    //     $emailTemplates = EmailTemplate::get();

    //     $leadId = $request->input('lead_id');

    //     // $lead = Lead::select('lead_customer_details.*', 'lead_payment_detail.*', 'lead_price_info.*')
    //     //     ->join('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
    //     //     ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
    //     //     ->where('lead_customer_details.id', $leadId)
    //     //     ->first();
    //     $lead = Lead::select('lead_customer_details.id as leads_id', 'lead_customer_details.*', 'lead_price_info.*')
    //         ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
    //         ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
    //         ->where('lead_customer_details.id', $leadId)
    //         ->first();

    //     // dd($lead->payment_type);
    //     $customerId = $lead->customer_id;
    //     // $customer = Crm::join('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
    //     //     ->join('service_address', 'customers.id', '=', 'service_address.customer_id')
    //     //     ->select('customers.id as custom_id', 'customers.*', 'language_spoken.language_name as language_name', 'service_address.*')
    //     //     ->where('customers.id', $customerId)
    //     //     ->first();

    //     $customer = Crm::leftJoin('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
    //         ->join('service_address', 'customers.id', '=', 'service_address.customer_id')
    //         ->select('customers.id as custom_id', 'customers.*', 'language_spoken.language_name as language_name', 'service_address.*')
    //         ->where('customers.id', $customerId)
    //         ->first();

    //     // dd($customer->custom_id);
    //     $service = LeadServices::where('lead_id', $leadId)->get();

    //     $addresses = ServiceAddress::where('customer_id', $customerId)->get();
    //     // $Serviceaddresses = ServiceAddress::where('customer_id', $customerId)->first();
    //     $Serviceaddresses = ServiceAddress::where('customer_id', $customerId)
    //                             ->where('id', $lead->service_address)
    //                             ->first();

    //     $priceInfo = LeadPriceInfo::where('lead_id', $leadId)->first();

    //     $billingaddresses = BillingAddress::where('customer_id', $customerId)->get();
    //     $leadBillingaddresses = BillingAddress::where('customer_id', $customerId)->first();

    //     $services = LeadServices::where('lead_id', $leadId)->get();

    //     $customersResidential = Crm::where('customer_type', 'residential_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();
    //     $customersCommercial = Crm::where('customer_type', 'commercial_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();

    //     $tax = Tax::first();
    //     $emailTemplates = EmailTemplate::get();

    //     return view('admin.leads.edit', compact('dates','lead', 'service', 'companyList', 'customer', 'services', 'addresses', 'Serviceaddresses', 'customersResidential', 'customersCommercial', 'billingaddresses', 'priceInfo', 'leadBillingaddresses', 'tax', 'emailTemplates'));
    // }

    public function edit(request $request)
    {
        $companyList = Company::get();
        // $get_current_month_service = LeadSchedule::whereMonth('cleaning_date', date('m'))->get();
        $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();
        $service_date = array();
        foreach ($get_current_month_service as $item) {
            $service_date[] = array(
                'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
                'service' => $item->service_name
            );
        }
        $dates = json_encode((object)$service_date);
        $leadprice = LeadPrice::get();

        $subtotal = 0;
        $nettotal = 0;
        $discount = 0;

        // foreach ($leadprice as $price) {

        //     $subtotal += $price->sub_total;
        //     $nettotal += $price->net_total;
        //     $discount += $price->discount;
        // }

        $emailTemplates = EmailTemplate::get();

        $leadId = $request->input('lead_id');

        // $lead = Lead::select('lead_customer_details.*', 'lead_payment_detail.*', 'lead_price_info.*')
        //     ->join('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
        //     ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
        //     ->where('lead_customer_details.id', $leadId)
        //     ->first();

        // $lead = Lead::select('lead_customer_details.id as leads_id', 'lead_customer_details.*', 'lead_price_info.*')
        //     ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
        //     ->leftJoin('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
        //     ->where('lead_customer_details.id', $leadId)
        //     ->first();

        $lead = Lead::select('lead_customer_details.id as leads_id', 'lead_customer_details.*')
            ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
            ->where('lead_customer_details.id', $leadId)
            ->first();

        // dd($lead->payment_type);
        $customerId = $lead->customer_id;

        $customer = Crm::leftJoin('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
                        ->select(
                            'customers.*',
                            'customers.id as customer_id',
                            'language_spoken.language_name as language_name',
                        )
                        ->where('customers.id', $customerId)
                        ->first();

        // dd($customer->custom_id);
        $service = LeadServices::where('lead_id', $leadId)->get();

        $addresses = ServiceAddress::where('customer_id', $customerId)->get();
        // $Serviceaddresses = ServiceAddress::where('customer_id', $customerId)->first();
        $Serviceaddresses = ServiceAddress::where('customer_id', $customerId)
                                ->where('id', $lead->service_address)
                                ->first();

        $priceInfo = LeadPriceInfo::where('lead_id', $leadId)->first();

        $billingaddresses = BillingAddress::where('customer_id', $customerId)->get();
        $leadBillingaddresses = BillingAddress::where('customer_id', $customerId)->first();

        $services = LeadServices::where('lead_id', $leadId)->get();
        foreach($services as $item)
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
                // $item->total_session = "";
                $item->weekly_freq = "";
            }
        }

        $customersResidential = Crm::where('customer_type', 'residential_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();
        $customersCommercial = Crm::where('customer_type', 'commercial_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();
       
        $emailTemplates = EmailTemplate::get();

        // tax

        // $tax = Tax::first();

        $today_date = date('Y-m-d');
        $tax = Tax::whereDate('from_date', '<=', $today_date)
                    ->whereDate('to_date', '>=', $today_date)
                    ->first();

        return view('admin.leads.edit', compact('dates','lead', 'service', 'companyList', 'customer', 'services', 'addresses', 'Serviceaddresses', 'customersResidential', 'customersCommercial', 'billingaddresses', 'priceInfo', 'leadBillingaddresses', 'tax', 'emailTemplates'));
    }

    public function leadSchedule(Request $request)
    {

        $date = str_replace("/", "-", $request->service_date);
        $schedule = new LeadSchedule();
        $schedule->service_id = $request->service;
        $schedule->cleaning_date = date('Y-m-d', strtotime($date));
        //  print_r($schedule); exit;
        $schedule->save();
        echo 1;
        //  return redirect()->back();
    }

    public function leadPriceInfo(Request $request)
    {
        // echo"<pre>"; print_r($request->all()); exit;
        $price = new LeadPrice();
        $price->service_id = $request->service_id;
        $price->service = $request->service_name;
        $price->unit_price = $request->unit_price;
        $price->qty = $request->qty;
        $price->sub_total = $request->sub_total;
        $price->discount = $request->discount;
        $price->net_total = $request->net_total;
        $price->save();

        $priceInfo = LeadPrice::where('service_id', $price->service_id)->first();
        // print_r($priceInfo); exit;

        $subtotal = $priceInfo->unit_price * $priceInfo->qty;
        $nettotal = 0;
        $discount = 0;

        // foreach ($priceInfo as $price) {

        //     $subtotal += $price->sub_total;
        //     $nettotal += $price->net_total;
        //     $discount += $price->discount;
        // }

        $priceInfoDetails = '
        <tr>
        <input type="text" class="form-control price" value="' . $priceInfo->serivce . '" name="service_name[]">
        <td>' . $priceInfo->service_id . '</td>
        <td>' . $priceInfo->service . '</td>
        <td><input type="number" class="form-control price" value="' . $priceInfo->unit_price . '"></td>
        <td><input type="number" class="form-control quantity-input" placeholder="" value="' . $priceInfo->qty . '"></td>
        <td><input type="number" class="form-control sub-total-input" placeholder="" value="' . $subtotal . '"></td>
        <td><input type="number" class="form-control discount-input" placeholder="" value="' . $priceInfo->discount . '"></td>
        <td><input type="number" class="form-control net-total-input" placeholder="" value="' . $priceInfo->net_total . '"></td>

        <td>
            <button class="btn btn-danger ripple remove-service-btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cross m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="currentColor" d="M6 6l12 12" />
                <path stroke="currentColor" d="M6 18L18 6" />
                </svg>

            </button>
        </td>
        </tr>
        ';
        return $priceInfoDetails;
    }

    public function downloadLeadQuotation()
    {
        $company_id = $_GET['company_id'];
        $service_id = $_GET['service_id'];
        $company = Company::where('id', $company_id)->first();
        $termCondition = TermCondition::where('company_id', $company_id)->get();
        $service = Services::where('id', $service_id)->get();
        $pdf = Pdf::loadView('pdf', ['company' => $company, 'termCondition' => $termCondition, 'service' => $service]);
        // $pdfPath = public_path('pdf/quotation.pdf');
        // // print_r(public_path()); exit;
        // $pdf->save($pdfPath);
        return $pdf->download('quotation.pdf');
        // return redirect()->back();
    }

    // public function sendEmail(Request $request)
    // {

    //     $company = Company::where('id', $request->company_id)->first();
    //     $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();
    //     $customer = DB::table('customers')->where('id', $request->customer_id)->first();

    //     // print_r($emailTemplate);exit;

    //     $data['title'] = $emailTemplate->title;
    //     $data['subject'] = $emailTemplate->subject;
    //     $data['body'] = $emailTemplate->body;
    //     $data['company_name'] = $company->company_name;
    //     $data["email"] = $request->email;

    //     $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    //     $leadCustomerDetail =  new Lead();
    //     $leadCustomerDetail->unique_id = $uniqueID;
    //     $leadCustomerDetail->customer_id = $request->customer_id;
    //     $leadCustomerDetail->service_address = $request->service_id;
    //     $leadCustomerDetail->billing_address = $request->billing_address;
    //     $leadCustomerDetail->schedule_date = $request->schedule_date;
    //     $leadCustomerDetail->amount = $request->amount_val;
    //     $leadCustomerDetail->tax = $request->tax;
    //     $leadCustomerDetail->grand_total = $request->grand_total_amount;
    //     $leadCustomerDetail->tax_percent = $request->tax_percent;
    //     $leadCustomerDetail->status = 2;
    //     // dd($leadCustomerDetail);
    //     $leadCustomerDetail->save();

    //     $serviceIds = explode(',', $request->input('service_ids'));
    //     $serviceDescription = explode(',', $request->input('service_descriptions'));
    //     $serviceUnitPrice = explode(',', $request->input('unit_price'));
    //     $serviceQuantity = explode(',', $request->input('quantities'));
    //     $serviceDiscount = explode(',', $request->input('discounts'));
    //     $serviceNames = explode(',', $request->input('service_names'));
    //     // Assuming all arrays have the same length
    //     $count = count($serviceIds);
    //     $leadId = $leadCustomerDetail->id;

    //     for ($i = 0; $i < $count; $i++) {
    //         $service = new LeadServices();

    //         $service->lead_id = $leadId;
    //         $service->service_id = $serviceIds[$i] ?? '';
    //         $service->name = $serviceNames[$i] ?? '';
    //         $service->description = $serviceDescription[$i] ?? " ";
    //         $service->unit_price = $serviceUnitPrice[$i];
    //         $service->quantity = $serviceQuantity[$i] ?? 0;
    //         $service->discount = $serviceDiscount[$i] ?? 0;
    //         // $grossAmount = ($service->unit_price * $service->quantity) * (1 - (floatval($service->discount) / 100));

    //         $service->gross_amount = 0;
    //        $service->save();
    //     }

    //     $leadPriceInfo  = new LeadPriceInfo([
    //         'lead_id' => $leadCustomerDetail->id,
    //         'deposite_type' => $request->deposite_type,
    //         'date_of_cleaning' => $request->cleaning_date,
    //         'time_of_cleaning' => $request->cleaning_time,

    //     ]);
    //    $leadPriceInfo->save();

    //     for ($i = 0; $i < $count; $i++) {
    //         $price = new LeadPrice();

    //         $price->service_id = $serviceIds[$i];
    //         $price->service = $serviceNames[$i];
    //         $price->unit_price = $serviceUnitPrice[$i];
    //         $price->qty = $serviceQuantity[$i] ?? 0;
    //         $price->discount = $serviceDiscount[$i] ?? 0;
    //         $sub_total = $price->unit_price;
    //         $net_total = $price->unit_price;
    //         // $net_total = ($price->unit_price * $price->qty) * (1 - (floatval($price->discount) / 100));

    //         $price->sub_total = $sub_total;
    //         $price->net_total = $net_total;
    //        $price->save();
    //     }

    //     $leadPaymentInfo = new LeadPaymentInfo();
    //     $leadPaymentInfo->lead_id = $leadCustomerDetail->id;
    //     $leadPaymentInfo->payment_type = $request->payment_option;
    //     $leadPaymentInfo->total_amount = $request->total_amount;
    //     $leadPaymentInfo->advance_amount = $request->advance_amount;


    //     if ($request->has('payment_file')) {
    //         // Get image file
    //         $image = $request->file('payment_file');

    //         $name = Str::slug($request->input('payment_option')) . '_' . time();
    //         $folder = '/uploads/images/';
    //         $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
    //         $this->uploadOne($image, $folder, 'public', $name);
    //         $leadPaymentInfo->file = $filePath;
    //     }
    //     if ($request->has('full_payment_file')) {
    //         // Get image file
    //         $image = $request->file('full_payment_file');

    //         $name = Str::slug($request->input('payment_option')) . '_' . time();
    //         $folder = '/uploads/images/';
    //         $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
    //         $this->uploadOne($image, $folder, 'public', $name);
    //         $leadPaymentInfo->file = $filePath;
    //     }
    //    $leadPaymentInfo->save();

    //     $quotation = new Quotation([
    //         'customer_id' => $request->customer_id,
    //         'company_id' => $request->company_id,
    //         'lead_id' => $leadCustomerDetail->id,
    //         'quotation_no' => rand(1234, 9999),
    //     ]);
    //     $quotation->save();

    //     $service = Services::where('id', $price->service_id)->get();
    //     $termCondition = TermCondition::where('company_id', $request->company_id)->get();
    //     $pdf = PDF::loadView('pdf', ['company' => $company, 'termCondition' => $termCondition, 'service' => $service]);
    //     $attachment = $pdf->output();

    //     // Mail::send('admin.leads.mail', $data, function($message)use($data, $pdf) {
    //     //     $message->to($data["email"])
    //     //             ->subject($data["subject"])
    //     //             ->attach($pdf->output(),'document.pdf');
    //     // });

    //     Mail::to($customer->email)->send(new SendLeadEmail($emailTemplate->title, $emailTemplate->subject, $emailTemplate->body, $company->company_name, $leadCustomerDetail->id, $attachment));

    //     //  echo"<pre>"; print_r($leadCustomerDetail); exit;
    //     return response()->json(['success' => 'Email Send successfully!']);
    // }

    public function sendEmail(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time'
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Lead::where('id', $request->lead_id)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['status'=>'failed', 'message' => 'Lead already Approved']);
        }

        if($request->type == "add")
        {
            // $leadId = $this->temp_lead_store($request);
            $leadId = $this->draft_lead_store($request);

            $db_quotation = Quotation::where('lead_id', $leadId)->first();

            // log data store start

            LogController::store('lead', 'Lead Created and Email Send', $leadId);
            LogController::store('quotation', 'Quotation Created from lead and Email Send', $db_quotation->id ?? '');

            // log data store end
        }
        else if($request->type == "update")
        {
            $leadId = $this->temp_lead_update($request);

            $db_quotation = Quotation::where('lead_id', $leadId)->first();

            // log data store start

            LogController::store('lead', 'Lead Updated and Email Send', $leadId);
            LogController::store('quotation', 'Quotation Updated from lead and Email Send', $db_quotation->id ?? '');

            // log data store end
        }
        else
        {
            return response()->json(['status'=>'failed', 'message' => 'Email is not Send successfully!']);
        }

        // lead start

        $lead = Lead::find($leadId);
        $lead->status = 2;
        $lead->save();

        $lead_details = LeadServices::where('lead_id', $leadId)->get();

        // lead end

        // quotation start

        $quotation = Quotation::where('lead_id', $leadId)->first();
        $quotation->status = 2;
        $quotation->save();

        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();

        $ServiceAddress = ServiceAddress::find($quotation->service_address);

        if($ServiceAddress)
        {
            $quotation->service_address_details = $ServiceAddress->address;
            $quotation->service_address_unit_number = $ServiceAddress->unit_number;
        }
        else
        {
            $quotation->service_address_details = "";
            $quotation->service_address_unit_number = "";
        }

        // quotation end

        // calculation start

        $subtotal = 0;

        foreach($quotation_details as $item)
        {
            $subtotal += $item->unit_price;
        }

        $nettotal = $quotation->amount;

        if($quotation->discount_type == "percentage")
        {
            $discount_amt = $nettotal * $quotation->discount/100;
        }
        else
        {
            $discount_amt = $quotation->discount;
        }

        $total = $nettotal - $discount_amt;

        $quotation->subtotal = $subtotal;
        $quotation->nettotal = $nettotal;
        $quotation->discount_amt = $discount_amt;
        $quotation->total = $total;

        // calculation end

        $company = Company::where('id', $request->company_id)->first();

        if(!empty($company->company_logo))
        {
            $company->image_path = "/company_logos/$company->company_logo";
        }
        else
        {
            $company->image_path = "";
        }

        if(!empty($company->qr_code))
        {
            $company->qr_code_path = "/qr_code/$company->qr_code";
        }
        else
        {
            $company->qr_code_path = "";
        }

        if(!empty($company->stamp))
        {
            $company->stamp_path = "/stamp/$company->stamp";
        }
        else
        {
            $company->stamp_path = "";
        }

        // invoice footer logo

        $company_invoice_footer_logo = DB::table('company_invoice_footer_logo')
                                            ->where('company_id', $request->company_id)
                                            ->get();

        foreach($company_invoice_footer_logo as $item)
        {          
            $item->invoice_footer_logo_path = '/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;            
        }                                  

        $company->company_invoice_footer_logo = $company_invoice_footer_logo;

        $customer = DB::table('customers')->where('id', $request->customer_id)->first();
        $payment_terms = PaymentTerms::find($customer->payment_terms);

        if($payment_terms)
        {
            $customer->payment_terms_value = $payment_terms->payment_terms;
        }
        else
        {
            $customer->payment_terms_value = "";
        }

        $term_condition = TermCondition::where('company_id', $request->company_id)->get();

        $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();

        // mail send start

        if($request->filled('email_cc'))
        {
            $email_cc = $request->email_cc;

            $cc_arr = [];

            foreach(json_decode($email_cc) as $value)
            {
                $temp = $value->value;

                array_push($cc_arr, $temp);
            }

            $new_cc = implode(',', $cc_arr);
        }
        else
        {
            $new_cc = '';
            $cc_arr = [];
        }

        $data['title'] = $emailTemplate->title;
        $data['subject'] = $request->email_subject;
        $data['body'] = $request->email_body;
        $data["to_email"] = $request->email_to;
        $data["cc"] = $cc_arr;
        $data["bcc"] = $request->email_bcc;

        $data['lead_id'] = $leadId;
        $data['lead'] = $lead;
        $data['lead_details'] = $lead_details;
        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $files = PDF::loadView('admin.leads.invoice', $data);
        $file_name = $quotation->quotation_no.".pdf";

        Mail::send('admin.leads.mail', $data, function ($message) use ($data, $files, $file_name) {
            // $message->from(Auth::user()->email);
            $message->to($data['to_email'])
                    ->cc($data['cc'] ?? [])
                    ->bcc($data['bcc'] ?? [])
                    ->subject($data['subject'])
                    // ->attach($files);
                    ->attachData($files->output(), $file_name);
        });

        // mail send end

        return response()->json(['status'=>'success', 'message' => 'Email Send successfully!']);
    }


    public function sendPaymentEmail(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();
        $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();
        $customer = DB::table('customers')->where('id', $request->customer_id)->first();

        // print_r($emailTemplate);exit;

        $data['title'] = $emailTemplate->title;
        $data['subject'] = $emailTemplate->subject;
        $data['body'] = $emailTemplate->body;
        $data['company_name'] = $company->company_name;
        $data["email"] = $request->email;

        $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $leadCustomerDetail =  new Lead();
        $leadCustomerDetail->unique_id = $uniqueID;
        $leadCustomerDetail->customer_id = $request->customer_id;
        $leadCustomerDetail->service_address = $request->service_id;
        $leadCustomerDetail->billing_address = $request->billing_address;
        $leadCustomerDetail->schedule_date = $request->schedule_date;
        $leadCustomerDetail->amount = $request->amount_val;
        $leadCustomerDetail->tax = $request->tax;
        $leadCustomerDetail->grand_total = $request->grand_total_amount;
        $leadCustomerDetail->tax_percent = $request->tax_percent;
        $leadCustomerDetail->status = 2;
        // dd($leadCustomerDetail);
        $leadCustomerDetail->save();

        $serviceIds = explode(',', $request->input('service_ids'));
        $serviceDescription = explode(',', $request->input('service_descriptions'));
        $serviceUnitPrice = explode(',', $request->input('unit_price'));
        $serviceQuantity = explode(',', $request->input('quantities'));
        $serviceDiscount = explode(',', $request->input('discounts'));
        $serviceNames = explode(',', $request->input('service_names'));
        // Assuming all arrays have the same length
        $count = count($serviceIds);
        $leadId = $leadCustomerDetail->id;

        for ($i = 0; $i < $count; $i++) {
            $service = new LeadServices();

            $service->lead_id = $leadId;
            $service->service_id = $serviceIds[$i] ?? '';
            $service->name = $serviceNames[$i] ?? '';
            $service->description = $serviceDescription[$i] ?? " ";
            $service->unit_price = $serviceUnitPrice[$i];
            $service->quantity = $serviceQuantity[$i] ?? 0;
            $service->discount = $serviceDiscount[$i] ?? 0;
            // $grossAmount = ($service->unit_price * $service->quantity) * (1 - (floatval($service->discount) / 100));

            $service->gross_amount = 0;
            $service->save();
        }

        $leadPriceInfo  = new LeadPriceInfo([
            'lead_id' => $leadCustomerDetail->id,
            'deposite_type' => $request->deposite_type,
            'date_of_cleaning' => $request->cleaning_date,
            'time_of_cleaning' => $request->cleaning_time,

        ]);
        $leadPriceInfo->save();

        for ($i = 0; $i < $count; $i++) {
            $price = new LeadPrice();

            $price->service_id = $serviceIds[$i];
            $price->service = $serviceNames[$i];
            $price->unit_price = $serviceUnitPrice[$i];
            $price->qty = $serviceQuantity[$i] ?? 0;
            $price->discount = $serviceDiscount[$i] ?? 0;
            $sub_total = $price->unit_price;
            $net_total = $price->unit_price;
            // $net_total = ($price->unit_price * $price->qty) * (1 - (floatval($price->discount) / 100));

            $price->sub_total = $sub_total;
            $price->net_total = $net_total;
            $price->save();
        }

        $leadPaymentInfo = new LeadPaymentInfo();
        $leadPaymentInfo->lead_id = $leadCustomerDetail->id;
        $leadPaymentInfo->payment_type = $request->payment_option;
        $leadPaymentInfo->total_amount = $request->total_amount;
        $leadPaymentInfo->advance_amount = $request->advance_amount;


        if ($request->has('payment_file')) {
            // Get image file
            $image = $request->file('payment_file');

            $name = Str::slug($request->input('payment_option')) . '_' . time();
            $folder = '/uploads/images/';
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $leadPaymentInfo->file = $filePath;
        }
        if ($request->has('full_payment_file')) {
            // Get image file
            $image = $request->file('full_payment_file');

            $name = Str::slug($request->input('payment_option')) . '_' . time();
            $folder = '/uploads/images/';
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $leadPaymentInfo->file = $filePath;
        }
        $leadPaymentInfo->save();

        $quotation = new Quotation([
            'customer_id' => $request->customer_id,
            'company_id' => $request->company_id,
            'lead_id' => $leadCustomerDetail->id,
            'quotation_no' => rand(1234, 9999),
        ]);
        $quotation->save();
        $service = Services::where('id', $price->service_id)->get();
        $termCondition = TermCondition::where('company_id', $request->company_id)->get();
        $pdf = PDF::loadView('pdf', ['company' => $company, 'termCondition' => $termCondition, 'service' => $service]);
        $attachment = $pdf->output();

        // Mail::send('admin.leads.mail', $data, function($message)use($data, $pdf) {
        //     $message->to($data["email"])
        //             ->subject($data["subject"])
        //             ->attach($pdf->output(),'document.pdf');
        // });
        Mail::to($customer->email)->send(new SendLeadEmail($emailTemplate->title, $emailTemplate->subject, $emailTemplate->body, $company->company_name, $leadCustomerDetail->id, $attachment));

        //  echo"<pre>"; print_r($leadCustomerDetail); exit;
        return response()->json(['success' => 'Email Send successfully!']);
    }

    // public function getEmailData(Request $request){

    //     $customer = Crm::where('id',$request->customer_id)->first();
    //     $data = EmailTemplate::where('id',$request->template_id)->first();
    //     $company = Company::where('id', $request->company_id)->first();
    //     // print_r($data); exit;
    //     // $pdf = PDF::loadView('pdf', ['company' => $company]);
    //     // $quotationPdf  = $pdf->output();

    //     $emailTemplate = '
    //     <div class="row">
    //     <div class="col-md-12">
    //         <div class="mb-3">
    //             <label class="form-label">To:</label>
    //             <input type="text" class="form-control" name="example-text-input"
    //                 id="emailInput" placeholder="Type email" value="'.$customer->email.'">
    //         </div>
    //         <div class="mb-3">
    //             <label class="form-label">Title:</label>
    //             <input type="text" class="form-control" name="example-text-input"
    //                 id="emailTitle" value="'.$data->title.'">
    //         </div>
    //         <div class="mb-3">
    //             <label class="form-label">Subject:</label>
    //             <input type="text" class="form-control" name="example-text-input"
    //                 id="emailSubject" value="'.$data->subject.'">
    //         </div>
    //         <div class="mb-3">
    //             <label class="form-label">Body:</label>
    //             <textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Content..">'.$data->body.'</textarea>
    //         </div>
    //     </div>
    //     <div class="col-md-12 text-end">
    //         <button class="btn btn-info" onclick="emailSend(event)">Confirm</button>
    //     </div>
    //     ';

    //     return $emailTemplate;
    // }



    // public function getlLeadPreview(Request $request)
    // {
    //     // return $request->all();

    //     $company = Company::where('id', $request->company_id)->first();
    //     $customer = Crm::where('id', $request->customer_id)->first();
    //     // dd($customer);

    //     $term_condition = TermCondition::where('company_id', $request->company_id)->get();


    //     $term = '';
    //     foreach ($term_condition as $item) {
    //         $term .= '<li>
    //         ' . $item->term_condition . '
    //     </li>';
    //     }

    //     $imagePath = 'application/public/company_logos/' . $company->company_logo;

    //     $route = route('download.quotation', $company->id);
    //     $output = '';
    //     // foreach($company as $item){
    //     $output .= '<div class="row">
    //                     <div class="col-md-12">
    //                         <div class="card card-link card-link-pop">
    //                             <div class="card-status-start bg-primary"></div>
    //                             <div class="card-stamp">
    //                                 <div class="card-stamp-icon bg-white text-primary">
    //                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin"
    //                                     width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
    //                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
    //                                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
    //                                     <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
    //                                     <path
    //                                         d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
    //                                     </path>
    //                                 </svg>
    //                                 </div>
    //                             </div>
    //                             <div class="page-header d-print-none">
    //                                 <div class="container-xl">
    //                                     <div class="row g-2 align-items-center">
    //                                         <div class="col-auto ms-auto d-print-none">
    //                                             <button type="button" class="btn btn-primary" id="print-btn" onclick="printQuotation()">
    //                                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
    //                                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
    //                                                     stroke-linecap="round" stroke-linejoin="round">
    //                                                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
    //                                                     <path
    //                                                         d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
    //                                                     </path>
    //                                                     <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
    //                                                     <path
    //                                                         d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
    //                                                     </path>
    //                                                 </svg>
    //                                                 Print Quotation
    //                                             </button>
    //                                             <a class="btn btn-primary" onclick="downloadQuotation()">
    //                                                 Download Quotation
    //                                             </a>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                             <div class="page-body">
    //                                 <div class="container-xl">
    //                                     <div class="card card-lg" id="invoice-card">

    //                                         <div class="card-body">
    //                                             <div class="d-flex mb-2">
    //                                                 <div class="logo p-0 text-center">
    //                                                     <img src="dist/img/invoice-logo/logo-1.png" alt="" class="img-fluid"
    //                                                         style="height: 100px;">
    //                                                     <div class="img">
    //                                                         <img src="' . $imagePath . '" alt="logo" style="background-color:black; max-width:100px; height: 100px;">
    //                                                     </div>
    //                                                 </div>
    //                                                 <div class="company-dece pe-0 ps-3">
    //                                                     <h1 class="title" style="font-size: 26px;"><b>' . $company->company_name . '</b></h1>
    //                                                     <p class="m-0 fs-12 lh-13">' . $company->company_address . '</p>
    //                                                     <p class="m-0 fs-12 lh-13">Tel: ' . $company->contact_number . ' Fax: ' . $company->contact_number . ' Phone: ' . $company->contact_number . '</p>
    //                                                     <p class="m-0 fs-12 lh-13">Website: ' . $company->website . '</p>
    //                                                     <p class="m-0 fs-12 lh-13">Co. Reg No: 201524788N
    //                                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg
    //                                                         No.
    //                                                         201524788N</p>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="row">
    //                                                 <div class="col-8"></div>
    //                                                 <div class="col-4 text-center">
    //                                                     <h3>QUOTATION</h3>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="row mb-3">

    //                                                 <div class="col-1 col-sm-1 text-center">
    //                                                     <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
    //                                                 </div>
    //                                                 <div class="col-4 col-sm-4 ps-0">
    //                                                     <div class="fs-12 lh-13">' . $customer->individual_company_name . '</div>
    //                                                     <div class="fs-12 lh-13">' . $customer->customer_name . '</div>
    //                                                     <div class="fs-12 lh-13">' . $customer->default_address . '</div>
    //                                                     <div class="fs-12 lh-13">+' . $customer->mobile_number . '</div>
    //                                                     <div class="fs-12 lh-13">' . $customer->email . '</div>
    //                                                 </div>

    //                                                 <div class="col-7 col-sm-7">
    //                                                     <div class="row">

    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-12 lh-13"><b>Quotation No:</b></div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div class="fs-12 lh-13">ACQ-23-000115</div>
    //                                                         </div>
    //                                                     </div>

    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-12 lh-13"><b>Issued Date:</b></div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div class="fs-12 lh-13">08 Feb 2023</div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-12 lh-13"><b>Expiry Date</b></div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div class="fs-12 lh-13">22 Feb 2023</div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-12 lh-13"><b>Issue By:
    //                                                                 </b></div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div class="fs-12 lh-13">Leau
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>
    //                                                 </div>

    //                                             </div>

    //                                             <div class="table-responsive-sm">
    //                                                 <table class="quotation-table table">
    //                                                     <thead>
    //                                                         <tr>
    //                                                             <th>SERVICE</th>
    //                                                             <th>DESCRIPTION</th>
    //                                                             <th>QTY</th>
    //                                                             <th>UNIT PRICE</th>
    //                                                             <th>Total</th>
    //                                                         </tr>
    //                                                     </thead>
    //                                                     <tbody class="quotation-table-content">

    //                                                     </tbody>
    //                                                 </table>
    //                                             </div>
    //                                             <div class="row mb-2">
    //                                                 <div class="col-7 col-sm-7">
    //                                                     <div class="row mb-2">
    //                                                         <div class="col-3 col-md-3 text-start">
    //                                                             <div class="fs-12 lh-13"><b>Remarks:</b></div>
    //                                                         </div>
    //                                                         <div class="col-8 col-md-8 text-start">
    //                                                             <div></div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row mb-2">
    //                                                         <div class="col-3 col-md-3 text-start">
    //                                                             <div class="fs-10 lh-13"><b>Payment Term:</b></div>
    //                                                         </div>
    //                                                         <div class="col-9 col-md-9 text-start">
    //                                                             <div class="fs-10 lh-13">C.O.D</div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row mb-2">
    //                                                         <div class="col-3 col-md-3 text-start">
    //                                                             <div class="fs-10 lh-13"><b>Bank Detail:</b></div>
    //                                                         </div>
    //                                                         <div class="col-9 col-md-9 text-start">
    //                                                             <div class="fs-10 lh-13">DBS Current: 017-904550-9</div>
    //                                                             <div class="fs-10 lh-13">Bank Code: 7171 / Branch Code: 017</div>
    //                                                             <div class="fs-10 lh-13">OCBC Current: 686-026980-001</div>
    //                                                             <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 686</div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row mb-2">
    //                                                         <div class="col-3 col-md-3 text-start">
    //                                                             <div class="fs-10 lh-13"><b>Commence Date:</b></div>
    //                                                         </div>
    //                                                         <div class="col-9 col-md-9 text-start">
    //                                                             <div class="fs-10 lh-13">OCBC Current: 695-163-311-001
    //                                                             </div>
    //                                                             <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 695</div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row mb-2">
    //                                                         <div class="col-3 col-md-3 text-start">
    //                                                             <div class="fs-10 lh-13"><b>Payment Method:</b></div>
    //                                                         </div>
    //                                                         <div class="col-9 col-md-9 text-start">
    //                                                             <div class="fs-10 lh-13" style="text-decoration: underline;">"Bank Code:
    //                                                                 7339 / Branch Code: 686"
    //                                                             </div>
    //                                                             <div class="fs-10 lh-13">All cheques are to be crossed and made payable to
    //                                                             </div>
    //                                                             <div class="fs-10 lh-13" style="text-decoration: underline;"><b>"@bsolute
    //                                                                     Aircon Pte Ltd"</b></div>
    //                                                         </div>
    //                                                     </div>

    //                                                 </div>
    //                                                 <div class="col-2 col-sm-2">

    //                                                 </div>
    //                                                 <div class="col-3 col-sm-3">
    //                                                     <div class="row" style="display: none;">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-10 lh-13">Sub Total:</div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div>
    //                                                                 <div class="d-flex justify-content-between fs-10 lh-13">
    //                                                                     $<div class="subTotal" id="preview_subTotal"> </div>
    //                                                                 </div>
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-10 lh-13">Sub Total:</div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div>
    //                                                                 <div class="d-flex justify-content-between fs-10 lh-13">
    //                                                                     $<div class="netTotal" id="preview_netTotal"> </div>
    //                                                                 </div>
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-10 lh-13">
    //                                                                 Discount:
    //                                                             </div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div>
    //                                                                 <div class="d-flex justify-content-between fs-10 lh-13">
    //                                                                     $<div id="preview_discount"></div>
    //                                                                 </div>
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-10 lh-13">Total:</div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div>
    //                                                                 <div class="d-flex justify-content-between fs-10 lh-13">
    //                                                                     $<div id="preview_total"></div>
    //                                                                 </div>
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-10 lh-13">GST @ <span id="preview_tax"></span>%:</div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div>
    //                                                                 <div class="d-flex justify-content-between fs-10 lh-13">
    //                                                                     $<div id="preview_tax_amt"></div>
    //                                                                 </div>
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="row">
    //                                                         <div class="col-8 col-md-8 text-end">
    //                                                             <div class="fs-10 lh-13">Grand Total:</div>
    //                                                         </div>
    //                                                         <div class="col-4 col-md-4 text-end">
    //                                                             <div>
    //                                                                 <div class="d-flex justify-content-between fs-10 lh-13">
    //                                                                     $<div class="grandTotal" id="preview_grandTotal"></div>
    //                                                                 </div>
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="row  terms-row">
    //                                                 <h3><b>Terms and Conditions</b></h3>
    //                                                 ' . $term . '
    //                                             </div>
    //                                             <div class="row">

    //                                                 <div class="col-5">
    //                                                     <div class="signature-container">

    //                                                         <canvas id="signatureCanvas"></canvas>
    //                                                         <div class="signature-line"></div>
    //                                                         <div class="signature-details">
    //                                                             <span class="name fs-12 lh-13"> Customers Acknowledgement </span>
    //                                                             <span class="time fs-10 lh-13">Designation:</span>
    //                                                             <span class="time fs-10 lh-13">Company Stamp:</span>
    //                                                         </div>

    //                                                     </div>
    //                                                 </div>
    //                                                 <div class="col-2">

    //                                                 </div>
    //                                                 <div class="col-5">
    //                                                     <div class="signature-container">

    //                                                         <canvas id="signatureCanvas"></canvas>
    //                                                         <div class="signature-line"></div>
    //                                                         <div class="signature-details">
    //                                                             <span class="name fs-12 lh-13">Singapore Carpet Cleaning Pte Ltd</span>
    //                                                             <span class="time fs-10 lh-13">Designation : Sales Coordinator</span>
    //                                                         </div>

    //                                                     </div>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="row">
    //                                                 <div class="col-sm-12">
    //                                                     <h3><b>This is a computer generated invoice therefore no signature required.</b>
    //                                                     </h3>
    //                                                     <div class="d-flex footer-logo justify-content-between">
    //                                                         <div class="img">
    //                                                             <img src="' . $imagePath . '" alt="logo" style="background-color:black; max-width:100px; height: 100px;">
    //                                                         </div>
    //                                                     </div>
    //                                                 </div>

    //                                             </div>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                     <button type="button" onclick="sendByEmail()" class="btn btn-info w-100 mt-3"
    //                             data-dismiss="modal" style="width: 150px !important;">Send By mail</button>
    //                     <button type="button"  class="btn btn-info w-100 mt-3"
    //                             data-dismiss="modal"  onclick="confirm_btn()" style="width: 150px !important; margin-left:auto;">Confirm</button>
    //                 </div>';

    //     // }
    //     // return response()->json($company);
    //     print_r($output);
    //     exit;
    // }


    public function getlLeadPreview(Request $request)
    {
        // return $request->all();

        $lead = Lead::find($request->lead_id);
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

        if($db_service_address)
        {
            $service_address = $db_service_address->address;
        }
        else
        {
            $service_address = "";
        }

        $term_condition = TermCondition::where('company_id', $request->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        $route = route('download.quotation', $company->id);

        $data['lead'] = $lead;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;
        $data['route'] = $route;
        $data['imagePath'] = $imagePath;
        $data['issue_by'] = Auth::user()->first_name . " " . Auth::user()->last_name;
        $data['service_address'] = $service_address;

        return view('admin.leads.preview', $data);
    }


    public function deletePriceInfo($id)
    {
        // print_r($id); exit;
        LeadPrice::where('id', $id)->delete();

        return redirect()->back();
    }

    public function confirmMail($lead_id)
    {

        $lead = Lead::where('id', $lead_id)->first();

        if (!empty($lead->id)) {
            $data = new Lead([
                'unique_id' => $lead->unique_id,
                'customer_id' => $lead->customer_id,
                'service_address' => $lead->service_address,
                'billing_address' => $lead->billing_address,
                'schedule_date' => $lead->schedule_date,
                'amount' => $lead->amount,
                'tax' => $lead->tax,
                'grand_total' => $lead->grand_total,
                'tax_percent' => $lead->tax_percent,
                'time_of_cleaning' => $lead->time_of_cleaning,
                'date_of_cleaning' => $lead->date_of_cleaning,
                'status'  => 3,
            ]);

            $data->save();
        }
        echo "Your Confirmation is Successfuly Send";
        exit;
        // echo "<pre>"; print_r(env('APP_URL').'/mail/confirm/1'); exit;
    }

    public function temp_lead_store($request)
    {
        $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

        $company = Company::find($request->company_id);

        if($company)
        {
            $comp_short_name = $company->short_name;
            $lead_data = Quotation::where('company_id', $request->company_id)->orderBy('created_at', 'desc')->get();
            $quotation_no = $comp_short_name . "Q-". substr(date('Y'), -2) . "-";
        }
        else
        {
            $comp_short_name = "";
            $lead_data = Quotation::orderBy('created_at', 'desc')->get();
            $quotation_no = "Q-". substr(date('Y'), -2) . "-";
        }

        if($lead_data->isEmpty())
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

        // lead customer details start

        $leadCustomerDetail =  new Lead([
            'unique_id' => $uniqueID,
            'customer_id' => $request->input('customer_id'),
            'company_id' => $request->company_id,
            'service_address' => $request->input('service-address-radio'),
            'billing_address' => $request->input('billing-address-radio'),
            // 'schedule_date' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning')))),
            'time_of_cleaning' => $request->time_of_cleaning,
            'quotation_no' => $quotation_no,
            'created_by' => Auth::user()->id,
            'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name,
            'status' => 1
        ]);

        if($request->filled('date_of_cleaning'))
        {
            $leadCustomerDetail->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
        }
        else
        {
            $leadCustomerDetail->schedule_date = null;
        }

        $leadCustomerDetail->save();

        // lead customer details end

        $leadId = $leadCustomerDetail->id;
        $total_amount = 0;

        // lead service start

        $preview_service_id = $request->preview_service_id;
        $preview_service_product_code = $request->preview_service_product_code;
        $preview_service_name = $request->preview_service_name;
        $preview_service_desc = $request->preview_service_desc;
        $preview_service_qty = $request->preview_service_qty;
        $preview_service_unitPrice = $request->preview_service_unitPrice;
        $preview_service_discount = $request->preview_service_discount;

        for($i=0; $i<count($preview_service_id); $i++)
        {
            $db_service = Services::find($preview_service_id[$i]);

            if($db_service)
            {
                $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
                $service_discount_amount = $service_total_amount * ($preview_service_discount[$i]/100);
                $gross_amount = $service_total_amount - $service_discount_amount;

                $service = new LeadServices();

                $service->lead_id = $leadId;
                $service->service_id = $preview_service_id[$i];
                $service->product_code = $preview_service_product_code[$i];
                $service->name = $preview_service_name[$i];
                $service->description = $preview_service_desc[$i];
                $service->unit_price = $preview_service_unitPrice[$i];
                $service->quantity = $preview_service_qty[$i];
                $service->discount = $preview_service_discount[$i];
                $service->gross_amount = $gross_amount;
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

                $service = new LeadServices();

                $service->lead_id = $leadId;
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

        // lead service end

        $discount_Type = $request->discount_type;

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

        $leadCustomerDetail->discount_type = $discount_Type;
        $leadCustomerDetail->discount = $discount;
        $leadCustomerDetail->tax = $tax_amt;
        $leadCustomerDetail->tax_percent = $tax;
        $leadCustomerDetail->tax_type = $tax_type;
        $leadCustomerDetail->amount = $total_amount;
        $leadCustomerDetail->grand_total = $grand_total;
        $leadCustomerDetail->save();

        // lead price info start

        $leadPriceInfo  = new LeadPriceInfo([
            'lead_id' => $leadCustomerDetail->id,
            'deposite_type' => $request->deposite_type,
            'date_of_cleaning' => $request->date_of_cleaning,
            'time_of_cleaning' => $request->time_of_cleaning,
        ]);
        $leadPriceInfo->save();

        // lead price info end

        // quotation start

        $get_lead = Lead::find($leadId);

        $new_quotation = $get_lead->replicate(['unique_id', 'pending_customer_approval_status']);
        $new_quotation->lead_id = $leadId;
        $new_quotation->setTable('quotations');
        $new_quotation->save();

        $get_lead_service = LeadServices::where('lead_id', $leadId)->get();

        foreach($get_lead_service as $item)
        {
            $quotation_service = new QuotationServiceDetail();

            $quotation_service->quotation_id = $new_quotation->id;
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

        // quotation end

        return $leadId;
    }

    public function temp_lead_update($request)
    {
        $company = Company::find($request->company_id);
        $comp_short_name = $company->short_name ?? '';

        $leadId = $request->lead_id;

        // lead customer details start
        
        $leadDetails = Lead::where('id', $leadId)->first();

        // quotation no start

        if($leadDetails->company_id != $request->company_id)
        {    
            $new_quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";

            $lead_data = Quotation::where('company_id', $request->company_id)->orderBy('created_at', 'desc')->get();

            if ($lead_data->isEmpty()) 
            {
                $new_quotation_no .= "000001";
            } 
            else 
            {
                // $last_quotation_no = $lead_data[0]->quotation_no;
                // $new_quotation_no .= sprintf("%06d", (int)substr($last_quotation_no, 7) + 1);

                $last_quotation_no = [];

                foreach($lead_data as $od)
                {
                    $last_quotation_no[] = explode("-", $od->quotation_no)[2];
                }

                $new_quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
            }
        }
        else
        {
            $quotation_no = $leadDetails->quotation_no;
            $quot_arr = explode("-", $quotation_no);
            $quot_arr[0] = $comp_short_name . "Q";
            $new_quotation_no = implode("-", $quot_arr);
        }

        // quotation no end

        $leadDetails->customer_id = $request->customer_id;
        $leadDetails->company_id = $request->company_id;
        $leadDetails->service_address = $request->input('service-address-radio');
        $leadDetails->billing_address = $request->input('billing-address-radio');

        if($request->filled('date_of_cleaning'))
        {
            $leadDetails->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
        }
        else
        {
            $leadDetails->schedule_date = null;
        }
        
        $leadDetails->time_of_cleaning = $request->time_of_cleaning;
        $leadDetails->quotation_no = $new_quotation_no;
        $leadDetails->remarks = $request->edit_lead_remarks;

        $leadDetails->save();

        // lead customer details end

        $total_amount = 0;

        // lead service start

        LeadServices::where('lead_id', $leadId)->delete();

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

                $service = new LeadServices();

                $service->lead_id = $leadId;
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

                $service = new LeadServices();

                $service->lead_id = $leadId;
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

        // lead service end

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

        $leadDetails->discount_type = $discount_Type;
        $leadDetails->discount = $discount;
        $leadDetails->tax = $tax_amt;
        $leadDetails->tax_percent = $tax;
        $leadDetails->tax_type = $tax_type;
        $leadDetails->amount = $total_amount;
        $leadDetails->grand_total = $grand_total;
        $leadDetails->save();

        // lead price info start

        // LeadPriceInfo::where('lead_id', $leadId)->delete();

        // $leadPriceInfo  = new LeadPriceInfo([
        //     'lead_id' => $leadId,
        //     'deposite_type' => $request->deposite_type,
        //     'date_of_cleaning' => $request->date_of_cleaning,
        //     'time_of_cleaning' => $request->time_of_cleaning,
        // ]);
        // $leadPriceInfo->save();

        // lead price info end

        // quotation start

        $get_quotation = Quotation::where('lead_id', $leadId)->first();

        if($get_quotation)
        {
            $quotation_id = $get_quotation->id;

            $get_lead = Lead::find($leadId);

            $get_quotation->lead_id = $leadId;
            $get_quotation->customer_id = $get_lead->customer_id;
            $get_quotation->company_id = $get_lead->company_id;
            $get_quotation->service_address = $get_lead->service_address;
            $get_quotation->billing_address = $get_lead->billing_address;
            $get_quotation->amount = $get_lead->amount;
            $get_quotation->discount_type = $get_lead->discount_type;
            $get_quotation->discount = $get_lead->discount;
            $get_quotation->tax = $get_lead->tax;
            $get_quotation->tax_type = $get_lead->tax_type;
            $get_quotation->tax_percent = $get_lead->tax_percent;
            $get_quotation->grand_total = $get_lead->grand_total;
            $get_quotation->schedule_date = $get_lead->schedule_date;
            $get_quotation->time_of_cleaning = $get_lead->time_of_cleaning;
            $get_quotation->quotation_no = $get_lead->quotation_no;
            $get_quotation->remarks = $get_lead->remarks;

            $get_quotation->save();

            QuotationServiceDetail::where('quotation_id', $quotation_id)->delete();

            $get_lead_service = LeadServices::where('lead_id', $leadId)->get();

            foreach($get_lead_service as $item)
            {
                $quotation_service = new QuotationServiceDetail();

                $quotation_service->quotation_id = $quotation_id;
                $quotation_service->service_id = $item->service_id;
                $quotation_service->product_code = $item->product_code;
                $quotation_service->name = $item->name;
                $quotation_service->description = $item->description;
                $quotation_service->unit_price = $item->unit_price;
                $quotation_service->quantity = $item->quantity;
                $quotation_service->discount = $item->discount;
                $quotation_service->gross_amount = $item->gross_amount;
                $quotation_service->total_session = $item->total_session;
                $quotation_service->service_type = $item->service_type;

                $quotation_service->save();
            }
        }

        return $leadId;

        // quotation end
    }

    public function view_lead(Request $request)
    {
        $leadId = $request->lead_id;

        // $lead = Lead::select('lead_customer_details.*', 'lead_price_info.*')
        //             ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
        //             ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
        //             ->where('lead_customer_details.id', $leadId)
        //             ->first();

        $lead = Lead::select('lead_customer_details.*')
                    ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
                    ->where('lead_customer_details.id', $leadId)
                    ->first();

        $customerId = $lead->customer_id;

        $customer = Crm::leftJoin('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
                        ->select(
                            'customers.*',
                            'customers.id as customer_id',
                            'language_spoken.language_name as language_name',
                        )
                        ->where('customers.id', $customerId)
                        ->first();

        $payment_terms = PaymentTerms::find($customer->payment_terms);

        if($payment_terms)
        {
            $customer->payment_terms_value = $payment_terms->payment_terms;
        }
        else
        {
            $customer->payment_terms_value = "";
        }

        $service_address = ServiceAddress::where('customer_id', $customerId)->where('id', $lead->service_address)->first();
        $billing_address = BillingAddress::where('customer_id', $customerId)->where('id', $lead->billing_address)->first();

        $company = Company::where('id', $lead->company_id)->first();

        $service = LeadServices::where('lead_id', $leadId)->get();

        foreach($service as $item)
        {
            $get_service_details = Services::find($item->service_id);
            if($get_service_details)
            {
                $item->total_session = $item->total_session ? $item->total_session : $get_service_details->total_session;
            }
        }

        // lead payment detail start

        $lead_payment_detail = LeadPaymentInfo::where('lead_id', $leadId)->get();

        $deposit = 0;
        $balance = 0;
        foreach($lead_payment_detail as $item)
        {
            if($item->payment_status == 1)
            {
                $deposit += $item->payment_amount;
            }
        }
        $balance = $lead->grand_total - $deposit;

        // lead payment detail end

        // start

        $subtotal = 0;

        foreach($service as $item)
        {
            $subtotal += $item->unit_price * $item->quantity;
        }

        $nettotal = $lead->amount;

        if($lead->discount_type == "percentage")
        {
            $discount_amt = $nettotal * $lead->discount/100;
        }
        else
        {
            $discount_amt = $lead->discount;
        }

        $total = $nettotal - $discount_amt;

        $lead->subtotal = $subtotal;
        $lead->nettotal = $nettotal;
        $lead->discount_amt = $discount_amt;
        $lead->total = $total;
        $lead->deposit = $deposit;
        $lead->balance = $balance;

        // end

        $asiaOptions = PaymentMethod::where('payment_method', "Asia Pay")->get();
        $offlineOptions = PaymentMethod::where('payment_method', "Offline")->get();

        $emailTemplates = EmailTemplate::get();

        $term_condition = TermCondition::where('company_id', $lead->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        $lead_payment_detail = LeadPaymentInfo::where('lead_id', $leadId)->get();

        foreach($lead_payment_detail as $item)
        {
            $lead_offline_payment_details = LeadOfflinePaymentDetail::where('lead_payment_id', $item->id)->get();

            if(!$lead_offline_payment_details->isEmpty())
            {
                $payment_options_arr = [];

                foreach($lead_offline_payment_details as $list)
                {
                    $payment_options_arr[] = $list->payment_option;
                }

                $payment_options = implode(", ", $payment_options_arr);
            }
            else
            {
                $payment_options = "";
            }

            if(!empty($payment_options))
            {
                $item->new_payment_method = ucfirst($item->payment_method) . " (". $payment_options .")";
            }
            else
            {
                $item->new_payment_method = ucfirst($item->payment_method);
            }
            
            $item->payment_proof_details = DB::table('payment_proof')->where('payment_id', $item->id)->get();
        }

        return view('admin.leads.view', compact('leadId', 'lead', 'customer', 'company', 'asiaOptions', 'offlineOptions', 'service', 'emailTemplates', 'service_address', 'billing_address', 'term_condition', 'imagePath', 'lead_payment_detail'));
    }

    // reject

    public function reject(Request $request)
    {
        // return $request->all();

        if($request->filled('reject_lead_id'))
        {
            $id = $request->reject_lead_id;

            $lead = Lead::find($id);

            if ($lead)
            {
                if($lead->status == 3 || $lead->status == 4)
                {
                    return response()->json(['status'=>'failed', 'message'=>'Lead already approved']);
                }

                $lead->status = 5;
                $lead->reject_remarks = $request->reject_remarks;
                $lead->save();

                // quotation start

                $quotation = Quotation::where('lead_id', $id)->first();

                if($quotation)
                {
                    $quotation->status = 5;
                    $quotation->reject_remarks = $request->reject_remarks;
                    $quotation->save();
                }

                // quotation end

                // log data store start

                LogController::store('lead', 'Lead Rejected', $id);
                LogController::store('quotation', 'Quotation Rejected from lead', $quotation->id ?? '');

                // log data store end

                return response()->json(['status'=>'success', 'message'=>'Lead Rejected successfully']);               
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Lead not found']);
            }
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Lead not found']);
        }
    }

    // reject_lead_mail

    public function reject_lead_mail($lead_id)
    {
        // return $lead_id;

        $lead = Lead::find($lead_id);

        if ($lead)
        {
            if($lead->status == 2 && $lead->pending_customer_approval_status == 1)
            {
                $lead->status = 5;
                $lead->pending_customer_approval_status = 3;
                $lead->save();

                // quotation start

                $quotation = Quotation::where('lead_id', $lead_id)->first();

                if($quotation)
                {
                    $quotation->status = 5;         
                    $quotation->pending_customer_approval_status = 3;      
                    $quotation->save();
                }

                // quotation end

                // return "Lead Rejected successfully"; 
                $msg = "Your Quotation has been Rejected ❌! Please feel free to contact us for further assistance."; 
            }
            else if($lead->pending_customer_approval_status == 2)
            {           
                // return "Lead already approved";           
                $msg = "The Quotation has already been Confirmed ✅! Please contact us if you need further assistance.";      
            }
            else if($lead->pending_customer_approval_status == 3)
            {
                // return "Lead already rejected"; 
                $msg = "The Quotation has already been Rejected ❌! Please contact us if you need further assistance."; 
            }
            else
            {
                // return "";
                $msg = "Quotation can not be Rejected!"; 
            }
        }
        else
        {
            // return "Lead not found"; 
            $msg = "Quotation not found"; 
        }

        $data['msg'] = $msg;

        return view('admin.leads.confirmation', $data);
    }

    // confirm_lead_mail

    public function confirm_lead_mail($lead_id)
    {
        // return $lead_id;

        $lead = Lead::find($lead_id);

        if ($lead)
        {
            if($lead->status == 2 && $lead->pending_customer_approval_status == 1)
            {
                // $lead->status = 2;
                $lead->pending_customer_approval_status = 2;
                $lead->save();

                // quotation start

                $quotation = Quotation::where('lead_id', $lead_id)->first();

                if($quotation)
                {       
                    $quotation->pending_customer_approval_status = 2;      
                    $quotation->save();
                }

                // quotation end
              
                // return "Lead Confirmed successfully"; 
                $msg = "Thank you for confirming our Quotation! Your booking is now being processed. 😊"; 
            }
            else if($lead->pending_customer_approval_status == 1)
            {    
                $lead->pending_customer_approval_status = 2;
                $lead->save();

                // quotation start

                $quotation = Quotation::where('lead_id', $lead_id)->first();

                if($quotation)
                {       
                    $quotation->pending_customer_approval_status = 2;      
                    $quotation->save();
                }

                // quotation end

                // return "Lead already approved";  
                $msg = "Thank you for confirming our Quotation! Your booking is now being processed. 😊"; 
            }
            else if($lead->pending_customer_approval_status == 2)
            {           
                // return "Lead already approved";  
                $msg = "The Quotation has already been Confirmed ✅! Please contact us if you need further assistance.";               
            }
            else if($lead->pending_customer_approval_status == 3)
            {
                // return "Lead already rejected"; 
                $msg = "The Quotation has already been Rejected ❌! Please contact us if you need further assistance.";
            }
            else
            {
                // return "";
                $msg = ""; 
            }
        }
        else
        {
            // return "Lead not found"; 
            $msg = "Quotation not found"; 
        }

        $data['msg'] = $msg;

        return view('admin.leads.confirmation', $data);
    }

    // confirm_lead
    public function confirm_lead(Request $request)
    {
        // return $request->all();

        if($request->filled('leadId'))
        {
            $leadId = $request->leadId;

            $lead = Lead::find($leadId);

            $db_quotation = Quotation::where('lead_id', $leadId)->first();

            if ($lead)
            {
                if($lead->status == 1)
                {
                    $lead->status = 2;
                    $lead->pending_customer_approval_status = 2;
                    $lead->save();                  

                    // log data store start

                    LogController::store('lead', 'Lead Confirmed', $leadId);
                    LogController::store('quotation', 'Quotation Confirmed from lead', $db_quotation->id ?? '');

                    // log data store end
                }
                else if($lead->status == 2)
                {
                    $lead->status = 3;
                    $lead->payment_advice = 2;
                    $lead->save();

                    // log data store start

                    LogController::store('lead', 'Lead Approved', $leadId);
                    LogController::store('quotation', 'Quotation Approved from lead', $db_quotation->id ?? '');

                    // log data store end
                }
                
                // quotation start

                $quotation = Quotation::where('lead_id', $leadId)->first();

                if($quotation)
                {
                    if($quotation->status == 1)
                    {
                        $quotation->status = 2;
                        $quotation->save();
                    }
                    else if($quotation->status == 2)
                    {
                        $quotation->status = 3;
                        $quotation->payment_advice = 2;
                        $quotation->save();
                    }
                }

                // quotation end               

                return response()->json(['status'=>'success', 'message'=>'Lead Confirmed successfully']);
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Lead not found']);
            }
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Lead not found']);
        }
    }


    // draft lead save start

    public function draft_save_step_1(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
            ],
            [
                'customer_id.required' => 'Select Customer First',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            // lead

            $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

            $quotation_no = "Q-". substr(date('Y'), -2) . "-";

            // $lead_data = Lead::orderBy('created_at', 'desc')->get();
            $lead_data = Quotation::orderBy('created_at', 'desc')->get();

            if($lead_data->isEmpty())
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

            $leadCustomerDetail = new Lead([
                'unique_id' => $uniqueID,
                'customer_id' => $request->input('customer_id'),
                'quotation_no' => $quotation_no,
                'created_by' => Auth::user()->id,
                'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name,
                'status' => 0,
                // 'created_at' => Carbon::now(),
                // 'updated_at' => Carbon::now(),
            ]);
    
            $leadCustomerDetail->save();

            $leadId = $leadCustomerDetail->id;

            // quotation

            $get_lead = Lead::find($leadId);

            $new_quotation = $get_lead->replicate(['unique_id', 'pending_customer_approval_status']);
            $new_quotation->lead_id = $leadId;
            $new_quotation->setTable('quotations');
            $new_quotation->save();

            // log data store start

            LogController::store('lead', 'Lead Created (Draft)', $leadId);

            // log data store end

            return response()->json(['status' => 'success', 'message' => 'Data saved Successfully', 'leadId' => $leadId]);
        }
    }

    public function draft_save_step_2(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'service_id' => 'required'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'service_id.required' => 'Select Service',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $company = Company::find($request->company_id);
            $comp_short_name = $company->short_name ?? '';

            $leadId = $request->lead_id;

            // lead customer details start

            $leadDetails = Lead::where('id', $leadId)->first();

            if(!$leadDetails)
            {
                return response()->json(['status' => 'failed', 'message' => "Lead not found"]);
            }

            // quotation no start

            if($leadDetails->company_id != $request->company_id)
            {    
                $new_quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";

                $lead_data = Quotation::where('company_id', $request->company_id)->orderBy('created_at', 'desc')->get();

                if ($lead_data->isEmpty()) 
                {
                    $new_quotation_no .= "000001";
                } 
                else 
                {
                    // $last_quotation_no = $lead_data[0]->quotation_no;
                    // $new_quotation_no .= sprintf("%06d", (int)substr($last_quotation_no, 7) + 1);

                    $last_quotation_no = [];

                    foreach($lead_data as $od)
                    {
                        $last_quotation_no[] = explode("-", $od->quotation_no)[2];
                    }

                    $new_quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
                }
            }
            else
            {
                $quotation_no = $leadDetails->quotation_no;
                $quot_arr = explode("-", $quotation_no);
                $quot_arr[0] = $comp_short_name . "Q";
                $new_quotation_no = implode("-", $quot_arr);
            }

            // quotation no end

            $leadDetails->company_id = $request->company_id;
            $leadDetails->quotation_no = $new_quotation_no;
            $leadDetails->save();

            // lead customer details end

            $total_amount = 0;

            // lead service start

            LeadServices::where('lead_id', $leadId)->delete();

            $service_id = $request->service_id;
            $service_name = $request->service_name;
            $service_desc = $request->service_desc;
            $service_qty = $request->service_qty;
            $service_total_session = $request->service_total_session;

            for($i=0; $i<count($service_id); $i++)
            {
                $db_service = Services::find($service_id[$i]);

                if($db_service)
                {
                    $service_total_amount = $db_service->price * $service_qty[$i];                    
                    $gross_amount = $service_total_amount;

                    $service = new LeadServices();

                    $service->lead_id = $leadId;
                    $service->service_id = $service_id[$i];
                    $service->product_code = $db_service->product_code;
                    $service->name = $service_name[$i];
                    $service->description = $service_desc[$i];
                    $service->unit_price = $db_service->price;
                    $service->quantity = $service_qty[$i];
                    $service->gross_amount = $gross_amount;
                    $service->service_type = "service";
                    $service->total_session = $service_total_session[$i];

                    $service->save();

                    $total_amount += $gross_amount;
                }
            }

            // lead service end

            $leadDetails->amount = $total_amount;
            $leadDetails->grand_total = $total_amount;
            $leadDetails->save();


            // quotation start

            $get_quotation = Quotation::where('lead_id', $leadId)->first();

            if(!$get_quotation)
            {
                return response()->json(['status' => 'failed', 'message' => "Quotation not found"]);
            }

            $quotation_id = $get_quotation->id;

            $get_lead = Lead::find($leadId);

            $get_quotation->company_id = $get_lead->company_id;  
            $get_quotation->amount = $get_lead->amount;           
            $get_quotation->grand_total = $get_lead->grand_total; 
            $get_quotation->quotation_no = $new_quotation_no;      

            $get_quotation->save();

            QuotationServiceDetail::where('quotation_id', $quotation_id)->delete();

            $get_lead_service = LeadServices::where('lead_id', $leadId)->get();

            foreach($get_lead_service as $item)
            {
                $quotation_service = new QuotationServiceDetail();

                $quotation_service->quotation_id = $quotation_id;
                $quotation_service->service_id = $item->service_id;
                $quotation_service->product_code = $item->product_code;
                $quotation_service->name = $item->name;
                $quotation_service->description = $item->description;
                $quotation_service->unit_price = $item->unit_price;
                $quotation_service->quantity = $item->quantity;
                $quotation_service->discount = $item->discount;
                $quotation_service->gross_amount = $item->gross_amount;
                $quotation_service->service_type = $item->service_type;
                $quotation_service->total_session = $item->total_session;

                $quotation_service->save();
            }
        
            // quotation end

            return response()->json(['status' => 'success', 'message' => 'Data saved Successfully']);
        }
    }

    public function draft_save_step_3(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time'
            ]           
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $leadId = $request->lead_id;

            // lead customer details start

            $leadDetails = Lead::where('id', $leadId)->first();

            if(!$leadDetails)
            {
                return response()->json(['status' => 'failed', 'message' => "Lead not found"]);
            }

            if($request->filled('date_of_cleaning'))
            {
                $leadDetails->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
            }
            else
            {
                $leadDetails->schedule_date = null;
            }
            
            $leadDetails->time_of_cleaning = $request->time_of_cleaning;

            $leadDetails->save();

            // lead customer details end

            // lead price info start

            // LeadPriceInfo::where('lead_id', $leadId)->delete();

            // $leadPriceInfo  = new LeadPriceInfo([
            //     'lead_id' => $leadDetails->id,
            //     'date_of_cleaning' => $request->date_of_cleaning,
            //     'time_of_cleaning' => $request->time_of_cleaning,
            // ]);
            // $leadPriceInfo->save();

            // lead price info end

            // quotation start

            $get_quotation = Quotation::where('lead_id', $leadId)->first();

            if(!$get_quotation)
            {
                return response()->json(['status' => 'failed', 'message' => "Quotation not found"]);
            }

            $get_lead = Lead::find($leadId);

            $get_quotation->schedule_date = $get_lead->schedule_date;
            $get_quotation->time_of_cleaning = $get_lead->time_of_cleaning;

            $get_quotation->save();

            // quotation end

            return response()->json(['status' => 'success', 'message' => 'Data saved Successfully']);
        }
    }

    public function draft_save_step_4(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
            ]           
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $leadId = $request->lead_id;

            // lead customer details start

            $leadDetails = Lead::where('id', $leadId)->first();

            if(!$leadDetails)
            {
                return response()->json(['status' => 'failed', 'message' => "Lead not found"]);
            }

            $leadDetails->service_address = $request->input('service-address-radio');
            $leadDetails->billing_address = $request->input('billing-address-radio');

            $leadDetails->save();

            // lead customer details end

            // quotation start

            $get_quotation = Quotation::where('lead_id', $leadId)->first();

            if(!$get_quotation)
            {
                return response()->json(['status' => 'failed', 'message' => "Quotation not found"]);
            }

            $get_lead = Lead::find($leadId);

            $get_quotation->service_address = $get_lead->service_address;
            $get_quotation->billing_address = $get_lead->billing_address;

            $get_quotation->save();

            // quotation end

            return response()->json(['status' => 'success', 'message' => 'Data saved Successfully']);
        }
    }

    public function draft_lead_store(Request $request)
    {
        $company = Company::find($request->company_id);
        $comp_short_name = $company->short_name ?? '';

        $leadId = $request->lead_id;

        // lead customer details start

        $leadDetails = Lead::where('id', $leadId)->first();

        // quotation no start

        if($leadDetails->company_id != $request->company_id)
        {    
            $new_quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";

            $lead_data = Quotation::where('company_id', $request->company_id)->orderBy('created_at', 'desc')->get();

            if ($lead_data->isEmpty()) 
            {
                $new_quotation_no .= "000001";
            } 
            else 
            {
                // $last_quotation_no = $lead_data[0]->quotation_no;
                // $new_quotation_no .= sprintf("%06d", (int)substr($last_quotation_no, 7) + 1);

                $last_quotation_no = [];

                foreach($lead_data as $od)
                {
                    $last_quotation_no[] = explode("-", $od->quotation_no)[2];
                }

                $new_quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
            }
        }
        else
        {
            $quotation_no = $leadDetails->quotation_no;
            $quot_arr = explode("-", $quotation_no);
            $quot_arr[0] = $comp_short_name . "Q";
            $new_quotation_no = implode("-", $quot_arr);
        }

        // quotation no end

        $leadDetails->customer_id = $request->customer_id;
        $leadDetails->company_id = $request->company_id;
        $leadDetails->service_address = $request->input('service-address-radio');
        $leadDetails->billing_address = $request->input('billing-address-radio');

        if($request->filled('date_of_cleaning'))
        {
            $leadDetails->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
        }
        else
        {
            $leadDetails->schedule_date = null;
        }
        
        $leadDetails->time_of_cleaning = $request->time_of_cleaning;
        $leadDetails->status = 1;
        $leadDetails->quotation_no = $new_quotation_no;
        $leadDetails->remarks = $request->lead_remarks;

        $leadDetails->save();

        // lead customer details end

        $total_amount = 0;

        // lead service start

        LeadServices::where('lead_id', $leadId)->delete();

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

                $service = new LeadServices();

                $service->lead_id = $leadId;
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

                $service = new LeadServices();

                $service->lead_id = $leadId;
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

        // lead service end

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

        $leadDetails->discount_type = $discount_Type;
        $leadDetails->discount = $discount;
        $leadDetails->tax = $tax_amt;
        $leadDetails->tax_percent = $tax;
        $leadDetails->tax_type = $tax_type;
        $leadDetails->amount = $total_amount;
        $leadDetails->grand_total = $grand_total;
        $leadDetails->save();

        // lead price info start

        // LeadPriceInfo::where('lead_id', $leadId)->delete();

        // $leadPriceInfo  = new LeadPriceInfo([
        //     'lead_id' => $leadId,
        //     'deposite_type' => $request->deposite_type,
        //     'date_of_cleaning' => $request->date_of_cleaning,
        //     'time_of_cleaning' => $request->time_of_cleaning,
        // ]);
        // $leadPriceInfo->save();

        // lead price info end

        // quotation start

        $get_quotation = Quotation::where('lead_id', $leadId)->first();

        if($get_quotation)
        {
            $quotation_id = $get_quotation->id;

            $get_lead = Lead::find($leadId);

            $get_quotation->lead_id = $leadId;
            $get_quotation->customer_id = $get_lead->customer_id;
            $get_quotation->company_id = $get_lead->company_id;
            $get_quotation->service_address = $get_lead->service_address;
            $get_quotation->billing_address = $get_lead->billing_address;
            $get_quotation->amount = $get_lead->amount;
            $get_quotation->discount_type = $get_lead->discount_type;
            $get_quotation->discount = $get_lead->discount;
            $get_quotation->tax = $get_lead->tax;
            $get_quotation->tax_type = $get_lead->tax_type;
            $get_quotation->tax_percent = $get_lead->tax_percent;
            $get_quotation->grand_total = $get_lead->grand_total;
            $get_quotation->schedule_date = $get_lead->schedule_date;
            $get_quotation->time_of_cleaning = $get_lead->time_of_cleaning;
            $get_quotation->quotation_no = $get_lead->quotation_no;
            $get_quotation->status = $get_lead->status;
            $get_quotation->remarks = $get_lead->remarks;

            $get_quotation->save();

            QuotationServiceDetail::where('quotation_id', $quotation_id)->delete();

            $get_lead_service = LeadServices::where('lead_id', $leadId)->get();

            foreach($get_lead_service as $item)
            {
                $quotation_service = new QuotationServiceDetail();

                $quotation_service->quotation_id = $quotation_id;
                $quotation_service->service_id = $item->service_id;
                $quotation_service->product_code = $item->product_code;
                $quotation_service->name = $item->name;
                $quotation_service->description = $item->description;
                $quotation_service->unit_price = $item->unit_price;
                $quotation_service->quantity = $item->quantity;
                $quotation_service->discount = $item->discount;
                $quotation_service->gross_amount = $item->gross_amount;
                $quotation_service->total_session = $item->total_session;
                $quotation_service->service_type = $item->service_type;

                $quotation_service->save();
            }
        }

        return $leadId;

        // quotation end
    }

    // draft lead save end

    // lead_view_pdf

    public function lead_view_pdf(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'billing-address-radio' => 'required',
                'service-address-radio' => 'required',
                'preview_service_id' => 'required',
                'date_of_cleaning' => 'nullable',
                'time_of_cleaning' => 'nullable'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'billing-address-radio.required' => 'Select Billing address',
                'service-address-radio.required' => 'Select Service address',
                'preview_service_id.required' => 'Select Service',
                'date_of_cleaning.required' => 'Select Date',
                'time_of_cleaning.required' => 'Select Time'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        else
        {
            if(Lead::where('id', $request->lead_id)->whereIn('status', [3, 4])->exists())
            {
                return response()->json(['status'=>'failed', 'message' => 'Lead already Approved']);
            }

            if($request->type == "add")
            {
                // $leadId = $this->temp_lead_store($request);
                $leadId = $this->draft_lead_store($request);

                $db_quotation = Quotation::where('lead_id', $leadId)->first();

                // log data store start

                LogController::store('lead', 'Lead Created and pdf downloaded', $leadId);
                LogController::store('quotation', 'Quotation Created from lead', $db_quotation->id ?? '');

                // log data store end
            }
            else if($request->type == "update")
            {
                $leadId = $this->temp_lead_update($request);

                $db_quotation = Quotation::where('lead_id', $leadId)->first();

                // log data store start

                LogController::store('lead', 'Lead Updated and pdf downloaded', $leadId);
                LogController::store('quotation', 'Quotation Updated from lead', $db_quotation->id ?? '');

                // log data store end
            }

            $data['lead_id'] = $leadId;

            return response()->json(['status' => 'success', 'message' => 'Lead Created successfully!', 'route'=>route('lead.view-download-pdf', $data)]);
        }
    }

    // view_download_pdf

    public function view_download_pdf(Request $request)
    {
        $lead_id = $request->lead_id;

        $quotation = Quotation::where('lead_id', $lead_id)->first();
        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();

        $ServiceAddress = ServiceAddress::find($quotation->service_address);

        if($ServiceAddress)
        {
            $quotation->service_address_details = $ServiceAddress->address;
            $quotation->service_address_unit_number = $ServiceAddress->unit_number;
        }
        else
        {
            $quotation->service_address_details = "";
            $quotation->service_address_unit_number = "";
        }

        // lead payment detail start

        $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $quotation->id)->get();

        $deposit = 0;
        $balance = 0;
        foreach($lead_payment_detail as $item)
        {
            if($item->payment_status == 1)
            {
                $deposit += $item->payment_amount;
            }
        }
        $balance = $quotation->grand_total - $deposit;

        // lead payment detail end

        // calculation start

        $subtotal = 0;

        foreach($quotation_details as $item)
        {
            $subtotal += $item->unit_price;
        }

        $nettotal = $quotation->amount;

        if($quotation->discount_type == "percentage")
        {
            $discount_amt = $nettotal * $quotation->discount/100;
        }
        else
        {
            $discount_amt = $quotation->discount;
        }

        $total = $nettotal - $discount_amt;

        $quotation->subtotal = $subtotal;
        $quotation->nettotal = $nettotal;
        $quotation->discount_amt = $discount_amt;
        $quotation->total = $total;
        $quotation->deposit = $deposit;
        $quotation->balance = $balance;

        // calculation end

        $company = Company::where('id', $quotation->company_id)->first();

        if(!empty($company->company_logo))
        {
            // $company->image_path = 'application/public/company_logos/' . $company->company_logo;
            $company->image_path = "/company_logos/$company->company_logo";
        }
        else
        {
            $company->image_path = "";
        }

        if(!empty($company->qr_code))
        {
            // $company->qr_code_path = "application/public/qr_code/$company->qr_code";
            $company->qr_code_path = "/qr_code/$company->qr_code";
        }
        else
        {
            $company->qr_code_path = "";
        }

        if(!empty($company->stamp))
        {
            $company->stamp_path = "/stamp/$company->stamp";
        }
        else
        {
            $company->stamp_path = "";
        }

        $customer = DB::table('customers')->where('id', $quotation->customer_id)->first();
        $payment_terms = PaymentTerms::find($customer->payment_terms);

        if($payment_terms)
        {
            $customer->payment_terms_value = $payment_terms->payment_terms;
        }
        else
        {
            $customer->payment_terms_value = "";
        }

        $term_condition = TermCondition::where('company_id', $quotation->company_id)->get();

        // invoice footer logo

        $company_invoice_footer_logo = DB::table('company_invoice_footer_logo')
                                            ->where('company_id', $quotation->company_id)
                                            ->get();

        foreach($company_invoice_footer_logo as $item)
        {          
            // $item->invoice_footer_logo_path = 'application/public/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;            
            $item->invoice_footer_logo_path = '/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;
        }                                  

        $company->company_invoice_footer_logo = $company_invoice_footer_logo;

        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        // return view('admin.quotation.download-quotation', $data);

        $pdf = PDF::loadView('admin.leads.invoice', $data);

        return $pdf->stream($quotation->quotation_no.'.pdf');

        // return $pdf->download($quotation->quotation_no.'.pdf');
    }

    // log report

    public function log_report($lead_id)
    {
        $data['lead'] = Lead::find($lead_id);

        $log_details = DB::table('log_details')
                        ->where('module', 'lead')
                        ->where('ref_no', $lead_id)
                        ->paginate(30);

        $data['log_details'] = $log_details;

        return view('admin.leads.log-report', $data);
    }

    // get_past_transaction_details

    public function get_past_transaction_details(Request $request)
    {
        $customer_id = $request->customer_id;

        $sales_order = SalesOrder::where('customer_id', $customer_id)->orderBy('created_at', 'desc')->get();

        $data['sales_order'] = $sales_order;

        $new_data = [];

        foreach ($data['sales_order'] as $key => $item) 
        {
            // customer name

            $customer = Crm::find($item->customer_id);

            if ($customer) {
                $item->customer_type = $customer->customer_type;
                $item->customer_name = $customer->customer_name;
                $item->individual_company_name = $customer->individual_company_name;
            } else {
                $item->customer_type = "";
                $item->customer_name = "";
                $item->individual_company_name = "";
            }

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

            // total amount
            $item->total_amount = $quotation->grand_total ?? 0;

            // service address
            $service_address = ServiceAddress::find($quotation->service_address ?? '');  
            
            // remarks
            $schedule = ScheduleModel::where('sales_order_id', $item->id)->first();

            $new_data[] = [
                $key + 1,
                $item->invoice_no,
                $item->id,
                "$" . number_format($item->total_amount, 2),
                $status,
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

    // draft lead update start

    public function draft_update_step_2(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'service_id' => 'required'
            ],
            [
                'customer_id.required' => 'Select Customer First',
                'service_id.required' => 'Select Service',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $company = Company::find($request->company_id);
            $comp_short_name = $company->short_name ?? '';

            $leadId = $request->lead_id;

            // lead customer details start

            $leadDetails = Lead::where('id', $leadId)->first();

            if(!$leadDetails)
            {
                return response()->json(['status' => 'failed', 'message' => "Lead not found"]);
            }

            // quotation no start

            if($leadDetails->company_id != $request->company_id)
            {    
                $new_quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";

                $lead_data = Quotation::where('company_id', $request->company_id)->orderBy('created_at', 'desc')->get();

                if ($lead_data->isEmpty()) 
                {
                    $new_quotation_no .= "000001";
                } 
                else 
                {
                    // $last_quotation_no = $lead_data[0]->quotation_no;
                    // $new_quotation_no .= sprintf("%06d", (int)substr($last_quotation_no, 7) + 1);

                    $last_quotation_no = [];

                    foreach($lead_data as $od)
                    {
                        $last_quotation_no[] = explode("-", $od->quotation_no)[2];
                    }

                    $new_quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
                }
            }
            else
            {
                $quotation_no = $leadDetails->quotation_no;
                $quot_arr = explode("-", $quotation_no);
                $quot_arr[0] = $comp_short_name . "Q";
                $new_quotation_no = implode("-", $quot_arr);
            }

            // quotation no end


            $leadDetails->company_id = $request->company_id;
            $leadDetails->quotation_no = $new_quotation_no;
            $leadDetails->save();

            // lead customer details end

            $total_amount = 0;

            // lead service start

            LeadServices::where('lead_id', $leadId)->delete();

            $service_id = $request->service_id;
            $service_name = $request->service_name;
            $service_desc = $request->service_desc;
            $service_qty = $request->service_qty;
            $service_total_session = $request->service_total_session;

            for($i=0; $i<count($service_id); $i++)
            {
                $db_service = Services::find($service_id[$i]);

                if($db_service)
                {
                    $service_total_amount = $db_service->price * $service_qty[$i];                    
                    $gross_amount = $service_total_amount;

                    $service = new LeadServices();

                    $service->lead_id = $leadId;
                    $service->service_id = $service_id[$i];
                    $service->product_code = $db_service->product_code;
                    $service->name = $service_name[$i];
                    $service->description = $service_desc[$i];
                    $service->unit_price = $db_service->price;
                    $service->quantity = $service_qty[$i];
                    $service->gross_amount = $gross_amount;
                    $service->service_type = "service";
                    $service->total_session = $service_total_session[$i];

                    $service->save();

                    $total_amount += $gross_amount;
                }
            }

            // lead service end

            $discount_Type = $leadDetails->discount_type;
            $discount = $leadDetails->discount;

            if($discount_Type == "percentage")
            {
                $discount_amount = $total_amount * ($discount/100);
            }
            else
            {          
                $discount_amount = $discount;
            }
        
            $tax = $leadDetails->tax_percent;
            $tax_type = $leadDetails->tax_type;
            
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
            else
            {
                $tax_amt = 0;
                $grand_total = $total;
            }

            $leadDetails->tax = $tax_amt;
            $leadDetails->amount = $total_amount;
            $leadDetails->grand_total = $grand_total;
            $leadDetails->save();

            // quotation start

            $get_quotation = Quotation::where('lead_id', $leadId)->first();

            if(!$get_quotation)
            {
                return response()->json(['status' => 'failed', 'message' => "Quotation not found"]);
            }

            $quotation_id = $get_quotation->id;

            $get_lead = Lead::find($leadId);

            $get_quotation->company_id = $get_lead->company_id;  
            $get_quotation->amount = $get_lead->amount;           
            $get_quotation->grand_total = $get_lead->grand_total; 
            $get_quotation->quotation_no = $new_quotation_no;    
            $get_quotation->tax = $get_lead->tax;  

            $get_quotation->save();

            QuotationServiceDetail::where('quotation_id', $quotation_id)->delete();

            $get_lead_service = LeadServices::where('lead_id', $leadId)->get();

            foreach($get_lead_service as $item)
            {
                $quotation_service = new QuotationServiceDetail();

                $quotation_service->quotation_id = $quotation_id;
                $quotation_service->service_id = $item->service_id;
                $quotation_service->product_code = $item->product_code;
                $quotation_service->name = $item->name;
                $quotation_service->description = $item->description;
                $quotation_service->unit_price = $item->unit_price;
                $quotation_service->quantity = $item->quantity;
                $quotation_service->discount = $item->discount;
                $quotation_service->gross_amount = $item->gross_amount;
                $quotation_service->service_type = $item->service_type;
                $quotation_service->total_session = $item->total_session;

                $quotation_service->save();
            }
        
            // quotation end

            return response()->json(['status' => 'success', 'message' => 'Data saved Successfully']);
        }
    }

    // draft lead update end
}
