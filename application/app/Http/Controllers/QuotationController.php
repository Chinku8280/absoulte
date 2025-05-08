<?php

namespace App\Http\Controllers;

use App\Models\Crm;
use App\Models\Company;
use App\Models\Quotation;
use App\Models\LeadServices;
use App\Models\EmailTemplate;
use App\Models\SalesOrder;
use App\Models\Services;
use App\Mail\SendQuotationEmail;
use App\Models\BillingAddress;
use App\Models\JobDetail;
use App\Models\Lead;
use App\Models\LeadOfflinePaymentDetail;
use App\Models\LeadPaymentInfo;
use App\Models\LeadPrice;
use App\Models\LeadSchedule;
use App\Models\PaymentHistory;
use App\Models\PaymentHistoryDetail;
use App\Models\PaymentTerms;
use App\Models\QuotationServiceDetail;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use App\Models\ServiceAddress;
use App\Models\Tax;
use App\Models\TermCondition;
use App\Models\ZoneSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class QuotationController extends Controller
{
    public function index()
    {
        $companyList = Company::get();

        $quotation = Quotation::select('quotations.id', 'quotations.customer_id', 'quotations.quotation_no', 'quotations.created_at', 'quotations.invoice_no', 'quotations.schedule_date', 'customers.id as custm_id', 'customers.customer_name', 'customers.individual_company_name', 'customers.email', 'customers.mobile_number', 'customers.customer_type', 'quotations.status as quotation_status', 'quotations.payment_status as payment_status', 'quotations.payment_advice as payment_advice', 'quotations.created_by_name', 'quotations.grand_total', 'company.company_name')
                                ->leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')
                                ->leftjoin('company', 'quotations.company_id', '=', 'company.id')
                                ->where('quotations.status', '!=', 0)
                                ->where('quotations.quotation_type', '=', 1)
                                ->orderBy('quotations.created_at', 'desc')
                                ->get();
        $data = null;
        foreach ($quotation as $key => $value) {
            $data = Quotation::leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')->where('quotations.id', $value->id)->first();
            // echo"<pre>"; print_r($data); exit;

            $value->schedule_date = $value->schedule_date ? date('d-m-Y', strtotime($value->schedule_date)) : '';
        }
        $emailTemplates = EmailTemplate::get();

        return view('admin.quotation.index', compact('companyList', 'quotation', 'data', 'emailTemplates'));
    }

    public function create()
    {
        $companyList = Company::get();
        $service = Services::get();

        $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();
        $service_date = array();
        foreach ($get_current_month_service as $item) {
            $service_date[] = array(
                'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
                'service' => $item->service_name
            );
        }
        $dates = json_encode((object)$service_date);

        //    $customersResidential = Crm::select('customers.*', 'zs.zone_color')
        //     ->selectRaw('LEFT(sa.postal_code, 2) as shortPostalCode')
        //     ->leftJoin('service_address as sa', function ($join) {
        //         $join->on('customers.id', '=', 'sa.customer_id')
        //             ->whereRaw('sa.id = (SELECT MAX(id) FROM service_address WHERE customer_id = customers.id)');
        //     })
        //     ->leftJoin('zone_settings as zs', function ($join) {
        //         $join->whereRaw('FIND_IN_SET(LEFT(sa.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
        //     })
        //     ->where('customers.customer_type', 'residential_customer_type')
        //     ->limit(3)
        //     ->orderBy('customers.created_at', 'desc')
        //     ->get();
        //   dd(  $customersResidential);


        //    $customersCommercial = Crm::select('customers.*', 'zs.zone_color')
        //    ->selectRaw('LEFT(sa.postal_code, 2) as shortPostalCode')
        //    ->leftJoin('service_address as sa', function ($join) {
        //        $join->on('customers.id', '=', 'sa.customer_id')
        //            ->whereRaw('sa.id = (SELECT MAX(id) FROM service_address WHERE customer_id = customers.id)');
        //    })
        //    ->leftJoin('zone_settings as zs', function ($join) {
        //        $join->whereRaw('FIND_IN_SET(LEFT(sa.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
        //    })
        //    ->where('customers.customer_type', 'commercial_customer_type')
        //    ->limit(3)
        //    ->orderBy('customers.created_at', 'desc')
        //    ->get();

        $customersResidential = Crm::where('customers.customer_type', 'residential_customer_type')
            ->limit(3)
            ->orderBy('customers.created_at', 'desc')
            ->get();

        foreach ($customersResidential as $item) {
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

        $customersCommercial = Crm::where('customers.customer_type', 'commercial_customer_type')
            ->limit(3)
            ->orderBy('customers.created_at', 'desc')
            ->get();

        foreach ($customersCommercial as $item) {
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

        $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();

        $service_date = array();
        foreach ($get_current_month_service as $item) {
            $service_date[] = array(
                'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
                'service' => $item->service_name
            );
        }
        $dates = json_encode((object)$service_date);
        $emailTemplates = EmailTemplate::get();

        // tax

        // $tax = Tax::first();

        $today_date = date('Y-m-d');
        $tax = Tax::whereDate('from_date', '<=', $today_date)
                    ->whereDate('to_date', '>=', $today_date)
                    ->first();

        return view('admin.quotation.create', compact('companyList', 'service', 'customersResidential', 'customersCommercial', 'dates', 'tax', 'emailTemplates'));
    }

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
            

            foreach ($customers as $item) {
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

            foreach ($customers as $item) {
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
        }

        return response()->json($customers);
    }


    // public function store(Request $request)
    // {
    //     //dd($request->all());
        
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
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }

    //     $company = Company::find($request->company_id);

    //     $comp_short_name = $company->short_name ?? '';

    //     $quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";
    //     // $quotation_no = "Q-" . substr(date('Y'), -2) . "-";

    //     $lead_data = Quotation::orderBy('created_at', 'desc')->get();

    //     if ($lead_data->isEmpty()) {
    //         $quotation_no .= "000001";
    //     } else {
    //         // $last_quotation_no = $lead_data[0]->quotation_no;
    //         // $quotation_no .= sprintf("%06d", (int)substr($last_quotation_no, 7) + 1);

    //         $last_quotation_no = [];

    //         foreach($lead_data as $od)
    //         {
    //             $last_quotation_no[] = explode("-", $od->quotation_no)[2];
    //         }

    //         $quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
    //     }

    //     // return $quotation_no;

    //     $quotationDetails = new Quotation([
    //         'customer_id' => $request->customer_id,
    //         'company_id' => $request->company_id,
    //         'service_address' => $request->input('service-address-radio'),
    //         'billing_address' => $request->input('billing-address-radio'),
    //         'amount' => $request->amount ?? 0.00,
    //         'discount' => $request->discount ?? 0.00,
    //         'tax' => $request->tax ?? 0.00,
    //         'tax_percent' => $request->tax_percent ?? 0.00,
    //         'grand_total' => $request->grand_total ?? 0.00,
    //         // 'schedule_date' => $request->date_of_cleaning,
    //         'time_of_cleaning' => $request->time_of_cleaning,
    //         'quotation_no' => $quotation_no,
    //         'remarks' => $request->quotation_remarks,
    //         'created_by' => Auth::user()->id,
    //         'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name
    //     ]);

    //     if($request->filled('date_of_cleaning'))
    //     {
    //         $quotationDetails->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
    //     }
    //     else
    //     {
    //         $quotationDetails->schedule_date = null;
    //     }

    //     // dd($quotationDetails);

    //     $quotationDetails->save();
    //     $quotationId = $quotationDetails->id;
    //     $total_amount = 0;

    //     $preview_service_id = $request->preview_service_id;
    //     $preview_service_product_code = $request->preview_service_product_code;
    //     $preview_service_name = $request->preview_service_name;
    //     $preview_service_desc = $request->preview_service_desc;
    //     $preview_service_qty = $request->preview_service_qty;
    //     $preview_service_unitPrice = $request->preview_service_unitPrice;
    //     $preview_service_discount = $request->preview_service_discount;

    //     for ($i = 0; $i < count($preview_service_id); $i++) {
    //         $db_service = Services::find($preview_service_id[$i]);

    //         if ($db_service) {
    //             $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
    //             $service_discount_amount = $service_total_amount * ($preview_service_discount[$i] / 100);
    //             $gross_amount = $service_total_amount - $service_discount_amount;

    //             $service = new QuotationServiceDetail();

    //             $service->quotation_id = $quotationId;
    //             $service->service_id = $preview_service_id[$i];
    //             $service->product_code = $preview_service_product_code[$i];
    //             $service->name = $preview_service_name[$i];
    //             $service->description = $preview_service_desc[$i];
    //             $service->unit_price = $preview_service_unitPrice[$i];
    //             $service->quantity = $preview_service_qty[$i];
    //             $service->discount = $preview_service_discount[$i];
    //             $service->gross_amount = $gross_amount;
    //             $service->service_type = "service";

    //             $service->save();

    //             $total_amount += $gross_amount;
    //         }
    //     }

    //     if ($request->filled('preview_add_ons_service_name')) {
    //         $preview_add_ons_product_code = $request->preview_add_ons_product_code;
    //         $preview_add_ons_service_name = $request->preview_add_ons_service_name;
    //         $preview_add_ons_service_desc = $request->preview_add_ons_service_desc;
    //         $preview_add_ons_service_qty = $request->preview_add_ons_service_qty;
    //         $preview_add_ons_service_unitPrice = $request->preview_add_ons_service_unitPrice;
    //         $preview_add_ons_service_discount = $request->preview_add_ons_service_discount;

    //         for ($i = 0; $i < count($preview_add_ons_service_name); $i++) {
    //             $service_total_amount = $preview_add_ons_service_unitPrice[$i] * $preview_add_ons_service_qty[$i];
    //             $service_discount_amount = $service_total_amount * ($preview_add_ons_service_discount[$i] / 100);
    //             $gross_amount = $service_total_amount - $service_discount_amount;

    //             $service = new QuotationServiceDetail();

    //             $service->quotation_id = $quotationId;
    //             $service->product_code = $preview_add_ons_product_code[$i];
    //             $service->name = $preview_add_ons_service_name[$i];
    //             $service->description = $preview_add_ons_service_desc[$i];
    //             $service->unit_price = $preview_add_ons_service_unitPrice[$i];
    //             $service->quantity = $preview_add_ons_service_qty[$i];
    //             $service->discount = $preview_add_ons_service_discount[$i];
    //             $service->gross_amount = $gross_amount;
    //             $service->service_type = "addons";

    //             $service->save();

    //             $total_amount += $gross_amount;
    //         }
    //     }

    //     $discount_Type = $request->discount_type;

    //     if ($request->filled('add_tax_check')) {
    //         $tax = $request->tax;
    //         $tax_type = "exclusive";
    //     } else {
    //         // $tax = 0;
    //         $tax = $request->tax;
    //         $tax_type = "inclusive";
    //     }

    //     if ($discount_Type == "percentage") {
    //         $discount = $request->persentage_discount;
    //         $discount_amount = $total_amount * ($discount / 100);
    //     } else {
    //         $discount = $request->amount_discount;
    //         $discount_amount = $discount;
    //     }

    //     $total = $total_amount - $discount_amount;

    //     if ($tax_type == "exclusive") {
    //         $tax_amt = $total * $tax / 100;
    //         $grand_total = $total + $tax_amt;
    //     } else if ($tax_type == "inclusive") {
    //         $tax_amt = ($total / (100 + $tax)) * $tax;
    //         $grand_total = $total;
    //     }

    //     $quotationDetails->discount_type = $discount_Type;
    //     $quotationDetails->discount = $discount;
    //     $quotationDetails->tax = $tax_amt;
    //     $quotationDetails->tax_percent = $tax;
    //     $quotationDetails->tax_type = $tax_type;
    //     $quotationDetails->amount = $total_amount;
    //     $quotationDetails->grand_total = $grand_total;
    //     $quotationDetails->save();

    //     // log data store start

    //     LogController::store('quotation', 'Quotation Created', $quotationId);

    //     // log data store end

    //     return response()->json(['success' => 'Quotation Created successfully!']);
    // }

    public function store(Request $request)
    {
        //dd($request->all());
        
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
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $quotationId = $this->temp_quotation_store($request);      

        // log data store start

        LogController::store('quotation', 'Quotation Created', $quotationId);

        // log data store end

        return response()->json(['success' => 'Quotation Created successfully!']);
    }

    // public function update(Request $request)
    // {
    //     return $request->all();

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

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }

    //     if(Quotation::where('id', $request->quotation_id)->whereIn('status', [3, 4])->exists())
    //     {
    //         return response()->json(['failed' => 'Quotation already Approved']);
    //     }

    //     $company = Company::find($request->company_id);
    //     $comp_short_name = $company->short_name ?? '';
        
    //     $quotationId = $request->quotation_id;
    //     $quotation = Quotation::where('id', $quotationId)->first();

    //     $quotation_no = $quotation->quotation_no;
    //     $quot_arr = explode("-", $quotation_no);
    //     $quot_arr[0] = $comp_short_name . "Q";
    //     $new_quotation_no = implode("-", $quot_arr);

    //     $quotation->customer_id = $request->customer_id;
    //     $quotation->company_id = $request->company_id;
    //     $quotation->service_address = $request->input('service-address-radio');
    //     $quotation->billing_address = $request->input('billing-address-radio');

    //     if($request->filled('date_of_cleaning'))
    //     {
    //         $quotation->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
    //     }
    //     else
    //     {
    //         $quotation->schedule_date = null;
    //     }

    //     $quotation->time_of_cleaning = $request->time_of_cleaning;
    //     $quotation->quotation_no = $new_quotation_no;
    //     $quotation->remarks = $request->edit_quotation_remarks;

    //     $quotation->save();
    //     $total_amount = 0;

    //     // quotation service start

    //     QuotationServiceDetail::where('quotation_id', $quotationId)->delete();

    //     $preview_service_id = $request->preview_service_id;
    //     $preview_service_product_code = $request->preview_service_product_code;
    //     $preview_service_name = $request->preview_service_name;
    //     $preview_service_desc = $request->preview_service_desc;
    //     $preview_service_qty = $request->preview_service_qty;
    //     $preview_service_unitPrice = $request->preview_service_unitPrice;
    //     $preview_service_discount = $request->preview_service_discount;

    //     for ($i = 0; $i < count($preview_service_id); $i++) {
    //         $db_service = Services::find($preview_service_id[$i]);

    //         if ($db_service) {
    //             $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
    //             $service_discount_amount = $service_total_amount * ($preview_service_discount[$i] / 100);
    //             $gross_amount = $service_total_amount - $service_discount_amount;

    //             $service = new QuotationServiceDetail();

    //             $service->quotation_id = $quotationId;
    //             $service->service_id = $preview_service_id[$i];
    //             $service->product_code = $preview_service_product_code[$i];
    //             $service->name = $preview_service_name[$i];
    //             $service->description = $preview_service_desc[$i];
    //             $service->unit_price = $preview_service_unitPrice[$i];
    //             $service->quantity = $preview_service_qty[$i];
    //             $service->discount = $preview_service_discount[$i];
    //             $service->gross_amount = $gross_amount;
    //             $service->service_type = "service";

    //             $service->save();

    //             $total_amount += $gross_amount;
    //         }
    //     }

    //     if ($request->filled('preview_add_ons_service_name')) {
    //         $preview_add_ons_product_code = $request->preview_add_ons_product_code;
    //         $preview_add_ons_service_name = $request->preview_add_ons_service_name;
    //         $preview_add_ons_service_desc = $request->preview_add_ons_service_desc;
    //         $preview_add_ons_service_qty = $request->preview_add_ons_service_qty;
    //         $preview_add_ons_service_unitPrice = $request->preview_add_ons_service_unitPrice;
    //         $preview_add_ons_service_discount = $request->preview_add_ons_service_discount;

    //         for ($i = 0; $i < count($preview_add_ons_service_name); $i++) {
    //             $service_total_amount = $preview_add_ons_service_unitPrice[$i] * $preview_add_ons_service_qty[$i];
    //             $service_discount_amount = $service_total_amount * ($preview_add_ons_service_discount[$i] / 100);
    //             $gross_amount = $service_total_amount - $service_discount_amount;

    //             $service = new QuotationServiceDetail();

    //             $service->quotation_id = $quotationId;
    //             $service->product_code = $preview_add_ons_product_code[$i];
    //             $service->name = $preview_add_ons_service_name[$i];
    //             $service->description = $preview_add_ons_service_desc[$i];
    //             $service->unit_price = $preview_add_ons_service_unitPrice[$i];
    //             $service->quantity = $preview_add_ons_service_qty[$i];
    //             $service->discount = $preview_add_ons_service_discount[$i];
    //             $service->gross_amount = $gross_amount;
    //             $service->service_type = "addons";

    //             $service->save();

    //             $total_amount += $gross_amount;
    //         }
    //     }

    //     // quotation service end

    //     $discount_Type = $request->discount_type;

    //     if ($discount_Type == "percentage") {
    //         $discount = $request->persentage_discount;
    //         $discount_amount = $total_amount * ($discount / 100);
    //     } else {
    //         $discount = $request->amount_discount;
    //         $discount_amount = $discount;
    //     }

    //     if ($request->filled('add_tax_check')) {
    //         $tax = $request->tax;
    //         $tax_type = "exclusive";
    //     } else {
    //         // $tax = 0;
    //         $tax = $request->tax;
    //         $tax_type = "inclusive";
    //     }

    //     $total = $total_amount - $discount_amount;

    //     if ($tax_type == "exclusive") {
    //         $tax_amt = $total * $tax / 100;
    //         $grand_total = $total + $tax_amt;
    //     } else if ($tax_type == "inclusive") {
    //         $tax_amt = ($total / (100 + $tax)) * $tax;
    //         $grand_total = $total;
    //     }


    //     $quotation->discount_type = $discount_Type;
    //     $quotation->discount = $discount;
    //     $quotation->tax = $tax_amt;
    //     $quotation->tax_percent = $tax;
    //     $quotation->tax_type = $tax_type;
    //     $quotation->amount = $total_amount;
    //     $quotation->grand_total = $grand_total;
    //     $quotation->save();

    //     // $get_quotation = Quotation::where('id', $quotationId)->first();
    //     // //$quotation_id = $get_quotation->id;

    //     // $get_quotation = Quotation::find($quotationId);

    //     // $get_quotation->customer_id = $get_quotation->customer_id;
    //     // $get_quotation->company_id = $get_quotation->company_id;
    //     // $get_quotation->service_address = $get_quotation->service_address;
    //     // $get_quotation->billing_address = $get_quotation->billing_address;
    //     // $get_quotation->amount = $get_quotation->amount;
    //     // $get_quotation->discount_type = $get_quotation->discount_type;
    //     // $get_quotation->discount = $get_quotation->discount;
    //     // $get_quotation->tax = $get_quotation->tax;
    //     // $get_quotation->tax_percent = $get_quotation->tax_percent;
    //     // $get_quotation->grand_total = $get_quotation->grand_total;
    //     // $get_quotation->schedule_date = $get_quotation->schedule_date;
    //     // $get_quotation->time_of_cleaning = $get_quotation->time_of_cleaning;
    //     // $get_quotation->quotation_no = $get_quotation->quotation_no;
    //     // $get_quotation->save();

    //     // // QuotationServiceDetail::where('quotation_id', $quotationId)->delete();

    //     // $get_lead_service = QuotationServiceDetail::where('quotation_id', $quotationId)->get();

    //     // foreach ($get_lead_service as $item) {
    //     //     $quotation_service = new QuotationServiceDetail();

    //     //     $quotation_service->quotation_id = $quotationId;
    //     //     $quotation_service->service_id = $item->service_id;
    //     //     $quotation_service->name = $item->name;
    //     //     $quotation_service->description = $item->description;
    //     //     $quotation_service->unit_price = $item->unit_price;
    //     //     $quotation_service->quantity = $item->quantity;
    //     //     $quotation_service->discount = $item->discount;
    //     //     $quotation_service->gross_amount = $item->gross_amount;
    //     //     $quotation_service->service_type = $item->service_type;

    //     //     $quotation_service->save();
    //     // }

    //     // log data store start

    //     LogController::store('quotation', 'Quotation Updated', $quotationId);

    //     // log data store end

    //     return response()->json(['success' => 'Quotation Updated successfully!']);
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Quotation::where('id', $request->quotation_id)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['failed' => 'Quotation already Approved']);
        }

        $quotationId = $this->temp_quotation_update($request);
       
        // log data store start

        LogController::store('quotation', 'Quotation Updated', $quotationId);

        // log data store end

        return response()->json(['success' => 'Quotation Updated successfully!']);
    }

    public function delete(Request $request)
    {
        $quotation_id = $request->quotation_id;

        // quotation
        $quotation = Quotation::find($quotation_id);
        $lead_id = $quotation->lead_id ?? '';

        if($quotation)
        {
            $quotation->delete();
        }

        if(QuotationServiceDetail::where('quotation_id', $quotation_id)->exists())
        {
            QuotationServiceDetail::where('quotation_id', $quotation_id)->delete();
        }

        // lead

        $lead = Lead::find($lead_id);
        if ($lead)
        {
            $lead->delete();
        }

        if(LeadServices::where('lead_id', $lead_id)->exists())
        {
            LeadServices::where('lead_id', $lead_id)->delete();
        }

        // sales order
        $sales_order = SalesOrder::where('quotation_id', $quotation_id)->first();
        $sales_order_id = $sales_order->id ?? '';

        if(SalesOrder::where('quotation_id', $quotation_id)->exists())
        {
            SalesOrder::where('quotation_id', $quotation_id)->delete();
        }

        // payment
        if(LeadPaymentInfo::where('quotation_id', $quotation_id)->exists())
        {
            LeadPaymentInfo::where('quotation_id', $quotation_id)->delete();
        }

        if(LeadOfflinePaymentDetail::where('quotation_id', $quotation_id)->exists())
        {
            LeadOfflinePaymentDetail::where('quotation_id', $quotation_id)->delete();
        }

        if(DB::table('payment_proof')->where('quotation_id', $quotation_id)->exists())
        {
            DB::table('payment_proof')->where('quotation_id', $quotation_id)->delete();
        }

        // payment history
        if(PaymentHistoryDetail::where('quotation_id', $quotation_id)->exists())
        {
            $payment_history_id = PaymentHistoryDetail::where('quotation_id', $quotation_id)->pluck('payment_history_id');
            PaymentHistory::whereIn('id', $payment_history_id)->delete();

            PaymentHistoryDetail::where('quotation_id', $quotation_id)->delete();
        }

        // schedule
        if(ScheduleModel::where('sales_order_id', $sales_order_id)->exists())
        {
            ScheduleModel::where('sales_order_id', $sales_order_id)->delete();
        }

        if(ScheduleDetails::where('sales_order_id', $sales_order_id)->exists())
        {
            ScheduleDetails::where('sales_order_id', $sales_order_id)->delete();
        }

        if(DB::table('tble_schedule_employee')->where('sales_order_id', $sales_order_id)->exists())
        {
            DB::table('tble_schedule_employee')->where('sales_order_id', $sales_order_id)->delete();
        }

        // job details
        if(JobDetail::where('sales_order_id', $sales_order_id)->exists())
        {
            JobDetail::where('sales_order_id', $sales_order_id)->delete();
        }

        // after cleaning photos
        if(DB::table('after_cleaning_photos')->where('sales_order_id', $sales_order_id)->exists())
        {
            DB::table('after_cleaning_photos')->where('sales_order_id', $sales_order_id)->delete();
        }

        // before cleaning photos
        if(DB::table('before_cleaning_photos')->where('sales_order_id', $sales_order_id)->exists())
        {
            DB::table('before_cleaning_photos')->where('sales_order_id', $sales_order_id)->delete();
        }

        // log data store start

        LogController::store('quotation', 'Quotation Deleted', $quotation_id);

        // log data store end

        return response()->json(['status' => 'success', 'message'=>'Quotation Deleted Successfully']);
    }

    public function edit(Request $request)
    {
        $this->data['companyList'] = Company::get();
        $this->data['quotation'] = $quotation = Quotation::select('quotations.*', 'service_address.customer_id as service_customer_id', 'service_address.address as customer_service_address', 'billing_address.customer_id as billing_customer_id', 'billing_address.address as customer_billing_address')
            ->leftjoin('service_address', 'quotations.customer_id', '=', 'service_address.customer_id')
            ->leftjoin('billing_address', 'quotations.customer_id', '=', 'billing_address.customer_id')
            ->where('quotations.id', $request->quotationId)->first();

        $this->data['customer'] = Crm::where('id', $quotation->customer_id)->first();
        $this->data['service'] = LeadServices::where('lead_id', $quotation->lead_id)->get();
        $addresses = ServiceAddress::where('customer_id', $quotation->customer_id)->get();
        $billingaddresses = BillingAddress::where('customer_id', $quotation->customer_id)->get();
        $services = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();
        foreach ($services as $item) {
            $get_service_details = Services::find($item->service_id);
            if ($get_service_details) {
                $item->hour_session = $get_service_details->hour_session;
                $item->total_session = $item->total_session ? $item->total_session : $get_service_details->total_session;
                $item->weekly_freq = $get_service_details->weekly_freq;
            } else {
                $item->hour_session = "";
                // $item->total_session = "";
                $item->weekly_freq = "";
            }
        }

        // return $services;

        $get_current_month_service = LeadSchedule::select('lead_schedules.cleaning_date', 'services.service_name')->leftJoin('services', 'services.id', '=', 'lead_schedules.service_id')->get();
        //dd($get_service_details);
        $service_date = array();
        foreach ($get_current_month_service as $item) {
            $service_date[] = array(
                'date' =>  date("D M d Y", strtotime($item->cleaning_date)),
                'service' => $item->service_name
            );
        }
        $dates = json_encode((object)$service_date);
        $emailTemplates = EmailTemplate::get();
        // echo"<pre>"; print_r($this->data['quotation']);exit;
        // dd( $services);

        // return $services;

        // tax

        // $tax = Tax::first();

        $today_date = date('Y-m-d');
        $tax = Tax::whereDate('from_date', '<=', $today_date)
                    ->whereDate('to_date', '>=', $today_date)
                    ->first();

        return view('admin.quotation.edit', $this->data, compact('addresses', 'billingaddresses', 'services', 'dates', 'tax', 'emailTemplates'));
    }

    // public function view(Request $request)
    // {
    //     $this->data['quotation'] = $quotation = Quotation::select('quotations.*', 'service_address.customer_id as service_customer_id', 'service_address.address as service_address', 'billing_address.customer_id as billing_customer_id', 'billing_address.address as billing_address')
    //         ->leftjoin('service_address', 'quotations.customer_id', '=', 'service_address.customer_id')
    //         ->leftjoin('billing_address', 'quotations.customer_id', '=', 'billing_address.customer_id')
    //         ->where('quotations.id', $request->quotationId)->first();

    //     $this->data['customer'] = Crm::where('id', $quotation->customer_id)->first();

    //     if ($quotation->lead_id === null) {
    //         // If lead_id is null, check QuotationServiceDetail
    //         $this->data['service'] = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();
    //     } elseif ($quotation->lead_id !== null) {
    //         // If lead_id is not null, check LeadServices
    //         $this->data['service'] = LeadServices::where('lead_id', $quotation->lead_id)->get();
    //     }
    //     //dd($this->data['service']);
    //     return view('admin.quotation.view', $this->data);
    // }

    public function view(Request $request)
    {
        $quotationId = $request->quotationId;

        $data['quotation'] = Quotation::select('quotations.*', 'service_address.address as full_service_address', 'billing_address.address as full_billing_address')
                                        ->leftjoin('service_address', 'quotations.service_address', '=', 'service_address.id')
                                        ->leftjoin('billing_address', 'quotations.billing_address', '=', 'billing_address.id')
                                        ->where('quotations.id', $quotationId)
                                        ->first();

        $data['service'] = QuotationServiceDetail::where('quotation_id', $quotationId)->get();

        // start

        $subtotal = 0;

        foreach($data['service'] as $item)
        {
            $subtotal += $item->unit_price * $item->quantity;
        }

        $nettotal = $data['quotation']->amount;

        if($data['quotation']->discount_type == "percentage")
        {
            $discount_amt = $nettotal * $data['quotation']->discount/100;
        }
        else
        {
            $discount_amt = $data['quotation']->discount;
        }

        $total = $nettotal - $discount_amt;

        $data['quotation']->subtotal = $subtotal;
        $data['quotation']->nettotal = $nettotal;
        $data['quotation']->discount_amt = $discount_amt;
        $data['quotation']->total = $total;

        // end

        $customerId = $data['quotation']->customer_id;

        $data['customer'] = Crm::where('customers.id', $customerId)->first();

        $payment_terms = PaymentTerms::find($data['customer']->payment_terms);

        if($payment_terms)
        {
            $data['customer']->payment_terms_value = $payment_terms->payment_terms;
        }
        else
        {
            $data['customer']->payment_terms_value = "";
        }

        $data['company'] = Company::where('id', $data['quotation']->company_id)->first();

        $data['term_condition'] = TermCondition::where('company_id', $data['quotation']->company_id)->get();

        $data['imagePath'] = 'application/public/company_logos/' . $data['company']->company_logo;

        $data['lead_payment_detail'] = LeadPaymentInfo::where('quotation_id', $quotationId)->get();

        foreach($data['lead_payment_detail'] as $item)
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

        // return $data;

        return view('admin.quotation.view', $data);
    }


    public function getResidentialData(Request $request)
    {
        $quotation = Quotation::select('quotations.*', 'customers.id as customerId', 'customers.customer_name', 'customers.email', 'customers.mobile_number', 'customers.customer_type', 'company.company_name')                        
            ->leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')
            ->leftjoin('company', 'quotations.company_id', '=', 'company.id')
            ->where('quotations.status', '!=', 0)
            ->where('quotations.quotation_type', '=', 1)
            ->where('quotations.company_id', $request->company_id)
            ->where('customers.customer_type', 'residential_customer_type')
            ->orderBy('quotations.created_at', 'desc')
            ->get();

        $quotationData = '';

        foreach ($quotation as $key => $item) {
            if ($item->customer_type == 'residential_customer_type') {

                // $status = ($item->status == 2) ? '<span class="badge bg-success">Confirmed</span>' : '<span class="badge bg-red">Pending</span>';

                if ($item->status == 1) {
                    $status = '<span class="badge bg-yellow">Pending</span>';
                } elseif ($item->status == 2) {
                    $status = '<span class="badge bg-red">Pending Customer Approval</span>';
                } elseif ($item->status == 3) {
                    $status = '<span class="badge bg-red">Pending Payment</span>';
                } elseif ($item->status == 4) {
                    $status = '<span class="badge bg-green">Approved</span>';
                } elseif ($item->status == 5) {
                    $status = '<span class="badge bg-red">Rejected</span>';
                } 
                else {
                    $status = '';
                }

                if ($item->payment_advice == 1) {
                    $payment_advice_status = '<span class="badge bg-red">Pending</span>';
                } elseif ($item->payment_advice == 2) {
                    $payment_advice_status = '<span class="badge bg-green">Received</span>';
                } else {
                    $payment_advice_status = "";
                }

                $quotationData .= '
                <tr>
                    <td>' . ($key + 1) . ' </td>
                    <td><span class="text-muted">' . $item->quotation_no . '</span></td>
                    <td>'. $item->company_name .'</td>
                    <td>' . date('d-m-Y', strtotime($item->created_at. ' + 14 days')) . '</td>
                    <td> $' . $item->grand_total . '</td>
                    <td>' . $item->created_by_name . '</td>
                    <td>' . $status . '</td>
                    <td>' . $payment_advice_status . '</td>

                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle align-text-top t-btn"
                                data-bs-toggle="dropdown"
                                aria-expanded="true">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-end d-menu"
                                style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                data-popper-placement="bottom-end">
                                <a class="dropdown-item" href="#"
                                    data-bs-toggle="modal"
                                    data-bs-target="#view-quotation" onclick="viewQuotation(' . $item->id . ')">
                                    <i class="fa-solid fa-eye me-2 text-blue"></i>
                                    View
                                </a>';

                if ($item->status == 1 || $item->status == 2) 
                {
                    $quotationData .= '<a class="dropdown-item" href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#edit-quotation" onclick="editQuotation(' . $item->id . ')">
                                            <i class="fa-solid fa-pencil me-2 text-yellow"></i>
                                            Edit
                                        </a>';
                }

                if($item->status != 5)
                {
                    $quotationData .= '<a class="dropdown-item" href="'.route('quotation.download', $item->id).'">
                                            <i class="fa-solid fa-download me-2 text-blue"></i>
                                            Download
                                        </a>';
                }

                if($item->status != 5 && $item->status != 4 && $item->payment_advice == 1)
                {
                    $quotationData .= '<a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess('.$item->id.')">
                                            <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                            Send Payment Advice
                                        </a>';        
                }              
                elseif($item->status == 3 && $item->payment_advice == 2)
                {
                    $quotationData .= '<a class="dropdown-item" href="#" onclick="received_payment('.$item->id.')">
                                            <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                Payment
                                        </a>';
                }

                if($item->status == 5)
                {
                    $quotationData .=  '<a class="dropdown-item" onclick="change_status('. $item->id .')">
                                            <i class="fa-solid fa-edit me-2 text-green"></i>
                                            Change Rejected Quotation Status
                                        </a>';
                }   

                $quotationData .=  '<a class="dropdown-item" onclick="duplicate('.$item->id.')" href="#">
                                        <i class="fa-solid fa-copy me-2 text-yellow"></i>
                                        Duplicate
                                    </a>
                                    <a class="dropdown-item" onclick="delete_quotation('.$item->id.')" href="#">
                                        <i class="fa-solid fa-trash me-2 text-red"></i>
                                        Delete
                                    </a>
                                    <a class="dropdown-item" href="'.route('quotation.log-report', $item->id).'">
                                        <i class="fa-solid fa-file me-2 text-blue"></i>
                                        Log Report
                                    </a>
                                    <a class="dropdown-item send_mail_btn" href="#" title="Send Email" data-quotation_id="'.$item->id.'" data-invoice_no="'.$item->invoice_no.'" data-customer_id="'.$item->customer_id.'" data-schedule_date="'.$item->schedule_date.'" data-quotation_no="'.$item->quotation_no.'">
                                        <i class="fa fa-envelope me-2 text-blue"></i>
                                        Send Email
                                    </a>'; 
                

                // Close the existing code
                $quotationData .= '</div>
                                </div>
                            </td>
                        </tr>';
            }
        }
        return $quotationData;
    }

    public function getCommercialData(Request $request)
    {
        $quotation = Quotation::select('quotations.*', 'customers.id as customerId', 'customers.customer_name', 'customers.individual_company_name', 'customers.email', 'customers.mobile_number', 'customers.customer_type', 'company.company_name')
            ->leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')
            ->leftjoin('company', 'quotations.company_id', '=', 'company.id')
            ->where('quotations.status', '!=', 0)
            ->where('quotations.quotation_type', '=', 1)
            ->where('company_id', $request->company_id)
            ->where('customers.customer_type', 'commercial_customer_type')
            ->orderBy('quotations.created_at', 'desc')
            ->get();


        $quotationData = '';

        foreach ($quotation as $key => $item) {
            if ($item->customer_type == 'commercial_customer_type') {
                // $status = ($item->status == 2) ? '<span class="badge bg-success">Confirmed</span>' : '<span class="badge bg-red">Pending</span>';

                if ($item->status == 1) {
                    $status = '<span class="badge bg-yellow">Pending</span>';
                } elseif ($item->status == 2) {
                    $status = '<span class="badge bg-red">Pending Customer Approval</span>';
                } elseif ($item->status == 3) {
                    $status = '<span class="badge bg-red">Pending Payment</span>';
                } elseif ($item->status == 4) {
                    $status = '<span class="badge bg-green">Approved</span>';
                } elseif ($item->status == 5) {
                    $status = '<span class="badge bg-red">Rejected</span>';
                } 
                else {
                    $status = '';
                }

                if ($item->payment_advice == 1) {
                    $payment_advice_status = '<span class="badge bg-red">Pending</span>';
                } elseif ($item->payment_advice == 2) {
                    $payment_advice_status = '<span class="badge bg-green">Received</span>';
                } else {
                    $payment_advice_status = "";
                }

                $quotationData .= '
                <tr>
                    <td>' . ($key + 1) . ' </td>
                    <td><span class="text-muted">' . $item->quotation_no . '</span></td>
                    <td>'. $item->company_name .'</td>
                    <td>' . date('d-m-Y', strtotime($item->created_at. ' + 14 days')) . '</td>
                    <td> $' . $item->grand_total . '</td>
                    <td>' . $item->created_by_name . '</td>
                    <td>' . $status . '</td>
                    <td>' . $payment_advice_status . '</td>

                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle align-text-top t-btn"
                                data-bs-toggle="dropdown"
                                aria-expanded="true">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-end d-menu"
                                style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                data-popper-placement="bottom-end">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#view-quotation"
                                    onclick="viewQuotation(' . $item->id . ')">
                                    <i class="fa-solid fa-eye me-2 text-blue"></i>
                                    View
                                </a>';

                    if ($item->status == 1 || $item->status == 2) 
                    {
                        $quotationData .= '<a class="dropdown-item" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit-quotation" onclick="editQuotation(' . $item->id . ')">
                                                <i class="fa-solid fa-pencil me-2 text-yellow"></i>
                                                Edit
                                            </a>';
                    }
    
                    if($item->status != 5)
                    {
                        $quotationData .= '<a class="dropdown-item" href="'.route('quotation.download', $item->id).'">
                                                <i class="fa-solid fa-download me-2 text-blue"></i>
                                                Download
                                            </a>';
                    }
                
                    if($item->status != 5 && $item->status != 4 && $item->payment_advice == 1)
                    {
                        $quotationData .= '<a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess('.$item->id.')">
                                                <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                Send Payment Advice
                                            </a>';        
                    }              
                    elseif($item->status == 3 && $item->payment_advice == 2)
                    {
                        $quotationData .= '<a class="dropdown-item" href="#" onclick="received_payment('.$item->id.')">
                                                <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                                    Payment
                                            </a>';
                    }
                
                    if($item->status == 5)
                    {
                        $quotationData .=  '<a class="dropdown-item" onclick="change_status('. $item->id .')">
                                                <i class="fa-solid fa-edit me-2 text-green"></i>
                                                Change Rejected Quotation Status
                                            </a>';
                    }   
    
                    $quotationData .=  '<a class="dropdown-item" onclick="duplicate('.$item->id.')" href="#">
                                            <i class="fa-solid fa-copy me-2 text-yellow"></i>
                                            Duplicate
                                        </a>
                                        <a class="dropdown-item" onclick="delete_quotation('.$item->id.')" href="#">
                                            <i class="fa-solid fa-trash me-2 text-red"></i>
                                            Delete
                                        </a>
                                        <a class="dropdown-item" href="'.route('quotation.log-report', $item->id).'">
                                            <i class="fa-solid fa-file me-2 text-blue"></i>
                                            Log Report
                                        </a>
                                        <a class="dropdown-item send_mail_btn" href="#" title="Send Email" data-quotation_id="'.$item->id.'" data-invoice_no="'.$item->invoice_no.'" data-customer_id="'.$item->customer_id.'" data-schedule_date="'.$item->schedule_date.'" data-quotation_no="'.$item->quotation_no.'">
                                            <i class="fa fa-envelope me-2 text-blue"></i>
                                            Send Email
                                        </a>'; 

                $quotationData .= '</div>
                            </div>
                        </td>
                    </tr>
                    ';
            }
        }

        return $quotationData;
    }

    public function getemailData(Request $request)
    {

        $customer = Crm::where('id', $request->customer_id)->first();
        $data = EmailTemplate::where('id', $request->template_id)->first();
        $company = Company::where('id', $request->company_id)->first();
        // echo"<pre>"; print_r($customer); exit;
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
                <label class="form-label">Title:</label>
                <input type="text" class="form-control" name="example-text-input"
                    id="emailTitle" value="' . $data->title . '">
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

    public function sendEmail(Request $request)
    {

        $company = Company::where('id', $request->company_id)->first();
        $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();
        $customer = DB::table('customers')->where('id', $request->customer_id)->first();
        // print_r($customer);exit;

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

        Mail::to($customer->email)->send(new SendQuotationEmail($emailTemplate->title, $emailTemplate->subject, $emailTemplate->body, $company->company_name, $attachment));


        // echo"<pre>"; print_r($leadCustomerDetail); exit;
        return redirect()->back();
    }

    public function confirmQuotation(Request $request)
    {
        $quotation = Quotation::where('id', $request->id)->first();

        // $service = LeadServices::where('lead_id',$quotation->lead_id)->get();
        // echo"<pre>"; print_r($service); exit;

        $quotation->status = 2;
        $quotation->save();

        $data = new SalesOrder();
        $data->sales_order_no = rand(12334, 99999);
        $data->customer_id = $quotation->customer_id;
        $data->lead_id = $quotation->lead_id;

        $data->status = 0;

        $data->quotation_id = $quotation->id;

        $data->save();

        return response()->json(['redirect' => route('salesOrder')]);
    }

    public function searchResidential(Request $request)
    {
        if (!empty($request->search_value)) {
            $customer = Quotation::select('quotations.*', 'customers.id as customerId', 'customers.customer_name', 'customers.email', 'customers.mobile_number', 'customers.customer_type')
                // select('quotations.quotation_no', 'quotations.customer_id', 'quotations.status as quotation_status', 'customers.*')
                ->leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')
                ->where('customers.customer_type', 'residential_customer_type')
                ->where('customers.customer_name', 'like', '%' . $request->search_value . '%')
                ->where('quotations.status', '!=', 0)
                ->get();
        } else {
            $customer = Quotation::select('quotations.*', 'customers.id as customerId', 'customers.customer_name', 'customers.email', 'customers.mobile_number', 'customers.customer_type')
                // select('quotations.*', 'customers.*')
                ->leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')
                ->where('customers.customer_type', 'residential_customer_type')
                ->where('quotations.status', '!=', 0)
                ->get();
        }

        if ($customer) {
            $tbody = '';

            foreach ($customer as $key => $value) {
                // if ($value->quotation_status == 1) {
                //     $status = '<span class="badge bg-red">Pending</span>';
                // } else {
                //     $status = '<span class="badge bg-success">Confirm</span>';
                // }

                if ($value->status == 1) {
                    $status = '<span class="badge bg-yellow">Pending</span>';
                } elseif ($value->status == 2) {
                    $status = '<span class="badge bg-red">Pending Customer Approval</span>';
                } elseif ($value->status == 3) {
                    $status = '<span class="badge bg-red">Pending Payment</span>';
                } elseif ($value->status == 4) {
                    $status = '<span class="badge bg-green">Approved</span>';
                } elseif ($value->status == 5) {
                    $status = '<span class="badge bg-red">Rejected</span>';
                } 
                else {
                    $status = '';
                }

                if ($value->payment_advice == 1) {
                    $payment_advice_status = '<span class="badge bg-red">Not Sent</span>';
                } elseif ($value->payment_advice == 2) {
                    $payment_advice_status = '<span class="badge bg-green">Sent</span>';
                } else {
                    $payment_advice_status = "";
                }

                $created_at = $value->created_at->format('d M Y');

                $tbody .= '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td><span class="text-muted">' . $value->quotation_no . '</span></td>
                                <td>' . $value->customer_name . '</td>
                                <td>' . $value->email . '</td>
                                <td>+65' . $value->mobile_number . '</td>
                                <td>' . $created_at . '</td>
                                <td>' . $status . '</td>
                                <td>' . $payment_advice_status . '</td>

                                <td class="text-center">
                                    <input type="hidden" name="customer_id" value="' . $value->customer_id . '" id="quotation_customer_id">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top t-btn"
                                            data-bs-toggle="dropdown" aria-expanded="true">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end d-menu"
                                            style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                            data-popper-placement="bottom-end">
                                            <a class="dropdown-item" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#view-quotation" onclick="viewQuotation( ' . $value->id . ' )">
                                                <i class="fa-solid fa-eye me-2 text-blue"></i>
                                                View
                                            </a>';

                if ($value->status == 1 || $value->status == 2) {
                    $tbody .= '<a class="dropdown-item" href="#"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit-quotation" onclick="editQuotation(' . $value->id . ')">
                                    <i class="fa-solid fa-pencil me-2 text-yellow"></i>
                                    Edit
                                </a>';
                }

                if ($value->status != 5 && $value->payment_advice == 1) {
                    $tbody .=  '<a class="dropdown-item" href="#quotation-payment"  onclick="paymentsProcess(' . $value->id . ')">
                                    <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                    Send Payment Advice
                                </a>';
                } elseif ($value->status == 3 && $value->payment_advice == 2) {
                    $tbody .=  '<a class="dropdown-item" href="#" onclick="received_payment(' . $value->id . ')">
                                    <i class="fa-solid fa-circle-check me-2 text-green"></i>
                                    Payment
                                </a>';
                }

                // Close the existing code
                $tbody .= '</div>
                            </div>
                        </td>
                    </tr>';
            }

            return $tbody;
        } else {
            return 'no result found.';
        }
    }

    public function getQuotationPreview(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();
        $customer = Crm::where('id', $request->customer_id)->first();
        // dd($customer);
        $db_service_address = ServiceAddress::find($request->service_address_id);

        if ($db_service_address) {
            $service_address = $db_service_address->address;
        } else {
            $service_address = "";
        }

        $term_condition = TermCondition::where('company_id', $request->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        $route = route('download.quotation', $company->id);

        // quotation

        if($request->filled('quotation_id'))
        {
            $quotation_id = $request->quotation_id;
            $quotation = Quotation::find($quotation_id);
        }

        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;
        $data['route'] = $route;
        $data['imagePath'] = $imagePath;
        $data['issue_by'] = Auth::user()->first_name . " " . Auth::user()->last_name;
        $data['service_address'] = $service_address;
        $data['quotation'] = $quotation ?? '';

        return view('admin.quotation.preview', $data);
    }

    // download_quotation

    // public function download_quotation($id)
    // {
    //     $quotation = Quotation::find($id);
    //     $quotation_details = QuotationServiceDetail::where('quotation_id', $id)->get();

    //     $ServiceAddress = ServiceAddress::find($quotation->service_address);

    //     if($ServiceAddress)
    //     {
    //         $quotation->service_address_details = $ServiceAddress->address;
    //         $quotation->service_address_unit_number = $ServiceAddress->unit_number;
    //     }
    //     else
    //     {
    //         $quotation->service_address_details = "";
    //         $quotation->service_address_unit_number = "";
    //     }

    //     // lead payment detail start

    //     $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $id)->get();

    //     $deposit = 0;
    //     $balance = 0;
    //     foreach($lead_payment_detail as $item)
    //     {
    //         if($item->payment_status == 1)
    //         {
    //             $deposit += $item->payment_amount;
    //         }
    //     }
    //     $balance = $quotation->grand_total - $deposit;

    //     // lead payment detail end

    //     // calculation start

    //     $subtotal = 0;

    //     foreach($quotation_details as $item)
    //     {
    //         $subtotal += $item->unit_price;
    //     }

    //     $nettotal = $quotation->amount;

    //     if($quotation->discount_type == "percentage")
    //     {
    //         $discount_amt = $nettotal * $quotation->discount/100;
    //     }
    //     else
    //     {
    //         $discount_amt = $quotation->discount;
    //     }

    //     $total = $nettotal - $discount_amt;

    //     $quotation->subtotal = $subtotal;
    //     $quotation->nettotal = $nettotal;
    //     $quotation->discount_amt = $discount_amt;
    //     $quotation->total = $total;
    //     $quotation->deposit = $deposit;
    //     $quotation->balance = $balance;

    //     // calculation end

    //     $company = Company::where('id', $quotation->company_id)->first();

    //     if(!empty($company->company_logo))
    //     {
    //         $company->image_path = 'application/public/company_logos/' . $company->company_logo;
    //     }
    //     else
    //     {
    //         $company->image_path = "";
    //     }

    //     if(!empty($company->qr_code))
    //     {
    //         $company->qr_code_path = "application/public/qr_code/$company->qr_code";
    //     }
    //     else
    //     {
    //         $company->qr_code_path = "";
    //     }

    //     if(!empty($company->stamp))
    //     {
    //         $company->stamp_path = "application/public/stamp/$company->stamp";
    //     }
    //     else
    //     {
    //         $company->stamp_path = "";
    //     }

    //     $customer = DB::table('customers')->where('id', $quotation->customer_id)->first();
    //     $payment_terms = PaymentTerms::find($customer->payment_terms);

    //     if($payment_terms)
    //     {
    //         $customer->payment_terms_value = $payment_terms->payment_terms;
    //     }
    //     else
    //     {
    //         $customer->payment_terms_value = "";
    //     }

    //     $term_condition = TermCondition::where('company_id', $quotation->company_id)->get();

    //     // invoice footer logo

    //     $company_invoice_footer_logo = DB::table('company_invoice_footer_logo')
    //                                         ->where('company_id', $quotation->company_id)
    //                                         ->get();

    //     foreach($company_invoice_footer_logo as $item)
    //     {          
    //         $item->invoice_footer_logo_path = 'application/public/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;            
    //     }                                  

    //     $company->company_invoice_footer_logo = $company_invoice_footer_logo;

    //     $data['quotation'] = $quotation;
    //     $data['quotation_details'] = $quotation_details;
    //     $data['company'] = $company;
    //     $data['customer'] = $customer;
    //     $data['term_condition'] = $term_condition;

    //     return view('admin.quotation.download-quotation', $data);
    // }

    public function download_quotation($id)
    {
        $quotation = Quotation::find($id);
        $quotation_details = QuotationServiceDetail::where('quotation_id', $id)->get();

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

        $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $id)->get();

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
            $item->invoice_footer_logo_path = '/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;       
        }                                  

        $company->company_invoice_footer_logo = $company_invoice_footer_logo;

        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $pdf = PDF::loadView('admin.quotation.invoice', $data);

        return $pdf->stream($quotation->quotation_no.'.pdf');
    }

    // change_status

    public function change_status(Request $request)
    {
        // return $request->all();

        if($request->filled('quotation_id'))
        {
            $quotation_id = $request->quotation_id;

            $quotation = Quotation::find($quotation_id);

            if ($quotation)
            {
                $quotation->status = 1;
                $quotation->payment_advice = 1;
                $quotation->save();

                // lead start

                $lead = Lead::find($quotation->lead_id);

                if($lead)
                {
                    $lead->status = 1;
                    $lead->pending_customer_approval_status = 1;
                    $lead->payment_advice = 1;
                    $lead->save();
                }

                // lead end

                // log data store start

                LogController::store('quotation', 'Quotation status Changed', $quotation_id);

                // log data store end

                return response()->json(['status'=>'success', 'message'=>'Status Changed successfully']);
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Quotation not found']);
            }
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Quotation not found']);
        }
    }

    // duplicate

    public function duplicate(Request $request)
    {
        // return $request->all();

        if($request->filled('quotation_id'))
        {
            $quotation_id = $request->quotation_id;

            $db_quotation = Quotation::find($quotation_id);

            if ($db_quotation)
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
                    $last_quotation_no = [];

                    foreach($lead_data as $od)
                    {
                        $last_quotation_no[] = explode("-", $od->quotation_no)[2];
                    }

                    $quotation_no .= sprintf("%06d", (int)max($last_quotation_no) + 1);
                }

                // return $quotation_no;

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
                $quotation->status = 1;
                $quotation->payment_advice = 1;
                $quotation->quotation_type = 1;
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

                // log data store start

                LogController::store('quotation', 'Quotation Duplicate', $quotation_id);
                LogController::store('quotation', 'Quotation Duplicate from Quotation '.$db_quotation->quotation_no, $quotation->id);

                // log data store end

                return response()->json(['status'=>'success', 'message'=>'Quotation Duplicated successfully']);
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Quotation not found']);
            }
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Quotation not found']);
        }
    }

    // log report

    public function log_report($quotation_id)
    {
        $data['quotation'] = Quotation::find($quotation_id);

        $log_details = DB::table('log_details')
                        ->where('module', 'quotation')
                        ->where('ref_no', $quotation_id)
                        ->paginate(30);

        $data['log_details'] = $log_details;

        return view('admin.quotation.log-report', $data);
    }

    // get_email_data

    public function get_email_data(Request $request)
    {
        $customer = Crm::where('customers.id', $request->customer_id)
                        ->leftJoin('constant_settings','constant_settings.id', '=', 'customers.saluation')
                        ->select('customers.*', 'constant_settings.salutation_name')
                        ->first();

        $data = EmailTemplate::where('id', $request->template_id)->first(); 

        // lead start

        if($request->filled('quotation_id'))
        {
            if($request->filled('quotation_id'))
            {
                $quotation = Quotation::find($request->quotation_id);
            }

            $service_address = ServiceAddress::find($quotation->service_address ?? '');
            $billing_address = BillingAddress::find($quotation->billing_address ?? '');

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

            $grand_total = $quotation->grand_total ?? 0;

            if($quotation->discount_type == "percentage")
            {
                $discount_amount = $quotation->grand_total * ($quotation->discount/100);
            }
            else
            {
                $discount_amount = $quotation->discount;
            }
            
            $tax_amount = $quotation->tax ?? 0;

            $payment_amount = PaymentController::get_deposit_amount($quotation->id);
            $balance_amount = PaymentController::get_balance_amount($quotation->id);

            // quotation no        
            if($request->filled('quotation_no'))
            {
                $quotation_no = $request->quotation_no ?? '';
            }
            else
            {
                $quotation_no = $quotation->quotation_no ?? '';
            }
        }

        // lead end

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

        if($request->filled('temp_invoice_no'))
        {
            $data->body = str_replace("##INVOICE_NO##", $request->temp_invoice_no ?? '', $data->body);
        }

        $data->body = str_replace("##GOOGLE_REVIEW##", '<a href="http://www.absolute.sg/cleaning-google-review">Google Review</a>', $data->body);
        $data->body = str_replace("##FACEBOOK_REVIEW##", '<a href="http://www.absolute.sg/cleaning-facebook-review">Facebook Review</a>', $data->body);
        $data->body = str_replace("##FACEBOOK_LINK##", '<a href="http://www.facebook.com/absoluteservicesingapore/">Absolute Services Singapore</a>', $data->body);
        $data->body = str_replace("##VISIT_US##", '<a href="http://absolute.sg">absolute.sg</a>', $data->body);

        // email body end

        $emailTemplate = '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">To:</label>
                                        <select class="form-select" name="email_to" id="send_email_to">
                                            '.$option.'
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CC:</label>
                                        <input type="text" class="form-control" name="email_cc"
                                            id="send_email_cc" value="' . $data->cc . '">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">BCC:</label>
                                        <input type="text" class="form-control" name="email_bcc"
                                            id="send_email_bcc" value="' . $data->bcc . '">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Subject:</label>
                                        <input type="text" class="form-control" name="email_subject"
                                            id="send_email_subject" value="' . $data->subject . '">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Body:</label>
                                        <textarea class="form-control" name="email_body" id="send_email_body" rows="6" placeholder="Content..">' . $data->body . '</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button class="btn btn-info" onclick="quotation_emailSend(event)" id="send_email_confirm_btn">Confirm</button>
                                </div>
                            </div>';

        return $emailTemplate;
    }

    // send_email

    public function send_email(Request $request)
    {
        // quotation start

        $quotation = Quotation::find($request->quotation_id);
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
        $grand_total = $quotation->grand_total;

        $deposit = PaymentController::get_deposit_amount($quotation->id);
        $balance = PaymentController::get_balance_amount($quotation->id);

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
                                            ->where('company_id', $quotation->company_id)
                                            ->get();

        foreach($company_invoice_footer_logo as $item)
        {          
            $item->invoice_footer_logo_path = '/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;            
        }                                  

        $company->company_invoice_footer_logo = $company_invoice_footer_logo;

        // payment term start

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

        // payment term end

        $term_condition = TermCondition::where('company_id', $quotation->company_id)->get();

        // mail send start

        $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();

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

        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $files = Pdf::loadView('admin.quotation.invoice', $data);
        $file_name = $quotation->quotation_no.".pdf";

        try {                         
            Mail::send('emails.email', $data, function ($message) use ($data, $files, $file_name) {
                    // $message->from(Auth::user()->email);
                    $message->to($data['to_email'])
                            ->cc($data['cc'] ?? [])
                            ->bcc($data['bcc'] ?? [])
                            ->subject($data['subject'])
                            ->attachData($files->output(), $file_name);
            });                                                   
        }
        catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    
        // mail send end

        // log data store start

        LogController::store('quotation', 'Mail Send', $quotation->quotation_no);

        // log data store end
        
        return response()->json(['status'=>'success', 'message'=>'Mail send successfully']);    
    }

    public function add_update_send_email(Request $request)
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
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Quotation::where('id', $request->quotation_id)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['failed' => 'Quotation already Approved']);
        }

        if($request->type == "add")
        {
            $quotationId = $this->temp_quotation_store($request);     

            // log data store start

            LogController::store('quotation', 'Quotation Created and Email Send', $quotationId);

            // log data store end
        }
        else if($request->type == "update")
        {
            $quotationId = $this->temp_quotation_update($request);

            // log data store start

            LogController::store('quotation', 'Quotation Updated and Email Send', $quotationId ?? '');

            // log data store end
        }
        else
        {
            return response()->json(['status'=>'failed', 'message' => 'Email is not Send successfully!']);
        }

        // lead start

        $quotation = Quotation::find($quotationId);
        $lead = Lead::find($quotation->lead_id ?? '');
        if($lead)
        {
            $lead->status = 2;
            $lead->save();
        }

        // lead end

        // quotation start

        $quotation = Quotation::find($quotationId);
        $quotation->status = 2;
        $quotation->save();

        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotationId)->get();

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

        $data['quotation_id'] = $quotationId;
        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $files = PDF::loadView('admin.quotation.invoice', $data);
        $file_name = $quotation->quotation_no.".pdf";

        Mail::send('admin.quotation.mail', $data, function ($message) use ($data, $files, $file_name) {
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

    public static function temp_quotation_update($request)
    {
        $company = Company::find($request->company_id);
        // $comp_short_name = $company->short_name ?? '';

        $quotationId = $request->quotation_id;

        // quotation start

        $quotation = Quotation::where('id', $quotationId)->first();

        // $quotation_no = $quotation->quotation_no;
        // $quot_arr = explode("-", $quotation_no);
        // $quot_arr[0] = $comp_short_name . "Q";
        // $new_quotation_no = implode("-", $quot_arr);

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
        // $quotation->quotation_no = $new_quotation_no;
        $quotation->remarks = $request->edit_quotation_remarks;

        $quotation->save();

        // quotation end

        $total_amount = 0;

        // quotation service start

        QuotationServiceDetail::where('quotation_id', $quotationId)->delete();

        $preview_service_id = $request->preview_service_id;
        $preview_service_product_code = $request->preview_service_product_code;
        $preview_service_name = $request->preview_service_name;
        $preview_service_desc = $request->preview_service_desc;
        $preview_service_qty = $request->preview_service_qty;
        $preview_service_unitPrice = $request->preview_service_unitPrice;
        $preview_service_discount = $request->preview_service_discount;
        $preview_service_total_session = $request->preview_service_total_session;

        for ($i = 0; $i < count($preview_service_id); $i++) {
            $db_service = Services::find($preview_service_id[$i]);

            if ($db_service) {
                $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
                $service_discount_amount = $service_total_amount * ($preview_service_discount[$i] / 100);
                $gross_amount = $service_total_amount - $service_discount_amount;

                $service = new QuotationServiceDetail();

                $service->quotation_id = $quotationId;
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

        if ($request->filled('preview_add_ons_service_name')) {
            $preview_add_ons_product_code = $request->preview_add_ons_product_code;
            $preview_add_ons_service_name = $request->preview_add_ons_service_name;
            $preview_add_ons_service_desc = $request->preview_add_ons_service_desc;
            $preview_add_ons_service_qty = $request->preview_add_ons_service_qty;
            $preview_add_ons_service_unitPrice = $request->preview_add_ons_service_unitPrice;
            $preview_add_ons_service_discount = $request->preview_add_ons_service_discount;

            for ($i = 0; $i < count($preview_add_ons_service_name); $i++) {
                $service_total_amount = $preview_add_ons_service_unitPrice[$i] * $preview_add_ons_service_qty[$i];
                $service_discount_amount = $service_total_amount * ($preview_add_ons_service_discount[$i] / 100);
                $gross_amount = $service_total_amount - $service_discount_amount;

                $service = new QuotationServiceDetail();

                $service->quotation_id = $quotationId;
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

        if ($discount_Type == "percentage") {
            $discount = $request->persentage_discount;
            $discount_amount = $total_amount * ($discount / 100);
        } else {
            $discount = $request->amount_discount;
            $discount_amount = $discount;
        }

        if ($request->filled('add_tax_check')) {
            $tax = $request->tax;
            $tax_type = "exclusive";
        } else {
            // $tax = 0;
            $tax = $request->tax;
            $tax_type = "inclusive";
        }

        $total = $total_amount - $discount_amount;

        if ($tax_type == "exclusive") {
            $tax_amt = $total * $tax / 100;
            $grand_total = $total + $tax_amt;
        } else if ($tax_type == "inclusive") {
            $tax_amt = ($total / (100 + $tax)) * $tax;
            $grand_total = $total;
        }


        $quotation->discount_type = $discount_Type;
        $quotation->discount = $discount;
        $quotation->tax = $tax_amt;
        $quotation->tax_percent = $tax;
        $quotation->tax_type = $tax_type;
        $quotation->amount = $total_amount;
        $quotation->grand_total = $grand_total;
        $quotation->save();


        // lead start

        $get_lead = Lead::where('id', $quotation->lead_id ?? '')->first();

        if($get_lead)
        {
            $lead_id = $get_lead->id;

            $get_quotation = Quotation::find($quotationId);

            $get_lead->customer_id = $get_quotation->customer_id;
            $get_lead->company_id = $get_quotation->company_id;
            $get_lead->service_address = $get_quotation->service_address;
            $get_lead->billing_address = $get_quotation->billing_address;
            $get_lead->amount = $get_quotation->amount;
            $get_lead->discount_type = $get_quotation->discount_type;
            $get_lead->discount = $get_quotation->discount;
            $get_lead->tax = $get_quotation->tax;
            $get_lead->tax_type = $get_quotation->tax_type;
            $get_lead->tax_percent = $get_quotation->tax_percent;
            $get_lead->grand_total = $get_quotation->grand_total;
            $get_lead->schedule_date = $get_quotation->schedule_date;
            $get_lead->time_of_cleaning = $get_quotation->time_of_cleaning;
            $get_lead->quotation_no = $get_quotation->quotation_no;
            $get_lead->remarks = $get_quotation->remarks;

            $get_lead->save();

            LeadServices::where('lead_id', $lead_id)->delete();

            $get_quotation_service = QuotationServiceDetail::where('quotation_id', $quotationId)->get();

            foreach($get_quotation_service as $item)
            {
                $lead_service = new LeadServices();

                $lead_service->lead_id = $lead_id;
                $lead_service->service_id = $item->service_id;
                $lead_service->product_code = $item->product_code;
                $lead_service->name = $item->name;
                $lead_service->description = $item->description;
                $lead_service->unit_price = $item->unit_price;
                $lead_service->quantity = $item->quantity;
                $lead_service->discount = $item->discount;
                $lead_service->gross_amount = $item->gross_amount;
                $lead_service->total_session = $item->total_session;
                $lead_service->service_type = $item->service_type;

                $lead_service->save();
            }
        }

        // lead end

        return $quotationId;
    }

    public static function temp_quotation_store($request)
    {
        $company = Company::find($request->company_id);

        $comp_short_name = $company->short_name ?? '';

        $quotation_no = $comp_short_name . "Q-" . substr(date('Y'), -2) . "-";
        // $quotation_no = "Q-" . substr(date('Y'), -2) . "-";

        $lead_data = Quotation::where('company_id', $request->company_id)->orderBy('created_at', 'desc')->get();

        if ($lead_data->isEmpty()) {
            $quotation_no .= "000001";
        } else {
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

        $quotationDetails = new Quotation([
            'customer_id' => $request->customer_id,
            'company_id' => $request->company_id,
            'service_address' => $request->input('service-address-radio'),
            'billing_address' => $request->input('billing-address-radio'),
            'amount' => $request->amount ?? 0.00,
            'discount' => $request->discount ?? 0.00,
            'tax' => $request->tax ?? 0.00,
            'tax_percent' => $request->tax_percent ?? 0.00,
            'grand_total' => $request->grand_total ?? 0.00,
            // 'schedule_date' => $request->date_of_cleaning,
            'time_of_cleaning' => $request->time_of_cleaning,
            'quotation_no' => $quotation_no,
            'remarks' => $request->quotation_remarks,
            'created_by' => Auth::user()->id,
            'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name
        ]);

        if($request->filled('date_of_cleaning'))
        {
            $quotationDetails->schedule_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('date_of_cleaning'))));
        }
        else
        {
            $quotationDetails->schedule_date = null;
        }

        // dd($quotationDetails);

        $quotationDetails->save();
        $quotationId = $quotationDetails->id;
        $total_amount = 0;

        $preview_service_id = $request->preview_service_id;
        $preview_service_product_code = $request->preview_service_product_code;
        $preview_service_name = $request->preview_service_name;
        $preview_service_desc = $request->preview_service_desc;
        $preview_service_qty = $request->preview_service_qty;
        $preview_service_unitPrice = $request->preview_service_unitPrice;
        $preview_service_discount = $request->preview_service_discount;
        $preview_service_total_session = $request->preview_service_total_session;

        for ($i = 0; $i < count($preview_service_id); $i++) {
            $db_service = Services::find($preview_service_id[$i]);

            if ($db_service) {
                $service_total_amount = $preview_service_unitPrice[$i] * $preview_service_qty[$i];
                $service_discount_amount = $service_total_amount * ($preview_service_discount[$i] / 100);
                $gross_amount = $service_total_amount - $service_discount_amount;

                $service = new QuotationServiceDetail();

                $service->quotation_id = $quotationId;
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

        if ($request->filled('preview_add_ons_service_name')) {
            $preview_add_ons_product_code = $request->preview_add_ons_product_code;
            $preview_add_ons_service_name = $request->preview_add_ons_service_name;
            $preview_add_ons_service_desc = $request->preview_add_ons_service_desc;
            $preview_add_ons_service_qty = $request->preview_add_ons_service_qty;
            $preview_add_ons_service_unitPrice = $request->preview_add_ons_service_unitPrice;
            $preview_add_ons_service_discount = $request->preview_add_ons_service_discount;

            for ($i = 0; $i < count($preview_add_ons_service_name); $i++) {
                $service_total_amount = $preview_add_ons_service_unitPrice[$i] * $preview_add_ons_service_qty[$i];
                $service_discount_amount = $service_total_amount * ($preview_add_ons_service_discount[$i] / 100);
                $gross_amount = $service_total_amount - $service_discount_amount;

                $service = new QuotationServiceDetail();

                $service->quotation_id = $quotationId;
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

        $discount_Type = $request->discount_type;

        if ($request->filled('add_tax_check')) {
            $tax = $request->tax;
            $tax_type = "exclusive";
        } else {
            // $tax = 0;
            $tax = $request->tax;
            $tax_type = "inclusive";
        }

        if ($discount_Type == "percentage") {
            $discount = $request->persentage_discount;
            $discount_amount = $total_amount * ($discount / 100);
        } else {
            $discount = $request->amount_discount;
            $discount_amount = $discount;
        }

        $total = $total_amount - $discount_amount;

        if ($tax_type == "exclusive") {
            $tax_amt = $total * $tax / 100;
            $grand_total = $total + $tax_amt;
        } else if ($tax_type == "inclusive") {
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

        return $quotationId;
    }

    // reject_quotation_mail

    public function reject_quotation_mail($quotation_id)
    {
        // return $quotation_id;

        $quotation = Quotation::find($quotation_id);

        if ($quotation)
        {
            if($quotation->status == 2 && $quotation->pending_customer_approval_status == 1)
            {
                $quotation->status = 5;
                $quotation->pending_customer_approval_status = 3;
                $quotation->save();

                // lead start

                $lead = Lead::where('id', $quotation->lead_id)->first();

                if($lead)
                {
                    $lead->status = 5;     
                    $lead->pending_customer_approval_status = 3;          
                    $lead->save();
                }

                // lead end

                // return "Lead Rejected successfully"; 
                $msg = "Your Quotation has been Rejected ❌! Please feel free to contact us for further assistance."; 
            }
            else if($quotation->pending_customer_approval_status == 2)
            {           
                // return "Lead already approved";           
                $msg = "The Quotation has already been Confirmed ✅! Please contact us if you need further assistance.";      
            }
            else if($quotation->pending_customer_approval_status == 3)
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

    // confirm_quotation_mail

    public function confirm_quotation_mail($quotation_id)
    {
        // return $quotation_id;

        $quotation = Quotation::find($quotation_id);

        if ($quotation)
        {
            if($quotation->status == 2 && $quotation->pending_customer_approval_status == 1)
            {
                // $quotation->status = 2;
                $quotation->pending_customer_approval_status = 2;
                $quotation->save();

                // lead start

                $lead = Lead::where('id', $quotation->lead_id)->first();

                if($lead)
                {
                    $lead->pending_customer_approval_status = 2;          
                    $lead->save();
                }

                // lead end
              
                // return "Lead Confirmed successfully"; 
                $msg = "Thank you for confirming our Quotation! Your booking is now being processed. 😊"; 
            }
            else if($quotation->pending_customer_approval_status == 1)
            {    
                $quotation->pending_customer_approval_status = 2;
                $quotation->save();

                // lead start

                $lead = Lead::where('id', $quotation->lead_id)->first();

                if($lead)
                {
                    $lead->pending_customer_approval_status = 2;          
                    $lead->save();
                }

                // lead end

                // return "Lead already approved";  
                $msg = "Thank you for confirming our Quotation! Your booking is now being processed. 😊"; 
            }
            else if($quotation->pending_customer_approval_status == 2)
            {           
                // return "Lead already approved";  
                $msg = "The Quotation has already been Confirmed ✅! Please contact us if you need further assistance.";               
            }
            else if($quotation->pending_customer_approval_status == 3)
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
}
