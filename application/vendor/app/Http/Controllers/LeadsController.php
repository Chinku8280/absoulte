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
use App\Models\Customer;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\UploadTrait;
use App\Mail\SendLeadEmail;
use Illuminate\Support\Facades\Mail;
use DB;
use Str;

class LeadsController extends Controller
{
    use UploadTrait;

    public function index()
    {
        $heading_name  = 'Leads';
        // $leads = DB::table('lead_customer_details')
        // ->select('lead_customer_details.*', 'customers.customer_name', 'lead_payment_detail.payment_type')
        // ->join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
        // ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
        // ->get();

        $leads = Lead::join('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
            ->join('lead_payment_detail', function ($join) {
                $join->on('lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
                    ->whereRaw('lead_payment_detail.id IN (
                 SELECT MAX(id) FROM lead_payment_detail GROUP BY lead_id
             )');
            })
            ->select('lead_customer_details.id', 'customers.customer_name as customer_name', 'customers.customer_type as customer_type', 'lead_payment_detail.payment_type')
            ->get();


        $pendingLeads = $leads->where('payment_type', 'advance');
        $approvedLeads = $leads->where('payment_type', 'full');

        // dd($leads);


        return view('admin.leads.index', compact('heading_name', 'leads', 'pendingLeads', 'approvedLeads'));
    }

    public function create()
    {

        $companyList = Company::get();
        // echo"<pre>"; print_r($companyList); exit;
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
        // echo"<pre>"; print_r($leadprice['sub_total']); exit;
        // $data = '';
        // foreach($leadprice as $item){
        //     $data = LeadPrice::where('id',$item->id)->first();
        // }
        return view('admin.leads.create', compact('companyList', 'service', 'dates', 'leadprice'));
    }
    public function createCustomer()
    {
        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        return view('admin.leads.customerCreate', compact('territory_list', 'spoken_language', 'payment_terms'));
    }
    public function search(Request $request)
    {
        $search = $request->search;

        if ($request->type == '1') {
            $customers = Crm::where('customers.customer_type', 'residential_customer_type')
                ->where(function ($query) use ($search) {
                    $query->where('customers.customer_name', 'like', '%' . $search . '%')
                        ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
                })
                ->get();
        } else {
            $customers = Crm::where('customers.customer_type', 'commercial_customer_type')
                ->where(function ($query) use ($search) {
                    $query->where('customers.customer_name', 'like', '%' . $search . '%')
                        ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
                })
                ->get();
        }

        return response()->json($customers);
    }

    public function getCustomerDetails(Request $request)
    {
        $customerId = $request->input('id');
        $customer = Crm::join('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
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
        // dd($selectedCompanyId);
        $searchTerm = $request->input('search');
        $selectedCompanyId = $request->input('company_id');
        $services = Services::where('company', $selectedCompanyId)
            ->where('service_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('price', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($services);


        return response()->json($services);
    }
    public function getServiceAddress(Request $request)
    {
        $customerId = $request->input('customer_id');
        // dd($customerId);
        $serviceAddress = ServiceAddress::where('customer_id', $customerId)->get();
        return response()->json($serviceAddress);
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
        $validator = Validator::make($request->all(), [
            'person_incharge_name' => 'required|array',
            'person_incharge_name.*' => 'required|string|max:255',
            'contact_no' => 'required|array',
            'contact_no.*' => 'required|string|max:15',
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

        ]);
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

                ];
            }
            ServiceAddress::insert($serviceAddresses);
        }
        return response()->json(['success' => 'Service Address added successfully!', 'serviceAddresses' => $serviceAddresses]);
    }
    public function storeBillingAddress(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'b_postal_code' => 'required|array',
            'b_postal_code.*' => 'required|string|max:10',

            'b_address' => 'required|array',
            'b_address.*' => 'required|string|max:255',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $postalCodes = $request->input('b_postal_code');
        $addresses = $request->input('b_address');
        $unitNumbers = $request->input('b_unit_number');
        if (!empty($postalCodes) && !empty($addresses) && !empty($unitNumbers)) {
            $billingAddresses = [];
            foreach ($postalCodes as $key => $postalCode) {
                $billingAddresses[] = [
                    'customer_id' => $request->customer_id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[$key],
                    'unit_number' => $unitNumbers[$key],

                ];
            }
            BillingAddress::insert($billingAddresses);
        }
        return response()->json(['success' => 'Billing Address added successfully!', 'billingAddresses' => $billingAddresses]);
    }
    public function leadStore(Request $request)
    {

        // dd($request->All());

        $validator = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
            ],
            [
                'customer_id.required' => 'Select Customer First'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        // dd($request->input( 'total_amount' ));
        $leadCustomerDetail =  new Lead([
            'unique_id' => $uniqueID,
            'customer_id' => $request->input('customer_id'),
            'service_address' => $request->input('service_address'),
            'billing_address' => $request->input('billing_address'),
            'schedule_date' => $request->input('schedule_date'),
            'amount' => $request->input('total_amount_val'),
            'tax' => $request->input('tax'),
            'grand_total' => $request->input('grand_total_amount'),
            'tax_percent' => $request->input('tax_percent'),
            'time_of_cleaning' => $request->input('time_of_cleaning'),
            'date_of_cleaning' => $request->input('date_of_cleaning'),
        ]);
        $leadCustomerDetail->save();

        $quotationDetails = new Quotation([
            'customer_id' => $request->input('customer_id'),
            'quotation_no' => rand(10000, 200000),
        ]);

        $quotationDetails->save();


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
            $service->service_id = $serviceIds[$i];
            $service->name = $serviceNames[$i];
            $service->description = $serviceDescription[$i] ?? " ";
            $service->unit_price = $serviceUnitPrice[$i];
            $service->quantity = $serviceQuantity[$i];
            $service->discount = $serviceDiscount[$i] ?? 0;
            $grossAmount = ($service->unit_price * $service->quantity) * (1 - (floatval($service->discount) / 100));

            $service->gross_amount = $grossAmount;
            $service->save();
        }

        $leadPriceInfo  = new LeadPriceInfo([
            'lead_id' => $leadCustomerDetail->id,
            'deposite_type' => $request->deposite_type,
            'date_of_cleaning' => $request->cleaning_date,
            'time_of_cleaning' => $request->cleaning_time,

        ]);
        $leadPriceInfo->save();
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
        return response()->json(['success' => 'Lead Created successfully!']);
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
    public function destroy($id)
    {
        $lead = Lead::find($id);

        if ($lead) {
            $lead->delete();
            return redirect()->back()->with('success', 'Lead deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Lead not found.');
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
    public function edit(request $request)
    {

        $leadId = $request->input('lead_id');

        $lead = Lead::select('lead_customer_details.*', 'lead_payment_detail.*', 'lead_price_info.*')
            ->join('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
            ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
            ->where('lead_customer_details.id', $leadId)
            ->first();
        // dd($lead->customer_id);
        // dd($lead->payment_type);
        $customerId = $lead->customer_id;
        $customer = Crm::join('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
            ->join('service_address', 'customers.id', '=', 'service_address.customer_id')
            ->select('customers.*', 'language_spoken.language_name as language_name', 'service_address.*')
            ->where('customers.id', $customerId)
            ->first();
        $addresses = ServiceAddress::where('customer_id', $customerId)->get();
        $billingaddresses = BillingAddress::where('customer_id', $customerId)->get();

        $services = LeadServices::where('lead_id', $leadId)->get();
        return view('admin.leads.edit', compact('lead', 'customer', 'services', 'addresses', 'billingaddresses'));
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

    public function leadPrice(Request $request)
    {
        $price = new LeadPrice();
        $price->products = $request->products;
        $price->unit_price = $request->unit_price;
        $price->qty = $request->qty;
        $price->sub_total = $request->sub_total;
        $price->discount = $request->discount;
        $price->net_total = $request->net_total;
        $price->save();

        return redirect()->back();
    }

    public function sendEmail(Request $request)
    {

        $customer = DB::table('customer')->where('created_by', $request->company_id)->first();
        $data = EmailTemplate::where('company_id', $request->company_id)->first();

        Mail::to($customer->email)->send(new SendLeadEmail($data->title, $data->subject, $data->body));

        return redirect()->back();
    }

    public function getlLeadPreview(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();
        $price_info = LeadPrice::get();
        $term_condition = TermCondition::where('company_id', $request->company_id)->get();
        // echo"<pre>"; print_r($company); exit;
        // $company = Company::first();

        $tbody = '';
        foreach ($price_info as $lead) {
            $tbody .= '<tr>
            <td>' . $lead->products . '</td>
            <td>
                <div class="mb-3">
                    ' . $lead->description . '
                </div>
            </td>
            <td>' . $lead->qty . '</td>
            <td>
                <div class="d-flex justify-content-between">

                    $ 

                    <div>' . $lead->unit_price . '</div>

                </div>
            </td>
            <td>
                <div class="d-flex justify-content-between">

                     $ 

                    <div>' . $lead->net_total . '</div>

                </div>
            </td>
        </tr>';
        }

        $term = '';
        foreach ($term_condition as $item) {
            $term .= '<li>
            ' . $item->term_condition . '
        </li>';
        }

        $public_path = public_path('company_logos');
        $imagePath = $public_path . '/' . $company->company_logo;

        //   print_r($public_path); exit;
        $output = '';
        // foreach($company as $item){
        $output .= '
        <div class="row">
           <div class="col-md-12">
              <div class="card card-link card-link-pop">
                 <div class="card-status-start bg-primary"></div>
                 <div class="card-stamp">
                    <div class="card-stamp-icon bg-white text-primary">
                       <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin"
                          width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                          fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                          <path
                             d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                          </path>
                       </svg>
                    </div>
                 </div>
                 <div class="page-header d-print-none">
                    <div class="container-xl">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto ms-auto d-print-none">
                                <button type="button" class="btn btn-primary" id="print-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                            d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
                                        </path>
                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                        <path
                                            d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
                                        </path>
                                    </svg>
                                    Print Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-body">
                    <div class="container-xl">
                        <div class="card card-lg" id="invoice-card">
    
                            <div class="card-body">
                                <div class="d-flex mb-2">
                                    <div class="logo p-0 text-center">
                                        <img src="dist/img/invoice-logo/logo-1.png" alt="" class="img-fluid"
                                            style="height: 100px;">
                                        <div class="img">
                                            <img src="' . $public_path . '/' . $company->company_logo . '" alt="logo">
                                            <img src="' . $imagePath . '" alt="logo">
                                        </div>
                                    </div>
                                    <div class="company-dece pe-0 ps-3">
                                        <h1 class="title" style="font-size: 26px;"><b>' . $company->company_name . '</b></h1>
                                        <p class="m-0 fs-12 lh-13">' . $company->company_address . '</p>
                                        <p class="m-0 fs-12 lh-13">Tel: ' . $company->contact_number . ' Fax: ' . $company->contact_number . ' Phone: ' . $company->contact_number . '</p>
                                        <p class="m-0 fs-12 lh-13">Website: ' . $company->website . '</p>
                                        <p class="m-0 fs-12 lh-13">Co. Reg No: 201524788N
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GST Reg
                                            No.
                                            201524788N</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8"></div>
                                    <div class="col-4 text-center">
                                        <h3>QUOTATION</h3>
                                    </div>
                                </div>
                                <div class="row mb-3">
    
                                    <div class="col-1 col-sm-1 text-center">
                                        <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                                    </div>
                                    <div class="col-4 col-sm-4 ps-0">
                                        <div class="fs-12 lh-13">Green Power Asia Pte Ltd</div>
                                        <div class="fs-12 lh-13">Ms Zaina</div>
                                        <div class="fs-12 lh-13">25 International Business Park #05-107</div>
                                        <div class="fs-12 lh-13">German Centre Singapore 609916</div>
                                        <div class="fs-12 lh-13">+6564820902</div>
                                        <div class="fs-12 lh-13">rozana.rashid@greenpowerasia.com</div>
                                    </div>
    
                                    <div class="col-7 col-sm-7">
                                        <div class="row">
    
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-12 lh-13"><b>Quotation No:</b></div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div class="fs-12 lh-13">ACQ-23-000115</div>
                                            </div>
                                        </div>
    
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div class="fs-12 lh-13">08 Feb 2023</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-12 lh-13"><b>Expiry Date</b></div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div class="fs-12 lh-13">22 Feb 2023</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-12 lh-13"><b>Issue By:
                                                    </b></div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div class="fs-12 lh-13">Leau
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                </div>
    
                                <div class="table-responsive-sm">
                                    <table class="invoice-table table">
                                        <thead>
                                            <tr>
                                                <th>PRODUCT</th>
                                                <th>DESCRIPTION</th>
                                                <th>QTY</th>
                                                <th>UNIT PRICE</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                        ' . $tbody . '
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-7 col-sm-7">
                                        <div class="row mb-2">
                                            <div class="col-3 col-md-3 text-start">
                                                <div class="fs-12 lh-13"><b>Remarks:</b></div>
                                            </div>
                                            <div class="col-8 col-md-8 text-start">
                                                <div></div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3 col-md-3 text-start">
                                                <div class="fs-10 lh-13"><b>Payment Term:</b></div>
                                            </div>
                                            <div class="col-9 col-md-9 text-start">
                                                <div class="fs-10 lh-13">C.O.D</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3 col-md-3 text-start">
                                                <div class="fs-10 lh-13"><b>Bank Detail:</b></div>
                                            </div>
                                            <div class="col-9 col-md-9 text-start">
                                                <div class="fs-10 lh-13">DBS Current: 017-904550-9</div>
                                                <div class="fs-10 lh-13">Bank Code: 7171 / Branch Code: 017</div>
                                                <div class="fs-10 lh-13">OCBC Current: 686-026980-001</div>
                                                <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 686</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3 col-md-3 text-start">
                                                <div class="fs-10 lh-13"><b>Commence Date:</b></div>
                                            </div>
                                            <div class="col-9 col-md-9 text-start">
                                                <div class="fs-10 lh-13">OCBC Current: 695-163-311-001
                                                </div>
                                                <div class="fs-10 lh-13">Bank Code: 7339 / Branch Code: 695</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3 col-md-3 text-start">
                                                <div class="fs-10 lh-13"><b>Payment Method:</b></div>
                                            </div>
                                            <div class="col-9 col-md-9 text-start">
                                                <div class="fs-10 lh-13" style="text-decoration: underline;">"Bank Code:
                                                    7339 / Branch Code: 686"
                                                </div>
                                                <div class="fs-10 lh-13">All cheques are to be crossed and made payable to
                                                </div>
                                                <div class="fs-10 lh-13" style="text-decoration: underline;"><b>"@bsolute
                                                        Aircon Pte Ltd"</b></div>
                                            </div>
                                        </div>
    
                                    </div>
                                    <div class="col-2 col-sm-2">
                                   
                                    </div>
                                    <div class="col-3 col-sm-3">
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-10 lh-13">Sub Total:</div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div>
                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                        $<div>15,013.20</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-10 lh-13">
                                                    Discount:
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div>
                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                        $<div>0.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-10 lh-13">Total:</div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div>
                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                        $<div>15,013.20</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-10 lh-13">GST @ 8%:
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div>
                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                        $<div>1201.08</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                <div class="fs-10 lh-13">Grand Total:</div>
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div>
                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                        $<div>16,214.28</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  terms-row">
                                    <h3><b>Terms and Conditions</b></h3>
                                    ' . $term . '
                                </div>
                                <div class="row">
                                  
                                    <div class="col-5">
                                        <div class="signature-container">
    
                                            <canvas id="signatureCanvas"></canvas>
                                            <div class="signature-line"></div>
                                            <div class="signature-details">
                                                <span class="name fs-12 lh-13"> Customers Acknowledgement </span>
                                                <span class="time fs-10 lh-13">Designation:</span>
                                                <span class="time fs-10 lh-13">Company Stamp:</span>
                                            </div>
    
                                        </div>
                                    </div>
                                    <div class="col-2">
    
                                    </div>
                                    <div class="col-5">
                                        <div class="signature-container">
    
                                            <canvas id="signatureCanvas"></canvas>
                                            <div class="signature-line"></div>
                                            <div class="signature-details">
                                                <span class="name fs-12 lh-13">Singapore Carpet Cleaning Pte Ltd</span>
                                                <span class="time fs-10 lh-13">Designation : Sales Coordinator</span>
                                            </div>
    
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3><b>This is a computer generated invoice therefore no signature required.</b>
                                        </h3>
                                        <div class="d-flex footer-logo justify-content-between">
                                            <div class="img">
                                                <img src="' . $company->company_logo . '" alt="logo">
                                            </div>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
           </div>
           <button type="button" class="btn btn-info w-100 mt-3"
        data-dismiss="modal"  onclick="confirm_btn()" style="width: 150px !important; margin-left:auto;">Confirm</button>
        </div>
        ';
        // }
        // return response()->json($company);
        print_r($output);
        exit;
    }

    public function deletePriceInfo($id)
    {
        // print_r($id); exit;
        LeadPrice::where('id', $id)->delete();

        return redirect()->back();
    }
}
