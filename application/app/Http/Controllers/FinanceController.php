<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\Company;
use App\Models\Crm;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\LeadPaymentInfo;
use App\Models\LeadSchedule;
use App\Models\LeadServices;
use App\Models\PaymentMethod;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use App\Models\ServiceAddress;
use App\Models\Services;
use App\Models\Tax;
use App\Models\TermCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    // view page

    public function index()
    {
        $data['emailTemplates'] = EmailTemplate::get();
        $data['company'] = Company::get();

        return view('admin.finance.index', $data);
    }

    // get table data

    public function get_table_data()
    {
        $quotation = Quotation::WhereNotNull('invoice_no')->orderBy('created_at', 'desc')->get();

        foreach($quotation as $item)
        {
            $customer = Crm::find($item->customer_id);

            if($customer)
            {
                $item->customer_type = $customer->customer_type;
                $item->customer_name = $customer->customer_name;
                $item->individual_company_name = $customer->individual_company_name;
            }
            else
            {
                $item->customer_type = "";
                $item->customer_name = "";
                $item->individual_company_name = "";
            }
        }

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

                $item->schedule_date = $item->schedule_date ? date('d-m-Y', strtotime($item->schedule_date)) : '';

                $action = '<div class="dropdown">
                                <button
                                    class="btn dropdown-toggle align-text-top t-btn"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    Actions
                                </button>
                                <div class="dropdown-menu dropdown-menu-end d-menu"
                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                    data-popper-placement="bottom-end">
                                    <a class="dropdown-item border-bottom" href="'.route('finance.view-invoice', $item->id).'">
                                        <i class="fa-solid fa-eye me-2 text-blue"></i>
                                        View Invoice
                                    </a>
                                    <a class="dropdown-item border-bottom" href="'.route('finance.edit', $item->id).'">
                                        <i class="fa-solid fa-edit me-2 text-blue"></i>
                                        Edit
                                    </a>
                                    <a class="dropdown-item border-bottom" href="'.route('finance.download-invoice', $item->id).'">
                                        <i class="fa-solid fa-download me-2 text-blue"></i>
                                        Download
                                    </a>';

                if($item->payment_status != "paid" && $balance > 0)
                {
                    $action .= '<a class="dropdown-item border-bottom" href="'.route('finance.make-payment', $item->id).'">
                                    <i class="fa-solid fa-calculator me-2 text-blue"></i>
                                    Make Payment
                                </a>';
                }

                $action .= '<a class="dropdown-item border-bottom" href="'.route('finance.log-report', $item->invoice_no).'">
                                <i class="fa-solid fa-file me-2 text-blue"></i>
                                Log Report
                            </a>
                            <a class="dropdown-item border-bottom send_mail_btn" href="#" data-quotation_id='.$item->id.' data-invoice_no='.$item->invoice_no.' data-customer_id='.$item->customer_id.' data-schedule_date='.$item->schedule_date.'>
                                <i class="fa fa-envelope me-2 text-blue"></i>
                                Send Mail
                            </a>
                        </div>
                    </div>';

                $new_data[] = [
                    $key+1,
                    $item->invoice_no,
                    $item->invoice_date ? date('d-m-Y', strtotime($item->invoice_date)) : '',
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

    // get table data by company

    public function get_table_data_by_company(Request $request)
    {
        $company_id = $request->company_id;

        $quotation = Quotation::where('company_id', $company_id)->WhereNotNull('invoice_no')->orderBy('created_at', 'desc')->get();

        foreach($quotation as $item)
        {
            $customer = Crm::find($item->customer_id);

            if($customer)
            {
                $item->customer_type = $customer->customer_type;
                $item->customer_name = $customer->customer_name;
                $item->individual_company_name = $customer->individual_company_name;
            }
            else
            {
                $item->customer_type = "";
                $item->customer_name = "";
                $item->individual_company_name = "";
            }
        }

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

                $item->schedule_date = $item->schedule_date ? date('d-m-Y', strtotime($item->schedule_date)) : '';

                $action = '<div class="dropdown">
                                <button
                                    class="btn dropdown-toggle align-text-top t-btn"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    Actions
                                </button>
                                <div class="dropdown-menu dropdown-menu-end d-menu"
                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                    data-popper-placement="bottom-end">
                                    <a class="dropdown-item border-bottom" href="'.route('finance.view-invoice', $item->id).'">
                                        <i class="fa-solid fa-eye me-2 text-blue"></i>
                                        View Invoice
                                    </a>
                                    <a class="dropdown-item border-bottom" href="'.route('finance.edit', $item->id).'">
                                        <i class="fa-solid fa-edit me-2 text-blue"></i>
                                        Edit
                                    </a>
                                    <a class="dropdown-item border-bottom" href="'.route('finance.download-invoice', $item->id).'">
                                        <i class="fa-solid fa-download me-2 text-blue"></i>
                                        Download
                                    </a>';

                if($item->payment_status != "paid" && $balance > 0)
                {
                    $action .= '<a class="dropdown-item border-bottom" href="'.route('finance.make-payment', $item->id).'">
                                    <i class="fa-solid fa-calculator me-2 text-blue"></i>
                                    Make Payment
                                </a>';
                }

                $action .= '<a class="dropdown-item border-bottom" href="'.route('finance.log-report', $item->invoice_no).'">
                                <i class="fa-solid fa-file me-2 text-blue"></i>
                                Log Report
                            </a>
                            <a class="dropdown-item border-bottom send_mail_btn" href="#" data-quotation_id='.$item->id.' data-invoice_no='.$item->invoice_no.' data-customer_id='.$item->customer_id.' data-schedule_date='.$item->schedule_date.'>
                                <i class="fa fa-envelope me-2 text-blue"></i>
                                Send Mail
                            </a>
                        </div>
                    </div>';

                $new_data[] = [
                    $key+1,
                    $item->invoice_no,
                    $item->invoice_date ? date('d-m-Y', strtotime($item->invoice_date)) : '',
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

    // view invoice

    // public function view_invoice($id)
    // {
    //     $lead = Lead::find($id);
    //     $lead_details = LeadServices::where('lead_id', $id)->get();

    //     $ServiceAddress = ServiceAddress::find($lead->service_address);

    //     if($ServiceAddress)
    //     {
    //         $lead->service_address_details = $ServiceAddress->address;
    //         $lead->service_address_unit_number = $ServiceAddress->unit_number;
    //     }
    //     else
    //     {
    //         $lead->service_address_details = "";
    //         $lead->service_address_details = $ServiceAddress->address;
    //     }

    //     // lead payment detail start

    //     $lead_payment_detail = LeadPaymentInfo::where('lead_id', $id)->get();

    //     $deposit = 0;
    //     $balance = 0;
    //     foreach($lead_payment_detail as $item)
    //     {
    //         $deposit += $item->payment_amount;
    //     }
    //     $balance = $lead->grand_total - $deposit;

    //     // lead payment detail end

    //     // calculation start

    //     $subtotal = 0;

    //     foreach($lead_details as $item)
    //     {
    //         $subtotal += $item->unit_price;
    //     }

    //     $nettotal = $lead->amount;

    //     if($lead->discount_type == "percentage")
    //     {
    //         $discount_amt = $nettotal * $lead->discount/100;
    //     }
    //     else
    //     {
    //         $discount_amt = $lead->discount;
    //     }

    //     $total = $nettotal - $discount_amt;

    //     $lead->subtotal = $subtotal;
    //     $lead->nettotal = $nettotal;
    //     $lead->discount_amt = $discount_amt;
    //     $lead->total = $total;
    //     $lead->deposit = $deposit;
    //     $lead->balance = $balance;

    //     // calculation end

    //     $company = Company::where('id', $lead->company_id)->first();

    //     if(!empty($company->company_logo))
    //     {
    //         $company->image_path = 'application/public/company_logos/' . $company->company_logo;
    //     }
    //     else
    //     {
    //         $company->image_path = "";
    //     }

    //     $customer = DB::table('customers')->where('id', $lead->customer_id)->first();
    //     $term_condition = TermCondition::where('company_id', $lead->company_id)->get();

    //     $data['lead'] = $lead;
    //     $data['lead_details'] = $lead_details;
    //     $data['company'] = $company;
    //     $data['customer'] = $customer;
    //     $data['term_condition'] = $term_condition;

    //     return view('admin.finance.view-tax-invoice', $data);
    // }

    public function view_invoice($id)
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
            $company->image_path = 'application/public/company_logos/' . $company->company_logo;
        }
        else
        {
            $company->image_path = "";
        }

        if(!empty($company->qr_code))
        {
            $company->qr_code_path = "application/public/qr_code/$company->qr_code";
        }
        else
        {
            $company->qr_code_path = "";
        }

        // invoice footer logo start

        $company_invoice_footer_logo = DB::table('company_invoice_footer_logo')
                                            ->where('company_id', $quotation->company_id)
                                            ->get();

        foreach($company_invoice_footer_logo as $item)
        {          
            $item->invoice_footer_logo_path = 'application/public/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;            
        }      

        $company->company_invoice_footer_logo = $company_invoice_footer_logo;

        // invoice footer logo end

        // payment terms start

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

        // payment terms end

        $term_condition = TermCondition::where('company_id', $quotation->company_id)->get();

        $sales_order = SalesOrder::where('quotation_id', $id)->first();
        $schedule = ScheduleModel::where('sales_order_id', $sales_order->id)->first();

        $new_sch_date_arr = [];

        if($schedule)
        {
            $schedule_details = ScheduleDetails::where('sales_order_id', $sales_order->id)->get();

            if(!$schedule_details->isEmpty())
            {
                foreach($schedule_details as $item)
                {
                    if(!empty($item->employee_id))
                    {
                        $new_sch_date_arr[] = date('d/m', strtotime($item->schedule_date));
                    }
                }
            }

            // no of cleaners start

            if($schedule->cleaner_type == "team")
            {
                $temp_sch_dt = ScheduleDetails::where('sales_order_id', $sales_order->id)
                                                    ->whereNotNull('employee_id')
                                                    ->groupBy('employee_id')
                                                    ->get();
                $emp_arr = [];

                foreach($temp_sch_dt as $item)
                {
                    $xin_team = DB::table('xin_team')->where('team_id', $item->employee_id)->first();
                    $temp_arr = explode(',', $xin_team->employee_id);

                    foreach($temp_arr as $te)
                    {
                        if(!empty($te))
                        {
                            if (in_array($te, $emp_arr)) 
                            { } 
                            else 
                            { 
                                $emp_arr[] = $te;
                            } 
                        }              
                    }
                }

                $no_of_cleaners = count($emp_arr);
            }
            else if($schedule->cleaner_type == "individual")
            {
                $no_of_cleaners = $schedule->man_power;
            }
            else
            {
                $no_of_cleaners = "";
            }

            // no of cleaners end

            // cleaners name start

            $schedule_emp_arr = DB::table('tble_schedule_employee')
                                        ->where('sales_order_id', $sales_order->id)
                                        ->groupBy('employee_id')
                                        ->pluck('employee_id')
                                        ->toarray();

            if($schedule->cleaner_type == "team")
            {
                $xin_team = DB::table('xin_team')->whereIn('team_id', $schedule_emp_arr)->get();

                $xin_employees_id = [];

                foreach($xin_team as $loop_team)
                {
                    $temp_emp_id = explode(',', $loop_team->employee_id);

                    foreach($temp_emp_id as $loop_temp)
                    {
                        if(!in_array($loop_temp, $xin_employees_id))
                        {
                            $xin_employees_id[] = $loop_temp;
                        }
                    }                
                }

                $xin_employees = DB::table('xin_employees')->whereIn('user_id', $xin_employees_id)->get();

                $xin_employees_arr = [];

                foreach($xin_employees as $emp)
                {
                    $xin_employees_arr[] = $emp->first_name . " " . $emp->last_name;
                }

                $cleaner_name = implode(", ", $xin_employees_arr);
                
            }
            else if($schedule->cleaner_type == "individual")
            {    
                $xin_employees = DB::table('xin_employees')->whereIn('user_id', $schedule_emp_arr)->get();

                $xin_employees_arr = [];

                foreach($xin_employees as $emp)
                {
                    $xin_employees_arr[] = $emp->first_name . " " . $emp->last_name;
                }

                $cleaner_name = implode(", ", $xin_employees_arr);
            }
            else
            {
                $cleaner_name = "";
            }

            // cleaners name end

            $schedule->new_selected_days = str_replace(",", ", ", $schedule->selected_days);
            $schedule->cleaning_time = date('h:ia', strtotime($schedule->startTime)) . " - " . date('h:ia', strtotime($schedule->endTime));
            $schedule->new_cleaning_dates = implode(', ', $new_sch_date_arr);

            $schedule->no_of_cleaners = $no_of_cleaners;
            $schedule->cleaner_name = $cleaner_name;
        }

        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;
        $data['schedule'] = $schedule;

        // return $data;

        return view('admin.finance.view-tax-invoice', $data);
    }

    public function edit($id)
    {
        $quotation = Quotation::find($id);
        $quotation_details = QuotationServiceDetail::where('quotation_id', $id)->get();

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

        $emailTemplates = EmailTemplate::get();

        // tax

        // $tax = Tax::first();

        $today_date = date('Y-m-d');
        $tax = Tax::whereDate('from_date', '<=', $today_date)
                    ->whereDate('to_date', '>=', $today_date)
                    ->first();

        return view('admin.finance.edit', compact('quotation', 'quotation_details', 'customer', 'tax', 'companyList', 'addresses', 'billingaddresses', 'dates', 'emailTemplates'));
    }

    public function update_invoice_send_mail(Request $request)
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

        $quotation_id = $this->temp_quotation_update($request);

        // quotation start

        $quotation = Quotation::find($quotation_id);
        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

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

        // lead payment detail start

        $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $quotation_id)->get();

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

        $company = Company::where('id', $request->company_id)->first();

        if(!empty($company->company_logo))
        {
            $company->image_path = "/company_logos/$company->company_logo";
        }
        else
        {
            $company->image_path = "";
        }

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

        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $files = Pdf::loadView('admin.quotation.tax-invoice', $data);
        $file_name = $quotation->invoice_no.".pdf";

        Mail::send('emails.email', $data, function ($message) use ($data, $files, $file_name) {
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

    public function update_invoice(Request $request)
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

        $quotation_id = $this->temp_quotation_update($request);

        $quotation = Quotation::find($quotation_id);

        // log data store start

        LogController::store('Invoice', 'Invoice Updated', $quotation->invoice_no);

        // log data store end

        return response()->json(['success' => 'Invoice updated successfully!']);
    }

    public function temp_quotation_update($request)
    {
        $quotation_id = $request->quotation_id;

        // quotation start

        $quotation = Quotation::where('id', $quotation_id)->first();
        $quotation->customer_id = $request->customer_id;
        $quotation->company_id = $request->company_id;
        $quotation->service_address = $request->input('service-address-radio');
        $quotation->billing_address = $request->input('billing-address-radio');

        if($request->filled('schedule_date'))
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

        return $quotation_id;
    }

    public function download_invoice($id)
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

        // invoice footer logo start

        $company_invoice_footer_logo = DB::table('company_invoice_footer_logo')
                                            ->where('company_id', $quotation->company_id)
                                            ->get();

        foreach($company_invoice_footer_logo as $item)
        {          
            $item->invoice_footer_logo_path = '/uploads/invoice_footer_logo/' . $item->invoice_footer_logo;            
        }                                  

        $company->company_invoice_footer_logo = $company_invoice_footer_logo;

        // invoice footer logo end

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

        $sales_order = SalesOrder::where('quotation_id', $id)->first();
        $schedule = ScheduleModel::where('sales_order_id', $sales_order->id)->first();

        $new_sch_date_arr = [];

        if($schedule)
        {
            $schedule_details = ScheduleDetails::where('sales_order_id', $sales_order->id)->get();

            if(!$schedule_details->isEmpty())
            {
                foreach($schedule_details as $item)
                {
                    if(!empty($item->employee_id))
                    {
                        $new_sch_date_arr[] = date('d/m', strtotime($item->schedule_date));
                    }
                }
            }

            // no of cleaners start

            if($schedule->cleaner_type == "team")
            {
                $temp_sch_dt = ScheduleDetails::where('sales_order_id', $sales_order->id)
                                                    ->whereNotNull('employee_id')
                                                    ->groupBy('employee_id')
                                                    ->get();
                $emp_arr = [];

                foreach($temp_sch_dt as $item)
                {
                    $xin_team = DB::table('xin_team')->where('team_id', $item->employee_id)->first();
                    $temp_arr = explode(',', $xin_team->employee_id);

                    foreach($temp_arr as $te)
                    {
                        if(!empty($te))
                        {
                            if (in_array($te, $emp_arr)) 
                            { } 
                            else 
                            { 
                                $emp_arr[] = $te;
                            } 
                        }              
                    }
                }

                $no_of_cleaners = count($emp_arr);
            }
            else
            {
                $no_of_cleaners = $schedule->man_power;
            }

            // no of cleaners end

            // cleaners name start

            $schedule_emp_arr = DB::table('tble_schedule_employee')
                                        ->where('sales_order_id', $sales_order->id)
                                        ->groupBy('employee_id')
                                        ->pluck('employee_id')
                                        ->toarray();

            if($schedule->cleaner_type == "team")
            {
                $xin_team = DB::table('xin_team')->whereIn('team_id', $schedule_emp_arr)->get();

                $xin_employees_id = [];

                foreach($xin_team as $loop_team)
                {
                    $temp_emp_id = explode(',', $loop_team->employee_id);

                    foreach($temp_emp_id as $loop_temp)
                    {
                        if(!in_array($loop_temp, $xin_employees_id))
                        {
                            $xin_employees_id[] = $loop_temp;
                        }
                    }                
                }

                $xin_employees = DB::table('xin_employees')->whereIn('user_id', $xin_employees_id)->get();

                $xin_employees_arr = [];

                foreach($xin_employees as $emp)
                {
                    $xin_employees_arr[] = $emp->first_name . " " . $emp->last_name;
                }

                $cleaner_name = implode(", ", $xin_employees_arr);
                
            }
            else if($schedule->cleaner_type == "individual")
            {    
                $xin_employees = DB::table('xin_employees')->whereIn('user_id', $schedule_emp_arr)->get();

                $xin_employees_arr = [];

                foreach($xin_employees as $emp)
                {
                    $xin_employees_arr[] = $emp->first_name . " " . $emp->last_name;
                }

                $cleaner_name = implode(", ", $xin_employees_arr);
            }
            else
            {
                $cleaner_name = "";
            }

            // cleaners name end

            $schedule->new_selected_days = str_replace(",", ", ", $schedule->selected_days);
            $schedule->cleaning_time = date('h:ia', strtotime($schedule->startTime)) . " - " . date('h:ia', strtotime($schedule->endTime));
            $schedule->new_cleaning_dates = implode(', ', $new_sch_date_arr);

            $schedule->no_of_cleaners = $no_of_cleaners;
            $schedule->cleaner_name = $cleaner_name;
        }

        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;
        $data['schedule'] = $schedule;

        $pdf = PDF::loadView('admin.finance.download-tax-invoice', $data);

        return $pdf->stream($quotation->invoice_no.'.pdf');
        // return $pdf->download($quotation->invoice_no.'.pdf');
    }

    public function preview(Request $request)
    {
        // return $request->all();

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

        // quotation

        $quotation_id = $request->quotation_id;
        $quotation = Quotation::find($quotation_id);

        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;
        $data['route'] = $route;
        $data['imagePath'] = $imagePath;
        $data['service_address'] = $service_address;
        $data['quotation'] = $quotation;

        return view('admin.finance.preview', $data);
    }

    // view make payment

    public function make_payment($quotation_id)
    {
        $data['quotation'] = Quotation::leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')
                                ->where('quotations.payment_status', '!=', 'paid')
                                ->WhereNotNull('invoice_no')
                                ->where('quotations.id', $quotation_id)
                                ->select('quotations.*', 'customers.customer_name as customer_name', 'customers.mobile_number as mobile_number', 'customers.email as email', 'customers.customer_type', 'customers.individual_company_name')
                                ->first();

        $total_amount = $data['quotation']->grand_total;

        // open amount start

        $lead_payment_details = LeadPaymentInfo::where('customer_id', $data['quotation']->customer_id)
                                                ->where('quotation_id', $data['quotation']->id)
                                                ->get();
        $payment_amount = 0;
        foreach($lead_payment_details as $list)
        {
            if($list->payment_status == 1)
            {
                $payment_amount += $list->payment_amount;
            }
        }

        if($total_amount == $payment_amount)
        {
            $open_amount = 0;
        }
        else if($total_amount > $payment_amount)
        {
            $open_amount = $total_amount - $payment_amount;
        }
        else
        {
            $open_amount = 0;
        }

        // open amount end

        $data['quotation']->open_amount = $open_amount;
        
        // payment method
        $data['offline_payment_method'] = PaymentMethod::where('payment_method', "Offline")->get();

        $data['emailTemplates'] = EmailTemplate::get();

        // return $data;

        return view('admin.finance.make-payment', $data);
    }

    // store make payment

    public function store_make_payment(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'payment_proof' => 'nullable',
                'pay_amount' => 'required'
            ],
            [],
            [
                'payment_proof' => "Payment Proof",
                'pay_amount' => 'Payment Amount'
            ]
        );

        if($validator->fails())
        {
            $error = $validator->errors();

            return response()->json(['status' => "error", 'error'=>$error]);
        }
        else
        {
            if($request->total_amount > 0)
            {          
                $get_quotation_data = Quotation::find($request->quotation_id);

                $this->temp_store_make_payment($request);

                // log data store start

                LogController::store('finance', 'Payment Received', $get_quotation_data->invoice_no);
                LogController::store('payment', 'Payment Received', $get_quotation_data->id);

                // log data store end

                $msg = "Payment received successfully for Invoice: " . $get_quotation_data->invoice_no . ", Amount: $" . number_format($request->pay_amount, 2) . ", Date: " . date('d-m-Y');
 
                return response()->json(['status'=>'success', 'message'=>$msg]);      
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Total Payment amount must be greater than 0']);
            }
        }
    }

    // store make payment and send email

    public function store_make_payment_send_email(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'payment_proof' => 'nullable',
                'pay_amount' => 'required'
            ],
            [],
            [
                'payment_proof' => "Payment Proof",
                'pay_amount' => 'Payment Amount'
            ]
        );

        if($validator->fails())
        {
            $error = $validator->errors();

            return response()->json(['status' => "error", 'error'=>$error]);
        }
        else
        {
            if($request->total_amount > 0)
            {            
                $get_quotation_data = Quotation::find($request->quotation_id);

                $this->temp_store_make_payment($request);


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

                $data['quotation_id'] = $quotation->id;
                $data['quotation'] = $quotation;
                $data['quotation_details'] = $quotation_details;
                $data['company'] = $company;
                $data['customer'] = $customer;
                $data['term_condition'] = $term_condition;

                try {    
                    
                    $files = Pdf::loadView('admin.quotation.tax-invoice', $data);
                    $file_name = $quotation->invoice_no.".pdf";

                    Mail::send('emails.received-payment-mail', $data, function ($message) use ($data, $files, $file_name) {
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

                LogController::store('finance', 'Payment Received and Mail Send', $get_quotation_data->invoice_no);
                LogController::store('payment', 'Payment Received and Mail Send', $get_quotation_data->id);

                // log data store end

                $msg = "Payment received successfully for Invoice: " . $get_quotation_data->invoice_no . ", Amount: $" . number_format($request->pay_amount, 2) . ", Date: " . date('d-m-Y');
                
                return response()->json(['status'=>'success', 'message'=>$msg]);      
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Total Payment amount must be greater than 0']);
            }
        }
    }

    public static function temp_store_make_payment($request)
    {
        $quotation_id = $request->quotation_id;
        $lead_id = $request->lead_id;

        if($request->pay_amount != 0)
        {
            $get_quotation_data = Quotation::find($quotation_id);

            if($request->hasFile('payment_proof'))
            {
                $image = $request->file('payment_proof');

                $ext = $image->extension();

                $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                $image->move(public_path('uploads/payment_proof'), $payment_proof_file);                           
            }

            $LeadPaymentInfo = new LeadPaymentInfo();  
            $LeadPaymentInfo->lead_id = $lead_id;
            $LeadPaymentInfo->quotation_id = $quotation_id;
            $LeadPaymentInfo->customer_id =  $get_quotation_data->customer_id;
            $LeadPaymentInfo->payment_method = $request->payment_method;
            $LeadPaymentInfo->payment_amount = $request->pay_amount;
            $LeadPaymentInfo->created_at = Carbon::now();
            $LeadPaymentInfo->updated_at = Carbon::now();
            $LeadPaymentInfo->created_by_id = Auth::user()->id;
            $LeadPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;
            $LeadPaymentInfo->payment_remarks = $request->payment_remarks;        
            $LeadPaymentInfo->save();

            if($request->hasFile('payment_proof'))
            {
                $insert_payment_proof = [
                    'payment_id' => $LeadPaymentInfo->id,
                    "lead_id" => $lead_id,
                    "quotation_id" => $quotation_id,
                    "payment_proof" =>  $payment_proof_file ?? '',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                
                DB::table('payment_proof')->insert($insert_payment_proof);
            }
        
            // update laed start

            $quotation_grand_total = $get_quotation_data->grand_total;

            $get_lead_payment_details = LeadPaymentInfo::where('quotation_id', $quotation_id)->get();

            $lead_payment_amount = 0;
            foreach($get_lead_payment_details as $list)
            {
                if($list->payment_status == 1)
                {
                    $lead_payment_amount += $list->payment_amount;
                }
            }

            if($quotation_grand_total == $lead_payment_amount)
            {
                $payment_status = "paid";
            }
            else
            {
                $payment_status = "partial_paid";
            }

            // invoice no
            if(empty($get_quotation_data->invoice_no))
            {                     
                $quotation_no = $get_quotation_data->quotation_no;
                $temp_arr = explode("-", $quotation_no);
                $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                $invoice_no = implode("-", $temp_arr);

                $get_quotation_data->invoice_no = $invoice_no;
            }

            $get_quotation_data->payment_status = $payment_status;
            $get_quotation_data->save();

            if(Lead::find($lead_id))
            {          
                $get_lead_data = Lead::find($lead_id);
                
                // invoice no
                if(empty($get_lead_data->invoice_no))
                {
                    $get_lead_data->invoice_no = $invoice_no;
                }

                $get_lead_data->payment_status = $payment_status;
                $get_lead_data->save();
            }

            // update laed end
        }   
    }

    // log report

    public function log_report($invoice_no)
    {
        $data['invoice_no'] = $invoice_no;

        $log_details = DB::table('log_details')
                        ->whereIn('module', ['invoice', 'finance'])
                        ->where('ref_no', $invoice_no)
                        ->paginate(30);

        $data['log_details'] = $log_details;

        return view('admin.finance.log-report', $data);
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

        $sales_order = SalesOrder::where('quotation_id', $quotation->id)->first();
        $schedule = ScheduleModel::where('sales_order_id', $sales_order->id)->first();

        $new_sch_date_arr = [];

        if($schedule)
        {
            $schedule_details = ScheduleDetails::where('sales_order_id', $sales_order->id)->get();

            if(!$schedule_details->isEmpty())
            {
                foreach($schedule_details as $item)
                {
                    if(!empty($item->employee_id))
                    {
                        $new_sch_date_arr[] = date('d/m', strtotime($item->schedule_date));
                    }
                }
            }

            // no of cleaners start

            if($schedule->cleaner_type == "team")
            {
                $temp_sch_dt = ScheduleDetails::where('sales_order_id', $sales_order->id)
                                                    ->whereNotNull('employee_id')
                                                    ->groupBy('employee_id')
                                                    ->get();
                $emp_arr = [];

                foreach($temp_sch_dt as $item)
                {
                    $xin_team = DB::table('xin_team')->where('team_id', $item->employee_id)->first();
                    $temp_arr = explode(',', $xin_team->employee_id);

                    foreach($temp_arr as $te)
                    {
                        if(!empty($te))
                        {
                            if (in_array($te, $emp_arr)) 
                            { } 
                            else 
                            { 
                                $emp_arr[] = $te;
                            } 
                        }              
                    }
                }

                $no_of_cleaners = count($emp_arr);
            }
            else
            {
                $no_of_cleaners = $schedule->man_power;
            }

            // no of cleaners end

            // cleaners name start

            $schedule_emp_arr = DB::table('tble_schedule_employee')
                                        ->where('sales_order_id', $sales_order->id)
                                        ->groupBy('employee_id')
                                        ->pluck('employee_id')
                                        ->toarray();

            if($schedule->cleaner_type == "team")
            {
                $xin_team = DB::table('xin_team')->whereIn('team_id', $schedule_emp_arr)->get();

                $xin_employees_id = [];

                foreach($xin_team as $loop_team)
                {
                    $temp_emp_id = explode(',', $loop_team->employee_id);

                    foreach($temp_emp_id as $loop_temp)
                    {
                        if(!in_array($loop_temp, $xin_employees_id))
                        {
                            $xin_employees_id[] = $loop_temp;
                        }
                    }                
                }

                $xin_employees = DB::table('xin_employees')->whereIn('user_id', $xin_employees_id)->get();

                $xin_employees_arr = [];

                foreach($xin_employees as $emp)
                {
                    $xin_employees_arr[] = $emp->first_name . " " . $emp->last_name;
                }

                $cleaner_name = implode(", ", $xin_employees_arr);
                
            }
            else if($schedule->cleaner_type == "individual")
            {    
                $xin_employees = DB::table('xin_employees')->whereIn('user_id', $schedule_emp_arr)->get();

                $xin_employees_arr = [];

                foreach($xin_employees as $emp)
                {
                    $xin_employees_arr[] = $emp->first_name . " " . $emp->last_name;
                }

                $cleaner_name = implode(", ", $xin_employees_arr);
            }
            else
            {
                $cleaner_name = "";
            }

            // cleaners name end

            $schedule->new_selected_days = str_replace(",", ", ", $schedule->selected_days);
            $schedule->cleaning_time = date('h:ia', strtotime($schedule->startTime)) . " - " . date('h:ia', strtotime($schedule->endTime));
            $schedule->new_cleaning_dates = implode(', ', $new_sch_date_arr);

            $schedule->no_of_cleaners = $no_of_cleaners;
            $schedule->cleaner_name = $cleaner_name;
        }

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
        $data['schedule'] = $schedule;

        $files = Pdf::loadView('admin.finance.download-tax-invoice', $data);
        $file_name = $quotation->invoice_no.".pdf";

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

        LogController::store('finance', 'Mail Send', $quotation->invoice_no);

        // log data store end
        
        return response()->json(['status'=>'success', 'message'=>'Mail send successfully']);    
    }
}
