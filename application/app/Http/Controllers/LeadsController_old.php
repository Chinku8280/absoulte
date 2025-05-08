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
use Mail;
use DB;
use Str;
use PDF;

class LeadsController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
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
            ->select('lead_customer_details.id', 'lead_customer_details.status as lead_status', 'customers.customer_name as customer_name', 'customers.customer_type as customer_type', 'customers.mobile_number', 'lead_payment_detail.payment_type')
            ->orderBy('lead_customer_details.created_at', 'desc')
            ->get();
        // echo"<pre>"; print_r($leads); exit;
        $pendingLeads = $leads->where('payment_type', 'advance');
        $approvedLeads = $leads->where('payment_type', 'full');
        $pendingCostomer = $leads->where('lead_status', 2);
        $pendingPeyment = $leads->where('lead_status', 3);

        $companyList = Company::get();
        $leadprice = LeadPrice::get();
        $service = Services::get();
        // print_r($request->all());exit;

        return view('admin.leads.index', compact('heading_name', 'pendingPeyment', 'leads', 'pendingLeads', 'approvedLeads', 'pendingCostomer'));
    }

    public function paymentProcess(Request $request)
    {
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
        // echo"<pre>"; print_r($leadId); exit;
        $lead = Lead::select('lead_customer_details.*', 'lead_payment_detail.*', 'lead_price_info.*')
            ->join('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
            ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
            ->where('lead_customer_details.id', $leadId)
            ->first();
        // echo"<pre>"; print_r($lead); exit;
        $customerId = $lead->customer_id;
        $customer = Crm::join('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
            ->join('service_address', 'customers.id', '=', 'service_address.customer_id')
            ->select('customers.*', 'customers.id as customer_id', 'language_spoken.language_name as language_name', 'service_address.*')
            ->where('customers.id', $customerId)
            ->first();
        // echo"<pre>"; print_r($customer); exit;
        $addresses = ServiceAddress::where('customer_id', $customerId)->get();
        $billingaddresses = BillingAddress::where('customer_id', $customerId)->get();

        $service = LeadServices::where('lead_id', $leadId)->get();
       // dd($service);    

        $asiaOptions = PaymentMethod::where('payment_method', "Asia Pay")->get();
      //  dd( $asiaOptions);
        $offlineOptions = PaymentMethod::where('payment_method', "Offline")->get();

        return view('admin.leads.payment', compact('lead', 'customer', 'asiaOptions', 'offlineOptions', 'service', 'leadprice', 'dates', 'companyList', 'emailTemplates', 'subtotal', 'discount', 'nettotal'));
    }

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

        $subtotal = 0;
        $nettotal = 0;
        $discount = 0;

        // foreach ($leadprice as $price) {

        //     $subtotal += $price->sub_total;
        //     $nettotal += $price->net_total;
        //     $discount += $price->discount;
        // }

        $emailTemplates = EmailTemplate::get();
        $customersResidential = Crm::where('customer_type', 'residential_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();
        $customersCommercial = Crm::where('customer_type', 'commercial_customer_type')->limit(3)->orderBy('created_at', 'desc')->get();
        // echo"<pre>"; print_r($emailTemplates); exit;
        return view('admin.leads.create', compact('customersResidential', 'customersCommercial', 'companyList', 'service', 'dates', 'leadprice', 'emailTemplates', 'subtotal', 'nettotal', 'discount'));
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

        //    if(!empty($search)){
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
        //    }else{
        //         if ($request->type == '1') {
        //             $customers = Crm::where('customers.customer_type', 'residential_customer_type')
        //                 ->get();
        //         } else {
        //             $customers = Crm::where('customers.customer_type', 'commercial_customer_type')
        //                 ->get();
        //         }
        //    }

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
        $services = Services::where('service_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('price', 'like', '%' . $searchTerm . '%')
            ->where('company', $selectedCompanyId)
            ->get();
        // dd($selectedCompanyId);
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
        // dd($request->service_ids);

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
        // dd($serviceDetail);


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

        // $quotationDetails = new Quotation([
        //     'customer_id' => $request->input('customer_id'),
        //     'quotation_no' => rand(10000, 200000),
        // ]);

        // $quotationDetails->save();


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
            $service->quantity = $serviceQuantity[$i] ?? 0;
            $service->discount = $serviceDiscount[$i] ?? 0;
            // $grossAmount = ($service->unit_price * $service->quantity) * (1 - (floatval($service->discount) / 100));

            // $service->gross_amount = $grossAmount;
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
            // $sub_total = ($price->unit_price * $price->qty);
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
        return response()->json(['success' => 'Lead Created successfully!']);
    }

    public function leadUpdateStore(Request $request)
    {

        // dd($request->customer_id);

        $leadDetails = Lead::where('id', $request->lead_id)->first();
        $leadDetails->customer_id = $request->input('customer_id');
        $leadDetails->service_address = $request->input('service_address');
        $leadDetails->billing_address = $request->input('billing_address');
        $leadDetails->schedule_date = $request->input('schedule_date');
        $leadDetails->amount = $request->input('total_amount_val');
        $leadDetails->tax = $request->input('tax');
        $leadDetails->grand_total = $request->input('grand_total_amount');
        $leadDetails->tax_percent = $request->input('tax_percent');
        $leadDetails->save();


        // $serviceIds = explode(',', $request->input('service_ids'));
        // $serviceDescription = explode(',', $request->input('service_descriptions'));
        // $serviceUnitPrice = explode(',', $request->input('unit_price'));
        // $serviceQuantity = explode(',', $request->input('quantities'));
        // $serviceDiscount = explode(',', $request->input('discounts'));
        // $serviceNames = explode(',', $request->input('service_names'));
        // // Assuming all arrays have the same length
        // $count = count($serviceIds);
        // $leadId = $leadDetails->id;

        // for ($i = 0; $i < $count; $i++) {
        //     // $service = LeadServices::where('lead_id',$request->lead_id)->first();
        //     $service = new LeadServices();
        //     $service->service_id = $serviceIds[$i];
        //     $service->name = $serviceNames[$i];
        //     $service->description = $serviceDescription[$i] ?? " ";
        //     $service->unit_price = $serviceUnitPrice[$i];
        //     $service->quantity = $serviceQuantity[$i]?? 0;
        //     $service->discount = $serviceDiscount[$i] ?? 0;
        // $grossAmount = ($service->unit_price * $service->quantity) * (1 - (floatval($service->discount) / 100));

        // $service->gross_amount = $grossAmount;
        // $service->save();
        // }

        // $leadPriceInfo  = new LeadPriceInfo([
        //     'lead_id' => $leadCustomerDetail->id,
        //     'deposite_type' => $request->deposite_type,
        //     '' => $request->cleaning_date,
        //     'time_of_cleaning' => $request->cleaning_time,

        // ]);
        // $leadPriceInfo  = LeadPriceInfo::where('lead_id',$request->lead_id)->first();
        // $leadPriceInfo->deposite_type = $request->deposite_type ?? '';
        // $leadPriceInfo->date_of_cleaning = $request->cleaning_date ?? '';
        // $leadPriceInfo->time_of_cleaning = $request->cleaning_time ?? '';
        // $leadPriceInfo->save();


        // $leadPaymentInfo = new LeadPaymentInfo();
        // $leadPaymentInfo->lead_id = $leadCustomerDetail->id;
        // $leadPaymentInfo->payment_type = $request->payment_option;
        // $leadPaymentInfo->total_amount = $request->total_amount;
        // $leadPaymentInfo->advance_amount = $request->advance_amount;

        return response()->json(['success' => 'Lead updated successfully!']);
    }
    public function leadpaymentStore(Request $request)
    {

        //dd($request->customer_id);

        $leadDetails = Lead::where('customer_id', $request->customer_id)->first();
        $leadDetails->service_address = $request->input('service_address');
        $leadDetails->billing_address = $request->input('billing_address');
        $leadDetails->schedule_date = $request->input('schedule_date');
        $leadDetails->amount = $request->input('total_amount_val');
        $leadDetails->tax = $request->input('tax');
        $leadDetails->grand_total = $request->input('grand_total_amount');
        $leadDetails->tax_percent = $request->input('tax_percent');
        $leadDetails->status = 3;
        $leadDetails->save();

        // $uniqueID = 'Lead' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        // $leadCustomerDetail =  new Lead([
        //     'unique_id' => $uniqueID,
        //     'customer_id' => $request->customer_id,
        //     'service_address' => $request->service_id,
        //     'billing_address' => $request->billing_address,
        //     'schedule_date' => $request->schedule_date,
        //     'amount' => $request->total_amount_val,
        //     'tax' => $request->tax,
        //     'grand_total' => $request->grand_total_amount,
        //     'tax_percent' => $request->tax_percent,
        //     'time_of_cleaning' => $request->time_of_cleaning,
        //     'date_of_cleaning' => $request->date_of_cleaning,
        //     'status' => 2,
        // ]);
        // $leadCustomerDetail->save();

        $serviceIds = explode(',', $request->input('service_ids'));
        $serviceDescription = explode(',', $request->input('service_descriptions'));
        $serviceUnitPrice = explode(',', $request->input('unit_price'));
        $serviceQuantity = explode(',', $request->input('quantities'));
        $serviceDiscount = explode(',', $request->input('discounts'));
        $serviceNames = explode(',', $request->input('service_names'));
        // Assuming all arrays have the same length
        $count = count($serviceIds);
        $leadId = $leadDetails->id;

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
            'lead_id' => $leadDetails->id,
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
        $leadPaymentInfo->lead_id = $leadDetails->id;
        $leadPaymentInfo->payment_type = $request->payment_option;
        $leadPaymentInfo->total_amount = $request->total_amount;
        $leadPaymentInfo->advance_amount = $request->advance_amount;

       // dd($request->all());


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
            'lead_id' => $leadDetails->id,
            'quotation_no' => rand(1234, 9999),
        ]);
        $quotation->save();

        // Mail::to($customer->email)->send(new SendLeadEmail($emailTemplate->title, $emailTemplate->subject,$emailTemplate->body,$company->company_name,$leadDetails->id,$attachment));
        // echo"<pre>"; print_r($leadDetails); exit;

        return response()->json(['success' => 'Payment updated successfully!']);
    }

    public function getEmailData(Request $request)
    {

        $customer = Crm::where('id', $request->customer_id)->first();
        $data = EmailTemplate::where('id', $request->template_id)->first();
        $company = Company::where('id', $request->company_id)->first();
        // print_r($data); exit;
        // $pdf = PDF::loadView('pdf', ['company' => $company]);
        // $quotationPdf  = $pdf->output();

        $emailTemplate = '
        <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">To:</label>
                <input type="text" class="form-control" name="example-text-input"
                    id="emailInput" placeholder="Type email" value="' . $customer->email . '">
            </div>
            <div class="mb-3">
                <label class="form-label">CC:</label>
                <input type="text" class="form-control" name="example-text-input"
                    id="emailTitle" value="' . $data->cc . '">
            </div>
            <div class="mb-3">
                <label class="form-label">BCC:</label>
                <input type="text" class="form-control" name="example-text-input"
                    id="emailSubject" value="' . $data->bcc . '">
            </div>
            <div class="mb-3">
                <label class="form-label">Subject:</label>
                <input type="text" class="form-control" name="example-text-input"
                    id="emailSubject" value="' . $data->subject . '">
            </div>
            <div class="mb-3">
                <label class="form-label">Body:</label>
                <textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Content..">' . $data->body . '</textarea>
            </div>
        </div>
        <div class="col-md-12 text-end">
            <button class="btn btn-info" onclick="emailSend(event)">Confirm</button>
        </div>
        ';

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
        $lead = Lead::select('lead_customer_details.id as leads_id', 'lead_customer_details.*', 'lead_payment_detail.*', 'lead_price_info.*')
            ->join('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
            ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
            ->where('lead_customer_details.id', $leadId)
            ->first();

        // dd($lead->payment_type);
        $customerId = $lead->customer_id;
        $customer = Crm::join('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
            ->join('service_address', 'customers.id', '=', 'service_address.customer_id')
            ->select('customers.id as custom_id', 'customers.*', 'language_spoken.language_name as language_name', 'service_address.*')
            ->where('customers.id', $customerId)
            ->first();
        // dd($customer->custom_id);
        $addresses = ServiceAddress::where('customer_id', $customerId)->get();
        $Serviceaddresses = ServiceAddress::where('customer_id', $customerId)->first();
        $priceInfo = LeadPriceInfo::where('lead_id', $leadId)->first();
        $billingaddresses = BillingAddress::where('customer_id', $customerId)->get();
        $leadBillingaddresses = BillingAddress::where('customer_id', $customerId)->first();

        $services = LeadServices::where('lead_id', $leadId)->get();
        return view('admin.leads.edit', compact('lead', 'companyList', 'customer', 'services', 'addresses', 'Serviceaddresses', 'billingaddresses', 'priceInfo', 'leadBillingaddresses'));
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
        $pdf = PDF::loadView('pdf', ['company' => $company, 'termCondition' => $termCondition, 'service' => $service]);
        // $pdfPath = public_path('pdf/quotation.pdf');
        // // print_r(public_path()); exit;
        // $pdf->save($pdfPath);
        return $pdf->download('quotation.pdf');
        // return redirect()->back();
    }

    public function sendEmail(Request $request)
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

        $pdf = PDF::loadView('pdf', ['company' => $company]);
        $attachment = $pdf->output();
        // Mail::send('admin.leads.mail', $data, function($message)use($data, $pdf) {
        //     $message->to($data["email"])
        //             ->subject($data["subject"])
        //             ->attach($pdf->output(),'document.pdf');
        // });

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
            'status' => 2,
        ]);
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

        // Mail::to($customer->email)->send(new SendLeadEmail($emailTemplate->title, $emailTemplate->subject,$emailTemplate->body,$company->company_name,$leadCustomerDetail->id,$attachment));
        // echo"<pre>"; print_r($leadCustomerDetail); exit;
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



    public function getlLeadPreview(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();

        $term_condition = TermCondition::where('company_id', $request->company_id)->get();


        $term = '';
        foreach ($term_condition as $item) {
            $term .= '<li>
            ' . $item->term_condition . '
        </li>';
        }

        $imagePath = 'public/company_logos/' . $company->company_logo;
        $route = route('download.quotation', $company->id);
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
                                <button type="button" class="btn btn-primary" id="print-btn" onclick="printQuotation()">
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
                                    Print Quotation
                                </button>
                                <a class="btn btn-primary" onclick="downloadQuotation()">
                                    Download Quotation
                                </a>
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
                                            <img src="' . $imagePath . '" alt="logo" style="background-color:black; max-width:100px; height: 100px;">
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
                                    <table class="quotation-table table">
                                        <thead>
                                            <tr>
                                                <th>SERVICE</th>
                                                <th>QTY</th>
                                                <th>UNIT PRICE</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="quotation-table-content"> 
                                        
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
                                                        $<div class="subTotal"> </div>
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
                                                        $<div id="discount"></div>
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
                                                        $<div class="grandTotal"></div>
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
                                                <img src="' . $imagePath . '" alt="logo" style="background-color:black; max-width:100px; height: 100px;">
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
           <button type="button" onclick="sendByEmail()" class="btn btn-info w-100 mt-3"
        data-dismiss="modal" style="width: 150px !important;">Send By mail</button>
           <button type="button"  class="btn btn-info w-100 mt-3"
        data-dismiss="modal"  onclick="confirm_btn()" style="width: 150px !important; margin-left:auto;">Confirm</button>
        </div>
        ';
        // }
        // return response()->json($company);
        print_r($output);
        exit;
    }

    public function getlLeadPyamentPreview(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();
        $price_info = LeadPrice::get();

        $subtotal = 0;
        $nettotal = 0;
        $discount = 0;

        // foreach ($price_info as $price) {

        //     $subtotal += $price->sub_total;
        //     $nettotal += $price->net_total;
        //     $discount += $price->discount;
        // }

        $term_condition = TermCondition::where('company_id', $request->company_id)->get();
        // echo"<pre>"; print_r($company); exit;
        // $company = Company::first();

        $tbody = '';
        foreach ($price_info as $lead) {
            $tbody .= '<tr>
            <td>' . $lead->service . '</td>
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

        $imagePath = 'public/company_logos/' . $company->company_logo;

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
                                            <img src="' . $imagePath . '" alt="logo" style="background-color:black; max-width:100px; height: 100px;">
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
                                        <h3>Tax Invoice</h3>
                                    </div>
                                </div>
                                <div class="row mb-3">
    
                                    <div class="col-1 col-sm-1 text-center">
                                        <h4 class="mb-3 fs-12 lh-13"><b>To:</b></h4>
                                    </div>
                                    <div class="col-4 col-sm-4 ps-0">
                                        <div class="fs-12 lh-13">Mr Soo Chee Yei</div>
                                        <div class="fs-12 lh-13">6 Holland Close #14-34</div>
                                        <div class="fs-12 lh-13">Singapore 271006</div>
                                    </div>
        
                                    <div class="col-7 col-sm-7">
                                    <div class="row">

                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Issued By:</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div class="fs-12 lh-13">Leau</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Invoice No:</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div class="fs-12 lh-13">ACI-22-001840</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Issued Date:</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div class="fs-12 lh-13">24-Aug-2022</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Service Sheet No</b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div class="fs-12 lh-13">AC-SC-0120455</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Service Date:
                                            </b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div class="fs-12 lh-13">24-Aug-2022</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-8 text-end">
                                            <div class="fs-12 lh-13"><b>Team:
                                            </b></div>
                                        </div>
                                        <div class="col-4 col-md-4 text-end">
                                            <div class="fs-12 lh-13">AC 2, Lin Lin</div>
                                        </div>
                                    </div>

                                </div>
    
                                </div>
    
                                <div class="table-responsive-sm">
                                    <table class="invoice-table table">
                                        <thead>
                                            <tr>
                                                <th>SERVICE</th>
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
                                                        $<div>' . $subtotal . '</div>
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
                                                        $<div>' . $discount . '</div>
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
                                                        $<div>' . $nettotal . '</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 col-md-8 text-end">
                                                
                                            </div>
                                            <div class="col-4 col-md-4 text-end">
                                                <div>
                                                    <div class="d-flex justify-content-between fs-10 lh-13">
                                                        
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
                                <br><br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3><b>This is a computer generated invoice therefore no signature required.</b>
                                        </h3>
                                        <div class="d-flex footer-logo justify-content-between">
                                            <div class="img">
                                                <img src="' . $imagePath . '" alt="logo" style="background-color:black; max-width:100px; height: 100px;">
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
           <button type="button" onclick="sendInvoiceEmail()" class="btn btn-info w-100 mt-3"
        data-dismiss="modal" style="width: 150px !important; margin-left: 88%;">Send By mail</button>
           <button type="button"  class="btn btn-info w-100 mt-3"
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
}
