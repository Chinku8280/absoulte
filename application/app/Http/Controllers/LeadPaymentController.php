<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Crm;
use Illuminate\Http\Request;
use App\Models\LeadPaymentInfo;
use App\Models\SalesOrder;
use GuzzleHttp\Client;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendLeadEmail;
use App\Models\BillingAddress;
use App\Models\Company;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\LeadServices;
use App\Models\PaymentHistory;
use App\Models\PaymentMethod;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\ServiceAddress;
use App\Models\TermCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Raw;

class LeadPaymentController extends Controller
{
    // public function send_payment(Request $request)
    // {

    //     $companyList = Company::get();
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

    //     // echo"<pre>"; print_r($leadId); exit;
    //     $lead = Lead::select('lead_customer_details.*', 'lead_payment_detail.*', 'lead_price_info.*')
    //         ->join('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
    //         ->join('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
    //         ->where('lead_customer_details.id', $leadId)
    //         ->first();
    //     // echo"<pre>"; print_r($lead); exit;
    //     $customerId = $lead->customer_id;
    //     $customer = Crm::join('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
    //         ->join('service_address', 'customers.id', '=', 'service_address.customer_id')
    //         ->leftJoin('zone_settings as zs', function ($join) {
    //             $join->whereRaw('FIND_IN_SET(LEFT(service_address.postal_code, 2) COLLATE utf8mb4_general_ci, REPLACE(zs.postal_code, " ", "") COLLATE utf8mb4_general_ci)');
    //         })
    //         ->select(
    //             'customers.*',
    //             'customers.id as customer_id',
    //             'language_spoken.language_name as language_name',
    //             'service_address.*',
    //             'zs.zone_color' // Include the zone_color from the joined table
    //         )
    //         ->where('customers.id', $customerId)
    //         ->first();
    //     // dd($customer);
    //     // echo"<pre>"; print_r($customer); exit;
    //     $addresses = ServiceAddress::where('customer_id', $customerId)->get();
    //     $billingaddresses = BillingAddress::where('customer_id', $customerId)->get();

    //     $service = LeadServices::where('lead_id', $leadId)->get();
    //    // dd($service);
    //     $asiaOptions = PaymentMethod::where('payment_method', "Asia Pay")->get();
    //     $offlineOptions = PaymentMethod::where('payment_method', "Offline")->get();

    //     return view('admin.leads.payment', compact('lead', 'customer', 'asiaOptions', 'offlineOptions', 'service', 'leadprice', 'dates', 'companyList', 'emailTemplates', 'subtotal', 'discount', 'nettotal'));
    // }

    // view send payment advice

    public function send_payment(Request $request)
    {
        $leadId = $request->input('lead_id');

        // $lead = Lead::select('lead_customer_details.*', 'lead_price_info.*')
        //             ->leftJoin('lead_payment_detail', 'lead_customer_details.id', '=', 'lead_payment_detail.lead_id')
        //             ->leftJoin('lead_price_info', 'lead_customer_details.id', '=', 'lead_price_info.lead_id')
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

        // end

        $asiaOptions = PaymentMethod::where('payment_method', "Asia Pay")->get();
        $offlineOptions = PaymentMethod::where('payment_method', "Offline")->get();

        $emailTemplates = EmailTemplate::get();

        $term_condition = TermCondition::where('company_id', $lead->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        // invoice no
        $quotation_no = $lead->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $lead->temp_invoice_no = implode("-", $temp_arr);

        // return $lead;

        return view('admin.leads.payment', compact('leadId', 'lead', 'customer', 'company', 'asiaOptions', 'offlineOptions', 'service', 'emailTemplates', 'service_address', 'billing_address', 'term_condition', 'imagePath'));
    }

    // send payment advice (asia pay)

    public function processPayment(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'leadId' => 'required',
                'payment_amount' => 'required',
                'payment_option' => 'required',
            ],
            [],
            [
                'leadId' => 'Lead Id',
                'payment_amount' => 'Payment Amount',
                'payment_option' => 'Payment Option',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Lead::where('id', $request->leadId)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['status'=>'failed', 'message' => 'Lead already Approved']);
        }

        $lead_id = $request->leadId;
        $lead = Lead::find($lead_id);

        $total_amount = $lead->grand_total;

        $payment_method = $request->payment_option;
        $payment_amount = $request->payment_amount;

        if($total_amount == $payment_amount)
        {
            $type = "full";
        }
        else
        {
            $type = "advance";
        }


        // Validate your request data here

        // Your existing email sending logic
        $paymentLink = $this->generatePaymentLink($request);

        // Send email synchronously
        $result = $this->sendPaymentEmail($request, $paymentLink);

        if ($result === true)
        {
            $lead = Lead::find($lead_id);
            $lead->status = 3;
            $lead->payment_advice = 2;
            $lead->save();

            $quotation = Quotation::where('lead_id',$lead_id)->first();
            $quotation->status = 3;
            $quotation->payment_advice = 2;
            $quotation->save();

            // log data store start

            LogController::store('lead', 'Payment Advice Send', $lead_id);
            LogController::store('quotation', 'Payment Advice Send from lead', $quotation->id);

            // log data store end

            return response()->json(['status'=>'success', 'message'=>'Payment link send successfully', 'payment_link' => $paymentLink]);
        }
        else {
            // return response()->json(['error' => 'Error sending payment email']);
            return response()->json(['status'=>'failed', 'message'=>'Error sending payment email']);
        }
    }


    public function generatePaymentLink(Request $request)
    {
        $lead_id = $request->leadId;
        $lead = Lead::find($lead_id);

        $quotation = Quotation::where('lead_id', $lead_id)->first();
        $quotation_id = $quotation->id;

        $customer_id = $lead->customer_id;

        $total_amount = $lead->grand_total;

        $payment_method = $request->payment_option;
        $payment_amount = $request->payment_amount;

        if($total_amount == $payment_amount)
        {
            $type = "full";
        }
        else
        {
            $type = "advance";
        }

        $payment_amount = $request->payment_amount;

        // invoice no
        $quotation_no = $lead->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $quotation->invoice_no = implode("-", $temp_arr);

        $payDollarUrl = 'https://test.paydollar.com/b2cDemo/eng/merchant/api/DirectPaymentLinkApi.jsp';

        $merchantId = '12109219';
        $merchantApiId = 'apicss';
        $password = 'css2108';

        // link expire date
        $today = Carbon::now(); // Get the current date and time
        $nextMonth = $today->addMonth()->toDateString(); // Add one month

        // Payment parameters
        $paymentData = [
            'merchantId' => $merchantId,
            'merchantApiId' => $merchantApiId,
            'password' => $password,
            'actionType' => 'Generate',
            'currCode' => '702', // Replace with the desired currency code
            'payType' => 'N', // Normal Payment
            'amt' => $payment_amount, // Replace with the actual amount
            'payMethod' => 'ALL', // All available payment methods
            'orderRef' => $quotation_no, // Replace with your order reference logic
            'lang' => 'E', // English language
            // 'successUrl' => "www.google.com", // Replace with your success URL
            'successUrl' => route('lead.payment-success-response', [
                'lead_id' => $lead_id,
                'quotation_id' => $quotation_id,
                'total_amount' => $total_amount,
                'payment_method' => $payment_method,
                'payment_amount' => $payment_amount,
                'type' => $type,
                'customer_id' => $customer_id
            ]), // Replace with your success URL
            'failUrl' => route('lead.payment-failed-response'), // Replace with your fail URL
            'cancelUrl' => route('lead.payment-cancel-response'), // Replace with your cancel URL
            'eMonth' => date('m', strtotime($nextMonth)), // Replace with the expiration month
            'eDay' => date('d', strtotime($nextMonth)), // Replace with the expiration day
            'eYear' => date('Y', strtotime($nextMonth)), // Replace with the expiration year
            'status' => 'A', // Active
            'returnQR' => 'T', // Return QR Code base64 encoded string
            'remark' => '', // Optional remark
            'installment_service' => 'F', // Optional, disable installment
            'installOnly' => 'F', // Optional, disable installment only
            'installment_period' => '', // Optional, specify installment period if needed
        ];

        $client = new Client();

        $response = $client->get($payDollarUrl, [
            'query' => $paymentData,
        ]);



        $payDollarResponse = simplexml_load_string($response->getBody());


        if (isset($payDollarResponse->resultCode, $payDollarResponse->dplId, $payDollarResponse->dplUrl)) {
            $resultCode = (int)$payDollarResponse->resultCode;

            if ($resultCode === 0) {

                $directPaymentLink = (string)$payDollarResponse->dplUrl;
                return response()->json(['payment_link' => $directPaymentLink]);
            } else {

                return response()->json(['error' => 'Error generating payment link']);
            }
        } else {

            return response()->json(['error' => 'Error generating payment link: Unexpected response structure']);
        }
    }

    // private function sendPaymentEmail(Request $request, $directPaymentLink)
    // {
    //     try {

    //         Mail::to($request->input('hello@gmail.com'))
    //             ->cc('cc@example.com')
    //             ->bcc('bcc@example.com')
    //             ->send(new InvoiceMail([
    //                 'payment_link' => $directPaymentLink,
    //                 'other_data' => $request->all(),
    //             ]));

    //         return true;
    //     } catch (\Exception $e) {

    //         Log::error('Error sending payment email: ' . $e->getMessage());

    //         return false;
    //     }
    // }

    private function sendPaymentEmail(Request $request, $directPaymentLink)
    {
        $payment_amount = $request->payment_amount;

        // lead start

        $lead_id = $request->leadId;
        $lead = Lead::find($lead_id);
        $lead_details = LeadServices::where('lead_id', $lead_id)->get();

        // lead end

        // quotation start

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

        // $deposit = $payment_amount;
        // $balance = $grand_total - $deposit;

        $deposit = PaymentController::get_deposit_amount($quotation->id);
        $balance = PaymentController::get_balance_amount($quotation->id);

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

        $data["payment_link"] = $directPaymentLink;
        $data['payment_amount'] = $payment_amount;

        $data['lead_id'] = $lead_id;
        $data['lead'] = $lead;
        $data['lead_details'] = $lead_details;
        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $data['send_payment_advice'] = true;

        // invoice no
        $quotation_no = $lead->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $quotation->invoice_no = implode("-", $temp_arr);

        try {

            $files = Pdf::loadView('admin.leads.tax-invoice', $data);
            $file_name = $quotation->invoice_no.".pdf";

            if($request->filled('check_attach_invoice'))
            {
                Mail::send('admin.leads.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
                        // $message->from(Auth::user()->email);
                        $message->to($data['to_email'])
                                ->cc($data['cc'] ?? [])
                                ->bcc($data['bcc'] ?? [])
                                ->subject($data['subject'])
                                ->attachData($files->output(), $file_name);
                });
            }
            else
            {
                Mail::send('admin.leads.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
                        // $message->from(Auth::user()->email);
                        $message->to($data['to_email'])
                                ->cc($data['cc'] ?? [])
                                ->bcc($data['bcc'] ?? [])
                                ->subject($data['subject']);
                });
            }

            return true;
        }
        catch (\Exception $e) {

            return $e;
            Log::error('Error sending payment email: ' . $e->getMessage());

            return false;
        }

        // mail send end
    }

    // store asia pay payment

    public function payment_success_response(Request $request)
    {
        // return $request->all();

        if(!SalesOrder::where('quotation_id', $request->quotation_id)->exists())
        {
            if($request->type == "full")
            {
                $payment_status = "paid";
            }
            else
            {
                $payment_status = "partial_paid";
            }

            $customer = Crm::find($request->customer_id);

            // lead payment detail start

            $leadPaymentInfo = new LeadPaymentInfo();
            $leadPaymentInfo->lead_id = $request->lead_id;
            $leadPaymentInfo->quotation_id = $request->quotation_id;
            $leadPaymentInfo->customer_id = $request->customer_id;
            $leadPaymentInfo->payment_method = $request->payment_method;
            $leadPaymentInfo->payment_amount = $request->payment_amount;
            $leadPaymentInfo->payment_type = $request->type;
            $leadPaymentInfo->ref = $request->Ref;
            $leadPaymentInfo->total_amount = $request->total_amount;
            $leadPaymentInfo->created_by_id = $request->customer_id;
            $leadPaymentInfo->created_by_name = $customer->customer_name ?? '';
            $leadPaymentInfo->created_by_type = "customer";

            $leadPaymentInfo->save();

            // lead payment detail end

            $lead = Lead::find($request->lead_id);

            // invoice no
            $quotation_no = $lead->quotation_no;
            $temp_arr = explode("-", $quotation_no);
            $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
            $invoice_no = implode("-", $temp_arr);

            // lead

            $lead->status = 4;
            $lead->invoice_no = $invoice_no;
            $lead->invoice_date = Carbon::now();
            $lead->payment_status = $payment_status;
            $lead->save();

            // quotation

            $quotation = Quotation::where('lead_id', $request->lead_id)->first();
            $quotation->payment_status = $payment_status;
            $quotation->invoice_no = $invoice_no;
            $quotation->invoice_date = Carbon::now();
            $quotation->status = 4;
            $quotation->save();

            // Create sales order

            $SalesOrder = new SalesOrder();
            $SalesOrder->sales_order_no = rand(12334, 99999);
            $SalesOrder->customer_id = $quotation->customer_id;
            $SalesOrder->company_id = $quotation->company_id;
            $SalesOrder->quotation_id = $quotation->id;
            $SalesOrder->lead_id = $quotation->lead_id;
            $SalesOrder->invoice_no = $quotation->invoice_no;
            $SalesOrder->invoice_date = Carbon::now();
            $SalesOrder->status = 0;
            $SalesOrder->created_by_id = $request->customer_id;
            $SalesOrder->created_by_name = $customer->customer_name ?? '';
            $SalesOrder->created_by_type = "customer";

            $SalesOrder->save();


            // quotation start

            $quotation = Quotation::where('lead_id', $request->lead_id)->first();
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

            $deposit = $request->payment_amount;
            $balance = $grand_total - $deposit;

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

            $term_condition = TermCondition::where('company_id', $quotation->company_id)->get();

            // mail send start

            $data['db_quotation'] = Quotation::find($request->quotation_id);
            $data['received_payment_amount'] = $request->payment_amount;
            $data['balance_amount'] = PaymentController::get_balance_amount($request->quotation_id);
            $data['customer'] = $customer;
            $data['subject'] = "Invoice " . $data['db_quotation']->invoice_no . " - Acknowledge Payment";
            $data["to_email"] = $customer->email;    

            $data['lead_id'] = $request->lead_id;
            $data['lead'] = Lead::find($request->lead_id);
            $data['lead_details'] = LeadServices::where('lead_id', $request->lead_id)->get();
            $data['quotation'] = $quotation;
            $data['quotation_details'] = $quotation_details;
            $data['company'] = $company;
            $data['customer'] = $customer;
            $data['term_condition'] = $term_condition;
            
            if(!empty($data["to_email"]))
            {
                try {          
                    
                    $files = Pdf::loadView('admin.leads.tax-invoice', $data);
                    $file_name = $quotation->invoice_no.".pdf";

                    Mail::send('emails.received-payment-mail', $data, function ($message) use ($data, $files, $file_name) {
                            // $message->from(Auth::user()->email);
                            $message->to($data['to_email'])
                                    ->subject($data['subject'])
                                    ->attachData($files->output(), $file_name);
                    });                                                   
                }
                catch (\Exception $e) {
                    return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
                }
            }
        
            // mail send end

            // log data store start

            LogController::store('lead', 'Payment received from Asia Pay and Email Send', $request->lead_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('lead', 'Invoice Created '.$invoice_no, $request->lead_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('lead', 'Sales Order Created from lead', $request->lead_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('sales_order', 'Sales Order Created from lead '.$quotation->quotation_no, $SalesOrder->id, $request->customer_id, $customer->customer_name, 'customer');

            LogController::store('quotation', 'Payment received from Asia Pay and Email Send from Lead', $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('quotation', 'Invoice Created '.$invoice_no.' from Lead', $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('quotation', 'Sales Order Created from Quotation', $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $SalesOrder->id, $request->customer_id, $customer->customer_name, 'customer');
            
            LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('invoice', 'Payment received from Asia Pay and Email Send from Lead', $invoice_no, $request->customer_id, $customer->customer_name, 'customer');

            LogController::store('payment', 'Payment received from Asia Pay and Email Send from Lead '.$quotation->quotation_no.' for Invoice No. '.$invoice_no, $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');

            // log data store end

            $data['msg'] = "Thank you for your payment! 😊 We're processing your booking.";

            return view('admin.leads.payment-confirmation', $data);
        }
        else
        {
            $data['msg'] = "Thank you for your payment! 😊 We're processing your booking.";
            return view('admin.leads.payment-confirmation', $data);
        }
    }

    // cancel asia pay payment

    public function payment_cancel_response(Request $request)
    {
        $data['msg'] = "Your payment has been cancelled ❌. Please contact support if you need assistance!";
        return view('admin.leads.payment-confirmation', $data);
    }

    // failed asia pay payment

    public function payment_failed_response(Request $request)
    {
        $data['msg'] = "Your payment has been failed ❌. Please contact support if you need assistance!";
        return view('admin.leads.payment-confirmation', $data);
    }

    // send payment advice (offline by email)

    public function send_payment_offline(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'leadId' => 'required',
                'payment_amount' => 'required',
                'payment_option' => 'required',
            ],
            [],
            [
                'leadId' => 'Lead Id',
                'payment_amount' => 'Payment Amount',
                'payment_option' => 'Payment Option',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Lead::where('id', $request->leadId)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['status'=>'failed', 'message' => 'Lead already Approved']);
        }

        // lead start

        $lead_id = $request->leadId;
        $lead = Lead::find($lead_id);
        $lead_details = LeadServices::where('lead_id', $lead_id)->get();

        // lead end

        $customer_id = $lead->customer_id;

        $total_amount = $lead->grand_total;

        $payment_method = $request->payment_option;
        $payment_amount = $request->payment_amount;

        if($total_amount == $payment_amount)
        {
            $type = "full";
        }
        else
        {
            $type = "advance";
        }

        // quotation start

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

        // $deposit = $payment_amount;
        // $balance = $grand_total - $deposit;

        $deposit = PaymentController::get_deposit_amount($quotation->id);
        $balance = PaymentController::get_balance_amount($quotation->id);

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

        $data['lead_id'] = $lead_id;
        $data['lead'] = $lead;
        $data['lead_details'] = $lead_details;
        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $data['payment_amount'] = $payment_amount;

        $data['send_payment_advice'] = true;

        // invoice no
        $quotation_no = $lead->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $quotation->invoice_no = implode("-", $temp_arr);

        try {

            $files = Pdf::loadView('admin.leads.tax-invoice', $data);
            $file_name = $quotation->invoice_no.".pdf";

            if($request->filled('check_attach_invoice'))
            {
                Mail::send('admin.leads.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
                        // $message->from(Auth::user()->email);
                        $message->to($data['to_email'])
                                ->cc($data['cc'] ?? [])
                                ->bcc($data['bcc'] ?? [])
                                ->subject($data['subject'])
                                ->attachData($files->output(), $file_name);
                });
            }
            else
            {
                Mail::send('admin.leads.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
                        // $message->from(Auth::user()->email);
                        $message->to($data['to_email'])
                                ->cc($data['cc'] ?? [])
                                ->bcc($data['bcc'] ?? [])
                                ->subject($data['subject']);
                });
            }

            // mail send end

            // lead

            $lead = Lead::find($lead_id);
            $lead->status = 3;
            $lead->payment_advice = 2;
            $lead->save();

            // quotation

            $quotation = Quotation::where('lead_id',$lead_id)->first();
            $quotation->status = 3;
            $quotation->payment_advice = 2;
            $quotation->save();

            // log data store start

            LogController::store('lead', 'Payment Advice Send', $lead_id);
            LogController::store('quotation', 'Payment Advice Send from Lead', $quotation->id);

            // log data store end

            return response()->json(['status' => 'success', 'message' => 'Payment Mail Send successfully!']);
        }
        catch (\Exception $e) {

            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        }


    }

    // send payment advice confirm

    public function confirm_payment_advice(Request $request)
    {
        // return $request->all();

        if($request->filled('leadId'))
        {
            if(Lead::where('id', $request->leadId)->whereIn('status', [3, 4])->exists())
            {
                return response()->json(['status'=>'failed', 'message' => 'Lead already Approved']);
            }

            $leadId = $request->leadId;

            $lead = Lead::find($leadId);

            if ($lead)
            {           
                $lead->status = 3;
                $lead->payment_advice = 2;
                $lead->save();
                                
                // quotation start

                $quotation = Quotation::where('lead_id', $leadId)->first();

                if($quotation)
                {                 
                    $quotation->status = 3;
                    $quotation->payment_advice = 2;
                    $quotation->save();     
                }

                // quotation end

                // log data store start

                LogController::store('lead', 'Lead Approved', $leadId);
                LogController::store('quotation', 'Quotation Approved from Lead', $quotation->id);

                // log data store end

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

    // view received payment

    public function received_payment(Request $request)
    {
        $leadId = $request->input('lead_id');

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

        // end

        $asiaOptions = PaymentMethod::where('payment_method', "Asia Pay")->get();
        $offlineOptions = PaymentMethod::where('payment_method', "Offline")->get();

        $emailTemplates = EmailTemplate::get();

        $term_condition = TermCondition::where('company_id', $lead->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        // invoice no
        $quotation_no = $lead->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $lead->temp_invoice_no = implode("-", $temp_arr);

        // return $lead;

        return view('admin.leads.received-payment', compact('leadId', 'lead', 'customer', 'company', 'asiaOptions', 'offlineOptions', 'service', 'emailTemplates', 'service_address', 'billing_address', 'term_condition', 'imagePath'));
    }

    // store received payment

    public function received_payment_store(Request $request)
    {
        // return $request->all();

        $rules = [
            'leadId' => 'required',
            'payment_option' => 'required',
        ];

        $rules_msg = [
            'leadId' => 'Lead Id',
            'payment_option' => 'Payment Option',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [],
            $rules_msg
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }
        else
        {
            if($request->payment_option == "Offline")
            {     
                if($request->filled('payment_amount_checkbox') && $request->filled('payment_option_checkbox'))
                {      
                    $lead_id = $request->leadId;
                    $lead = Lead::find($lead_id);

                    $quotation = Quotation::where('lead_id', $lead_id)->first();
                    $quotation_id = $quotation->id;

                    $customer_id = $lead->customer_id;

                    $total_amount = $lead->grand_total;
                    $payment_method = $request->payment_option;

                    $payment_amount_checkbox = $request->payment_amount_checkbox;

                    if(SalesOrder::where('quotation_id', $quotation->id)->doesntExist())
                    {          
                        $payment_amount_arr = [];
                        $payment_amount = 0;

                        foreach($payment_amount_checkbox as $item)
                        {
                            if(!empty($item))
                            {
                                $payment_amount += $item;

                                $payment_amount_arr[] = $item;
                            }
                        }

                        if($payment_amount == 0)
                        {
                            return response()->json(['status' => 'failed', 'message' => 'Payment amount must be greater than 0']);
                        }

                        if($total_amount == $payment_amount)
                        {
                            $type = "full";
                            $payment_status = "paid";
                        }
                        else
                        {
                            $type = "advance";
                            $payment_status = "partial_paid";
                        }

                        // lead payment detail start

                        $leadPaymentInfo = new LeadPaymentInfo();
                        $leadPaymentInfo->lead_id = $lead_id;
                        $leadPaymentInfo->quotation_id = $quotation_id;
                        $leadPaymentInfo->customer_id = $customer_id;
                        $leadPaymentInfo->payment_method = $payment_method;
                        $leadPaymentInfo->payment_amount = $payment_amount;
                        $leadPaymentInfo->payment_type = $type;
                        $leadPaymentInfo->total_amount = $total_amount;
                        $leadPaymentInfo->payment_remarks = $request->payment_remarks;
                        $leadPaymentInfo->created_by_id = Auth::user()->id;
                        $leadPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                        $leadPaymentInfo->save();

                        // lead payment detail end

                        // lead offline payment detail start

                        $payment_option_checkbox = $request->payment_option_checkbox;

                        $insert_offline_payment_data = [];
                        $insert_payment_proof_data = [];

                        for($i=0; $i<count($payment_amount_arr); $i++)
                        {
                            if(!empty($payment_amount_arr[$i]))
                            {
                                // $LeadOfflinePaymentDetail = new LeadOfflinePaymentDetail();
                                // $LeadOfflinePaymentDetail->lead_payment_id = $leadPaymentInfo->id;
                                // $LeadOfflinePaymentDetail->lead_id = $lead_id;
                                // $LeadOfflinePaymentDetail->payment_option = $payment_option_checkbox[$i];
                                // $LeadOfflinePaymentDetail->amount = $payment_amount_checkbox[$i];
                                // // $LeadOfflinePaymentDetail->payment_proof = "";
                                // $LeadOfflinePaymentDetail->save();

                                $insert_offline_payment_data[] = [
                                    'lead_payment_id' => $leadPaymentInfo->id,
                                    'lead_id' => $lead_id,
                                    'quotation_id' => $quotation_id,
                                    'payment_option' => $payment_option_checkbox[$i],
                                    'amount' => $payment_amount_arr[$i],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];

                                $insert_payment_proof_data[] = [
                                    'payment_id' => $leadPaymentInfo->id,
                                    'lead_id' => $lead_id,
                                    'quotation_id' => $quotation_id,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                            }
                        }

                        // payment proof start

                        $image_type_arr = [];
                        $image_file_arr = [];

                        if($request->hasFile('payment_proof'))
                        {
                            $payment_proof_arr = $request->file('payment_proof');

                            $j = 0;
                            for($i=0; $i<count($payment_amount_checkbox); $i++)
                            {
                                if(!empty($payment_amount_checkbox[$i]))
                                {
                                    $image_type_arr[$i] = $payment_option_checkbox[$j];
                                    $j++;
                                }
                                else
                                {
                                    $image_type_arr[$i] = "";
                                }
                            }

                            for($i=0; $i<count($payment_amount_checkbox); $i++)
                            {
                                $image = $payment_proof_arr[$i] ?? null;

                                if($image)
                                {
                                    $ext = $image->extension();

                                    $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                                    $image->move(public_path('uploads/payment_proof'), $payment_proof_file);

                                    $image_file_arr[$i] = $payment_proof_file;
                                }
                            }

                            $j = 0;
                            for($i=0; $i<count($image_type_arr); $i++)
                            {
                                if($image_type_arr[$i] != "")
                                {
                                    if($insert_offline_payment_data[$j]['payment_option'] == $image_type_arr[$i])
                                    {
                                        $insert_offline_payment_data[$j]['payment_proof'] = $image_file_arr[$i] ?? null;
                                        $insert_payment_proof_data[$j]['payment_proof'] = $image_file_arr[$i] ?? null;
                                    }

                                    $j++;
                                }
                            }

                            // $i = 0;
                            // foreach($request->file('payment_proof') as $image)
                            // {
                            //     $ext = $image->extension();

                            //     $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                            //     $image->move(public_path('uploads/payment_proof'), $payment_proof_file);

                            //     $insert_offline_payment_data[$i]['payment_proof'] = $payment_proof_file;

                            //     $i++;
                            // }
                        }

                        DB::table('payment_proof')->insert($insert_payment_proof_data);

                        // payment proof end

                        DB::table('lead_offline_payment_details')->insert($insert_offline_payment_data);
                        
                        // lead offline payment detail end

                        // lead start

                        $lead = Lead::find($lead_id);
                        $lead_details = LeadServices::where('lead_id', $lead_id)->get();

                        // invoice no
                        $quotation_no = $lead->quotation_no;
                        $temp_arr = explode("-", $quotation_no);
                        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                        $invoice_no = implode("-", $temp_arr);

                        $lead->status = 4;
                        $lead->payment_status = $payment_status;
                        $lead->invoice_no = $invoice_no;
                        $lead->invoice_date = Carbon::now();
                        $lead->save();

                        // lead end

                        // quotation start

                        $quotation = Quotation::where('lead_id', $lead_id)->first();
                        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();

                        $quotation->payment_status = $payment_status;
                        $quotation->invoice_no = $invoice_no;
                        $quotation->invoice_date = Carbon::now();
                        $quotation->status = 4;
                        $quotation->save();

                        // quotation end

                        // Create sales order

                        $data = new SalesOrder();
                        $data->sales_order_no = rand(12334, 99999);
                        $data->customer_id = $quotation->customer_id;
                        $data->company_id = $quotation->company_id;
                        $data->quotation_id = $quotation->id;
                        $data->lead_id = $quotation->lead_id;
                        $data->invoice_no = $quotation->invoice_no;
                        $data->invoice_date = Carbon::now();
                        $data->status = 0;
                        $data->created_by_id = Auth::user()->id;
                        $data->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                        $data->save();

                        // log data store start

                        LogController::store('lead', 'Payment received', $lead_id);
                        LogController::store('lead', 'Invoice Created '.$invoice_no, $lead_id);
                        LogController::store('lead', 'Sales Order Created from lead', $lead_id);
                        LogController::store('sales_order', 'Sales Order Created from lead '.$quotation->quotation_no, $data->id);

                        LogController::store('quotation', 'Payment received from Lead', $quotation_id);
                        LogController::store('quotation', 'Invoice Created '.$invoice_no.' from Lead', $quotation_id);
                        LogController::store('quotation', 'Sales Order Created from Quotation', $quotation_id);
                        LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $data->id);


                        LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no);
                        LogController::store('invoice', 'Payment received from Lead', $invoice_no);

                        LogController::store('payment', 'Payment received from Lead '.$quotation->quotation_no.' for Invoice No. '.$invoice_no, $quotation->id);

                        // log data store end

                        $msg = "Payment received successfully for Invoice: " . $invoice_no . ", Amount: $" . number_format($payment_amount, 2) . ", Date: " . date('d-m-Y');

                        return response()->json(['status' => 'success', 'message' => $msg]);
                    }
                    else
                    {
                        return response()->json(['status' => 'failed', 'message' => 'Payment already received']);
                    }
                }
                else
                {
                    return response()->json(['status' => 'failed', 'message' => 'Payment is not updated successfully!']);
                }
            }
            else
            {
                $lead_id = $request->leadId;
                $lead = Lead::find($lead_id);
                
                $quotation = Quotation::where('lead_id', $lead_id)->first();
                $quotation_id = $quotation->id;

                if(SalesOrder::where('quotation_id', $quotation->id)->doesntExist())
                {                           
                    // lead start

                    $lead = Lead::find($lead_id);
                    $lead_details = LeadServices::where('lead_id', $lead_id)->get();

                    // invoice no
                    $quotation_no = $lead->quotation_no;
                    $temp_arr = explode("-", $quotation_no);
                    $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                    $invoice_no = implode("-", $temp_arr);

                    $lead->status = 4;
                    $lead->payment_status = "unpaid";
                    $lead->invoice_no = $invoice_no;
                    $lead->invoice_date = Carbon::now();
                    $lead->save();

                    // lead end

                    // quotation start

                    $quotation = Quotation::where('lead_id', $lead_id)->first();
                    $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();

                    $quotation->payment_status = "unpaid";
                    $quotation->invoice_no = $invoice_no;
                    $quotation->invoice_date = Carbon::now();
                    $quotation->status = 4;
                    $quotation->save();

                    // quotation end

                    // Create sales order

                    $data = new SalesOrder();
                    $data->sales_order_no = rand(12334, 99999);
                    $data->customer_id = $quotation->customer_id;
                    $data->company_id = $quotation->company_id;
                    $data->quotation_id = $quotation->id;
                    $data->lead_id = $quotation->lead_id;
                    $data->invoice_no = $quotation->invoice_no;
                    $data->invoice_date = Carbon::now();
                    $data->status = 0;
                    $data->created_by_id = Auth::user()->id;
                    $data->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                    $data->save();

                    // log data store start

                    LogController::store('lead', 'Invoice Created '.$invoice_no, $lead_id);
                    LogController::store('lead', 'Sales Order Created from lead', $lead_id);
                    LogController::store('sales_order', 'Sales Order Created from lead '.$quotation->quotation_no, $data->id);

                    LogController::store('quotation', 'Invoice Created '.$invoice_no.' from lead', $lead_id);
                    LogController::store('quotation', 'Sales Order Created from quotation', $lead_id);
                    LogController::store('sales_order', 'Sales Order Created from quotation '.$quotation->quotation_no, $data->id);

                    LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no);

                    // log data store end

                    $msg = "Payment updated successfully for Invoice: " . $invoice_no . ", Amount: $0.00, Date: " . date('d-m-Y');

                    return response()->json(['status' => 'success', 'message' => $msg]);
                }
                else
                {
                    return response()->json(['status' => 'failed', 'message' => 'Payment already received']);
                }
            }
        }
    }

    // received payment store and send email

    public function received_payment_send_email(Request $request)
    {
        // return $request->all();

        $rules = [
            'leadId' => 'required',
            'payment_option' => 'required',
        ];

        $rules_msg = [
            'leadId' => 'Lead Id',
            'payment_option' => 'Payment Option',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [],
            $rules_msg
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }
        else
        {
            if($request->payment_option == "Offline")
            {     
                if($request->filled('payment_amount_checkbox') && $request->filled('payment_option_checkbox'))
                {      
                    $lead_id = $request->leadId;
                    $lead = Lead::find($lead_id);

                    $quotation = Quotation::where('lead_id', $lead_id)->first();
                    $quotation_id = $quotation->id;

                    $customer_id = $lead->customer_id;

                    $total_amount = $lead->grand_total;
                    $payment_method = $request->payment_option;

                    $payment_amount_checkbox = $request->payment_amount_checkbox;

                    if(SalesOrder::where('quotation_id', $quotation->id)->doesntExist())
                    {          
                        $payment_amount_arr = [];
                        $payment_amount = 0;

                        foreach($payment_amount_checkbox as $item)
                        {
                            if(!empty($item))
                            {
                                $payment_amount += $item;

                                $payment_amount_arr[] = $item;
                            }
                        }

                        if($payment_amount == 0)
                        {
                            return response()->json(['status' => 'failed', 'message' => 'Payment amount must be greater than 0']);
                        }

                        if($total_amount == $payment_amount)
                        {
                            $type = "full";
                            $payment_status = "paid";
                        }
                        else
                        {
                            $type = "advance";
                            $payment_status = "partial_paid";
                        }

                        // lead payment detail start

                        $leadPaymentInfo = new LeadPaymentInfo();
                        $leadPaymentInfo->lead_id = $lead_id;
                        $leadPaymentInfo->quotation_id = $quotation_id;
                        $leadPaymentInfo->customer_id = $customer_id;
                        $leadPaymentInfo->payment_method = $payment_method;
                        $leadPaymentInfo->payment_amount = $payment_amount;
                        $leadPaymentInfo->payment_type = $type;
                        $leadPaymentInfo->total_amount = $total_amount;
                        $leadPaymentInfo->payment_remarks = $request->payment_remarks;
                        $leadPaymentInfo->created_by_id = Auth::user()->id;
                        $leadPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                        $leadPaymentInfo->save();

                        // lead payment detail end

                        // lead offline payment detail start

                        $payment_option_checkbox = $request->payment_option_checkbox;

                        $insert_offline_payment_data = [];
                        $insert_payment_proof_data = [];

                        for($i=0; $i<count($payment_amount_arr); $i++)
                        {
                            if(!empty($payment_amount_arr[$i]))
                            {
                                // $LeadOfflinePaymentDetail = new LeadOfflinePaymentDetail();
                                // $LeadOfflinePaymentDetail->lead_payment_id = $leadPaymentInfo->id;
                                // $LeadOfflinePaymentDetail->lead_id = $lead_id;
                                // $LeadOfflinePaymentDetail->payment_option = $payment_option_checkbox[$i];
                                // $LeadOfflinePaymentDetail->amount = $payment_amount_checkbox[$i];
                                // // $LeadOfflinePaymentDetail->payment_proof = "";
                                // $LeadOfflinePaymentDetail->save();

                                $insert_offline_payment_data[] = [
                                    'lead_payment_id' => $leadPaymentInfo->id,
                                    'lead_id' => $lead_id,
                                    'quotation_id' => $quotation_id,
                                    'payment_option' => $payment_option_checkbox[$i],
                                    'amount' => $payment_amount_arr[$i],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];

                                $insert_payment_proof_data[] = [
                                    'payment_id' => $leadPaymentInfo->id,
                                    'lead_id' => $lead_id,
                                    'quotation_id' => $quotation_id,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                            }
                        }

                        // payment proof start

                        $image_type_arr = [];
                        $image_file_arr = [];

                        if($request->hasFile('payment_proof'))
                        {
                            $payment_proof_arr = $request->file('payment_proof');

                            $j = 0;
                            for($i=0; $i<count($payment_amount_checkbox); $i++)
                            {
                                if(!empty($payment_amount_checkbox[$i]))
                                {
                                    $image_type_arr[$i] = $payment_option_checkbox[$j];
                                    $j++;
                                }
                                else
                                {
                                    $image_type_arr[$i] = "";
                                }
                            }

                            for($i=0; $i<count($payment_amount_checkbox); $i++)
                            {
                                $image = $payment_proof_arr[$i] ?? null;

                                if($image)
                                {
                                    $ext = $image->extension();

                                    $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                                    $image->move(public_path('uploads/payment_proof'), $payment_proof_file);

                                    $image_file_arr[$i] = $payment_proof_file;
                                }
                            }

                            $j = 0;
                            for($i=0; $i<count($image_type_arr); $i++)
                            {
                                if($image_type_arr[$i] != "")
                                {
                                    if($insert_offline_payment_data[$j]['payment_option'] == $image_type_arr[$i])
                                    {
                                        $insert_offline_payment_data[$j]['payment_proof'] = $image_file_arr[$i] ?? null;
                                        $insert_payment_proof_data[$j]['payment_proof'] = $image_file_arr[$i] ?? null;
                                    }

                                    $j++;
                                }
                            }

                            // $i = 0;
                            // foreach($request->file('payment_proof') as $image)
                            // {
                            //     $ext = $image->extension();

                            //     $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                            //     $image->move(public_path('uploads/payment_proof'), $payment_proof_file);

                            //     $insert_offline_payment_data[$i]['payment_proof'] = $payment_proof_file;

                            //     $i++;
                            // }
                        }

                        DB::table('payment_proof')->insert($insert_payment_proof_data);

                        // payment proof end

                        DB::table('lead_offline_payment_details')->insert($insert_offline_payment_data);
                        
                        // lead offline payment detail end

                        // lead start

                        $lead = Lead::find($lead_id);
                        $lead_details = LeadServices::where('lead_id', $lead_id)->get();

                        // invoice no
                        $quotation_no = $lead->quotation_no;
                        $temp_arr = explode("-", $quotation_no);
                        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                        $invoice_no = implode("-", $temp_arr);

                        $lead->status = 4;
                        $lead->payment_status = $payment_status;
                        $lead->invoice_no = $invoice_no;
                        $lead->invoice_date = Carbon::now();
                        $lead->save();

                        // lead end

                        // quotation start

                        $quotation = Quotation::where('lead_id', $lead_id)->first();
                        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();

                        $quotation->payment_status = $payment_status;
                        $quotation->invoice_no = $invoice_no;
                        $quotation->invoice_date = Carbon::now();
                        $quotation->status = 4;
                        $quotation->save();

                        // quotation end

                        // Create sales order

                        $SalesOrder = new SalesOrder();
                        $SalesOrder->sales_order_no = rand(12334, 99999);
                        $SalesOrder->customer_id = $quotation->customer_id;
                        $SalesOrder->company_id = $quotation->company_id;
                        $SalesOrder->quotation_id = $quotation->id;
                        $SalesOrder->lead_id = $quotation->lead_id;
                        $SalesOrder->invoice_no = $quotation->invoice_no;
                        $SalesOrder->invoice_date = Carbon::now();
                        $SalesOrder->status = 0;
                        $SalesOrder->created_by_id = Auth::user()->id;
                        $SalesOrder->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                        $SalesOrder->save();


                        // quotation start

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

                        $deposit = $request->received_payment_amount;
                        $balance = $grand_total - $deposit;

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

                        $data['lead_id'] = $lead_id;
                        $data['lead'] = $lead;
                        $data['lead_details'] = $lead_details;
                        $data['quotation'] = $quotation;
                        $data['quotation_details'] = $quotation_details;
                        $data['company'] = $company;
                        $data['customer'] = $customer;
                        $data['term_condition'] = $term_condition;

                        try {      

                            $files = Pdf::loadView('admin.leads.tax-invoice', $data);
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

                        LogController::store('lead', 'Payment received and Email Send', $lead_id);
                        LogController::store('lead', 'Invoice Created '.$invoice_no, $lead_id);
                        LogController::store('lead', 'Sales Order Created from lead', $lead_id);
                        LogController::store('sales_order', 'Sales Order Created from lead '.$quotation->quotation_no, $SalesOrder->id);

                        LogController::store('quotation', 'Payment received from Lead', $quotation->id);
                        LogController::store('quotation', 'Invoice Created '.$invoice_no.' from Lead', $quotation->id);
                        LogController::store('quotation', 'Sales Order Created from Quotation', $quotation->id);
                        LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $SalesOrder->id);

                        LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no);
                        LogController::store('invoice', 'Payment received from Lead', $invoice_no);

                        LogController::store('payment', 'Payment received from Lead '.$quotation->quotation_no.' for Invoice No. '.$invoice_no, $quotation->id);

                        // log data store end

                        $msg = "Payment received successfully for Invoice: " . $invoice_no . ", Amount: $" . number_format($payment_amount, 2) . ", Date: " . date('d-m-Y');

                        return response()->json(['status' => 'success', 'message' => $msg]);
                    }
                    else
                    {
                        return response()->json(['status' => 'failed', 'message' => 'Payment already received']);
                    }
                }
                else
                {
                    return response()->json(['status' => 'failed', 'message' => 'Payment is not updated successfully!']);
                }
            }
            else
            {
                $lead_id = $request->leadId;
                $lead = Lead::find($lead_id);
                
                $quotation = Quotation::where('lead_id', $lead_id)->first();
                $quotation_id = $quotation->id;

                if(SalesOrder::where('quotation_id', $quotation->id)->doesntExist())
                {                           
                    // lead start

                    $lead = Lead::find($lead_id);
                    $lead_details = LeadServices::where('lead_id', $lead_id)->get();

                    // invoice no
                    $quotation_no = $lead->quotation_no;
                    $temp_arr = explode("-", $quotation_no);
                    $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                    $invoice_no = implode("-", $temp_arr);

                    $lead->status = 4;
                    $lead->payment_status = "unpaid";
                    $lead->invoice_no = $invoice_no;
                    $lead->invoice_date = Carbon::now();
                    $lead->save();

                    // lead end

                    // quotation start

                    $quotation = Quotation::where('lead_id', $lead_id)->first();
                    $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();

                    $quotation->payment_status = "unpaid";
                    $quotation->invoice_no = $invoice_no;
                    $quotation->invoice_date = Carbon::now();
                    $quotation->status = 4;
                    $quotation->save();

                    // quotation end

                    // Create sales order

                    $SalesOrder = new SalesOrder();
                    $SalesOrder->sales_order_no = rand(12334, 99999);
                    $SalesOrder->customer_id = $quotation->customer_id;
                    $SalesOrder->company_id = $quotation->company_id;
                    $SalesOrder->quotation_id = $quotation->id;
                    $SalesOrder->lead_id = $quotation->lead_id;
                    $SalesOrder->invoice_no = $quotation->invoice_no;
                    $SalesOrder->invoice_date = Carbon::now();
                    $SalesOrder->status = 0;
                    $SalesOrder->created_by_id = Auth::user()->id;
                    $SalesOrder->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                    $SalesOrder->save();


                    // quotation start

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

                    $deposit = $request->received_payment_amount;
                    $balance = $grand_total - $deposit;

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
                    
                    $data['lead_id'] = $lead_id;
                    $data['lead'] = $lead;
                    $data['lead_details'] = $lead_details;
                    $data['quotation'] = $quotation;
                    $data['quotation_details'] = $quotation_details;
                    $data['company'] = $company;
                    $data['customer'] = $customer;
                    $data['term_condition'] = $term_condition;

                    try {        
                        
                        $files = Pdf::loadView('admin.leads.tax-invoice', $data);
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

                    LogController::store('lead', 'Invoice Created '.$invoice_no, $lead_id);
                    LogController::store('lead', 'Sales Order Created from lead', $lead_id);
                    LogController::store('sales_order', 'Sales Order Created from lead '.$quotation->quotation_no, $SalesOrder->id);

                    LogController::store('quotation', 'Invoice Created '.$invoice_no.' from Lead', $quotation->id);
                    LogController::store('quotation', 'Sales Order Created from Quotation', $quotation->id);
                    LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $SalesOrder->id);

                    LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no);
                    
                    // log data store end

                    $msg = "Payment updated successfully for Invoice: " . $invoice_no . ", Amount: $0.00, Date: " . date('d-m-Y');

                    return response()->json(['status' => 'success', 'message' => $msg]);
                }
                else
                {
                    return response()->json(['status' => 'failed', 'message' => 'Payment already received']);
                }
            }
        }
    }

    // download send payment advice pdf start

    public function payment_advice_view_pdf(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'leadId' => 'required',
                'payment_amount' => 'required',
                'payment_option' => 'required',
            ],
            [],
            [
                'leadId' => 'Lead Id',
                'payment_amount' => 'Payment Amount',
                'payment_option' => 'Payment Option',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $lead_id = $request->leadId;
            $lead = Lead::find($lead_id);

            $total_amount = $lead->grand_total;

            $payment_method = $request->payment_option;
            $payment_amount = $request->payment_amount;

            if($total_amount == $payment_amount)
            {
                $type = "full";
            }
            else
            {
                $type = "advance";
            }

            if($payment_method == "Asia Pay")
            {
                $paymentLink = $this->generatePaymentLink($request);
                $data['payment_link'] = $paymentLink->getOriginalContent()['payment_link'];
            }
            else
            {
                $data['payment_link'] = "";
            }

            $data['lead_id'] = $lead_id;
            $data['company_id'] = $request->company_id;
            $data['payment_amount'] = $payment_amount;
            $data['payment_method'] = $payment_method;
            $data['total_amount'] = $total_amount;

            // return $data;

            return response()->json(['status'=>'success', 'route'=>route('lead.payment-advice.view-download-pdf', $data)]);
        }
    }

    public function payment_advice_view_download_pdf(Request $request)
    {
        $data['lead_id'] = $request->lead_id;
        $data['company_id'] = $request->company_id;
        $data['payment_amount'] = $request->payment_amount;
        $data['payment_method'] = $request->payment_method;
        $data['total_amount'] = $request->total_amount;
        $data['payment_link'] = $request->payment_link;

        // company

        $company = Company::where('id', $request->company_id)->first();

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

        $data['company'] = $company;

        // return $data;

        return view('admin.leads.view-payment-advice-pdf', $data);
    }

    // download send payment advice pdf end
}
