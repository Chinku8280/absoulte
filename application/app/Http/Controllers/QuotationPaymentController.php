<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\Company;
use App\Models\Crm;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\LeadPaymentInfo;
use App\Models\PaymentMethod;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;
use App\Models\ServiceAddress;
use App\Models\TermCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class QuotationPaymentController extends Controller
{
    // view send payment advice

    public function send_payment(Request $request)
    {
        $quotationId = $request->input('quotation_id');

        $quotation = Quotation::select('quotations.*')
                 //   ->leftJoin('quotation_payment_detail', 'quotation_customer_details.id', '=', 'quotation_payment_detail.quotation_id')
                  //  ->join('quotation_price_info', 'quotation_customer_details.id', '=', 'quotation_price_info.quotation_id')
                    ->where('quotations.id', $quotationId)
                    ->first();

        $customerId = $quotation->customer_id;

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

        $service_address = ServiceAddress::where('customer_id', $customerId)->where('id', $quotation->service_address)->first();
        $billing_address = BillingAddress::where('customer_id', $customerId)->where('id', $quotation->billing_address)->first();

        $company = Company::where('id', $quotation->company_id)->first();

        $service = QuotationServiceDetail::where('quotation_id', $quotationId)->get();

        // start
        $subtotal = 0;

        foreach($service as $item)
        {
            $subtotal += $item->unit_price * $item->quantity;
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

        // end

        $asiaOptions = PaymentMethod::where('payment_method', "Asia Pay")->get();
        $offlineOptions = PaymentMethod::where('payment_method', "Offline")->get();

        $emailTemplates = EmailTemplate::get();

        $term_condition = TermCondition::where('company_id', $quotation->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        // invoice no
        $quotation_no = $quotation->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $quotation->temp_invoice_no = implode("-", $temp_arr);

        return view('admin.quotation.payment', compact('quotationId', 'quotation', 'customer', 'company', 'asiaOptions', 'offlineOptions', 'service', 'emailTemplates', 'service_address', 'billing_address', 'term_condition', 'imagePath'));
    }

    // send payment advice (asia pay)

    public function processPayment(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'quotationId' => 'required',
                'payment_amount' => 'required',
                'payment_option' => 'required',
            ],
            [],
            [
                'quotationId' => 'quotation Id',
                'payment_amount' => 'Payment Amount',
                'payment_option' => 'Payment Option',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Quotation::where('id', $request->quotationId)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['status'=>'failed', 'message' => 'Quotation already Approved']);
        }

        $quotation_id = $request->quotationId;
        $quotation = Quotation::find($quotation_id);

        $total_amount = $quotation->grand_total;

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
            $quotation = Quotation::find($quotation_id);
            $quotation->status = 3;
            $quotation->payment_advice = 2;
            $quotation->save();

            // lead

            if(Lead::find($quotation->lead_id))
            {
                $lead = Lead::find($quotation->lead_id);
                $lead->status = 3;
                $lead->payment_advice = 2;
                $lead->save();
            }

            // log data store start

            LogController::store('quotation', 'Payment Advice Send', $quotation_id);

            // log data store end

            return response()->json(['status'=>'success', 'message'=>'Payment link send successfully', 'payment_link' => $paymentLink]);
        }
        else {
            // return response()->json(['error' => 'Error sending payment email']);
            return response()->json(['status'=>'failed', 'message'=>'Error sending payment email']);
        }
    }

    public function quotation_payment_preview($id)
    {
        $data['quotation'] = Quotation::find($id);

        $customerId = $data['quotation']->customer_id;

        $data['customer'] = Crm::leftJoin('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
                                ->select(
                                    'customers.*',
                                    'customers.id as customer_id',
                                    'language_spoken.language_name as language_name',
                                )
                                ->where('customers.id', $customerId)
                                ->first();

        $payment_terms = PaymentTerms::find($data['customer']->payment_terms);

        if($payment_terms)
        {
            $data['customer']->payment_terms_value = $payment_terms->payment_terms;
        }
        else
        {
            $data['customer']->payment_terms_value = "";
        }

        $data['service_address'] = ServiceAddress::where('customer_id', $customerId)->where('id', $data['quotation']->service_address)->first();
        $data['billing_address'] = BillingAddress::where('customer_id', $customerId)->where('id', $data['quotation']->billing_address)->first();

        $data['company'] = Company::where('id', $data['quotation']->company_id)->first();

        $data['service'] = QuotationServiceDetail::where('quotation_id', $id)->get();

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

        $data['asiaOptions'] = PaymentMethod::where('payment_method', "Asia Pay")->get();
        $data['offlineOptions'] = PaymentMethod::where('payment_method', "Offline")->get();

        $data['emailTemplates'] = EmailTemplate::get();

        $data['term_condition'] = TermCondition::where('company_id', $data['quotation']->company_id)->get();

        $data['imagePath'] = 'application/public/company_logos/' . $data['company']->company_logo;

        // return $data;

        return view('admin.quotation.payment', $data);
    }

    public function online_payment(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'quotation_id' => 'required',
                'payment_amount' => 'required',
                'payment_option' => 'required',
            ],
            [],
            [
                'quotation_id' => 'Quotation Id',
                'payment_amount' => 'Payment Amount',
                'payment_option' => 'Payment Option',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }
        else
        {
            $quotation_id = $request->quotation_id;
            $quotation = Quotation::find($quotation_id);

            $total_amount = $quotation->grand_total;

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

            $paymentLink = $this->generatePaymentLink($request);

            $result = $this->sendPaymentEmail($request, $paymentLink);

            if ($result === true)
            {
                return response()->json(['status'=>'success', 'message'=>'Payment link send successfully', 'payment_link' => $paymentLink]);
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Error sending payment email']);
            }
        }
    }

    public function generatePaymentLink(Request $request)
    {
        $quotation_id = $request->quotationId;
        $quotation = Quotation::find($quotation_id);

        $customer_id = $quotation->customer_id;

        $total_amount = $quotation->grand_total;

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
        $quotation_no = $quotation->quotation_no;
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
            'successUrl' => route('quotation.payment-success-response', [
                'quotation_id' => $quotation_id,
                'total_amount' => $total_amount,
                'payment_method' => $payment_method,
                'payment_amount' => $payment_amount,
                'type' => $type,
                'customer_id' => $customer_id
            ]), // Replace with your success URL
            'failUrl' => route('quotation.payment-failed-response'), // Replace with your fail URL
            'cancelUrl' => route('quotation.payment-cancel-response'), // Replace with your cancel URL
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
    //     // quotation start

    //     $quotation_id = $request->quotation_id;
    //     $quotation = Quotation::find($quotation_id);
    //     $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

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

    //     // quotation end

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

    //     // calculation end

    //     $company = Company::where('id', $request->company_id)->first();

    //     if(!empty($company->company_logo))
    //     {
    //         $company->image_path = "/company_logos/$company->company_logo";
    //     }
    //     else
    //     {
    //         $company->image_path = "";
    //     }

    //     $customer = DB::table('customers')->where('id', $request->customer_id)->first();
    //     $payment_terms = PaymentTerms::find($customer->payment_terms);

    //     if($payment_terms)
    //     {
    //         $customer->payment_terms_value = $payment_terms->payment_terms;
    //     }
    //     else
    //     {
    //         $customer->payment_terms_value = "";
    //     }

    //     $term_condition = TermCondition::where('company_id', $request->company_id)->get();

    //     // mail send start

    //     $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();

    //     if($request->filled('email_cc'))
    //     {
    //         $email_cc = $request->email_cc;

    //         $cc_arr = [];

    //         foreach(json_decode($email_cc) as $value)
    //         {
    //             $temp = $value->value;

    //             array_push($cc_arr, $temp);
    //         }

    //         $new_cc = implode(',', $cc_arr);
    //     }
    //     else
    //     {
    //         $new_cc = '';
    //         $cc_arr = [];
    //     }

    //     $data['title'] = $emailTemplate->title;
    //     $data['subject'] = $request->email_subject;
    //     $data['body'] = $request->email_body;
    //     $data["to_email"] = $request->email_to;
    //     $data["cc"] = $cc_arr;
    //     $data["bcc"] = $request->email_bcc;

    //     $data["payment_link"] = $directPaymentLink;
    //     $data["company_name"] = "";

    //     $data['quotation_id'] = $quotation_id;
    //     $data['quotation'] = $quotation;
    //     $data['quotation_details'] = $quotation_details;
    //     $data['company'] = $company;
    //     $data['customer'] = $customer;
    //     $data['term_condition'] = $term_condition;

    //     try {

    //         $files = Pdf::loadView('admin.quotation.invoice', $data);
    //         $file_name = $quotation->quotation_no.".pdf";

    //         if($request->filled('check_attach_invoice'))
    //         {
    //             Mail::send('admin.quotation.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
    //                     // $message->from(Auth::user()->email);
    //                     $message->to($data['to_email'])
    //                             ->cc($data['cc'])
    //                             ->bcc($data['bcc'])
    //                             ->subject($data['subject'])
    //                             ->attachData($files->output(), $file_name);
    //             });
    //         }
    //         else
    //         {
    //             Mail::send('admin.quotation.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
    //                     // $message->from(Auth::user()->email);
    //                     $message->to($data['to_email'])
    //                             ->cc($data['cc'])
    //                             ->bcc($data['bcc'])
    //                             ->subject($data['subject']);
    //             });
    //         }

    //         return true;
    //     }
    //     catch (\Exception $e) {

    //         return $e;

    //         // Log::error('Error sending payment email: ' . $e->getMessage());

    //         // return false;
    //     }

    //     // mail end end
    // }

    private function sendPaymentEmail(Request $request, $directPaymentLink)
    {
        $payment_amount = $request->payment_amount;
        // quotation start

        $quotation_id = $request->quotationId;
        $quotation = quotation::find($quotation_id);
        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

        // quotation end

        // quotation start

        $quotation = Quotation::where('id', $quotation_id)->first();
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

        $data['quotation_id'] = $quotation_id;
        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;

        $data['send_payment_advice'] = true;

        // invoice no
        $quotation_no = $quotation->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $quotation->invoice_no = implode("-", $temp_arr);

        try {

            $files = Pdf::loadView('admin.quotation.tax-invoice', $data);
            $file_name = $quotation->invoice_no.".pdf";

            if($request->filled('check_attach_invoice'))
            {
                Mail::send('admin.quotation.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
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
                Mail::send('admin.quotation.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
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

        // mail end end
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

            $quotation = Quotation::find($request->quotation_id);
            $lead_id = $quotation->lead_id;

            $leadPaymentInfo = new LeadPaymentInfo();
            $leadPaymentInfo->quotation_id = $request->quotation_id;
            $leadPaymentInfo->lead_id = $lead_id;
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

            // invoice no
            $quotation_no = $quotation->quotation_no;
            $temp_arr = explode("-", $quotation_no);
            $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
            $invoice_no = implode("-", $temp_arr);

            // quotation

            $quotation->invoice_no = $invoice_no;
            $quotation->invoice_date = Carbon::now();
            $quotation->payment_status = $payment_status;
            $quotation->status = 4;
            $quotation->save();

            // lead

            if(Lead::find($lead_id))
            {
                $lead = Lead::find($lead_id);

                $lead->status = 4;
                $lead->invoice_no = $invoice_no;
                $lead->invoice_date = Carbon::now();
                $lead->payment_status = $payment_status;
                $lead->save();
            }

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

            $quotation = Quotation::where('id', $request->quotation_id)->first();
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

            $data['quotation_id'] = $request->quotation_id;
            $data['quotation'] = $quotation;
            $data['quotation_details'] = $quotation_details;
            $data['company'] = $company;
            $data['customer'] = $customer;
            $data['term_condition'] = $term_condition;
            
            if(!empty($data["to_email"]))
            {
                try {      
                    
                    $files = Pdf::loadView('admin.quotation.tax-invoice', $data);
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

            LogController::store('quotation', 'Payment received from Asia Pay and Email Send', $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('quotation', 'Invoice Created '.$invoice_no, $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('quotation', 'Sales Order Created from Quotation', $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $SalesOrder->id, $request->customer_id, $customer->customer_name, 'customer');

            LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no, $request->customer_id, $customer->customer_name, 'customer');
            LogController::store('invoice', 'Payment received from Asia Pay and Email Send from Quotation', $invoice_no, $request->customer_id, $customer->customer_name, 'customer');

            LogController::store('payment', 'Payment received from Asia Pay and Email Send from Quotation '.$quotation->quotation_no.' for Invoice No. '.$invoice_no, $request->quotation_id, $request->customer_id, $customer->customer_name, 'customer');

            // log data store end

            $data['msg'] = "Thank you for your payment! 😊 We're processing your booking.";

            return view('admin.quotation.payment-confirmation', $data);
        }
        else
        {
            $data['msg'] = "Thank you for your payment! 😊 We're processing your booking.";
            return view('admin.quotation.payment-confirmation', $data);
        }
    }

    // cancel asia pay payment

    public function payment_cancel_response(Request $request)
    {
        $data['msg'] = "Your payment has been cancelled ❌. Please contact support if you need assistance!";
        return view('admin.quotation.payment-confirmation', $data);
    }

    // failed asia pay payment

    public function payment_failed_response(Request $request)
    {
        $data['msg'] = "Your payment has been failed ❌. Please contact support if you need assistance!";
        return view('admin.quotation.payment-confirmation', $data);
    }

    // offline payment

    // public function offline_payment(Request $request)
    // {
    //     // return $request->all();

    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'quotation_id' => 'required',
    //             'payment_amount_checkbox' => 'required',
    //             'payment_option_checkbox' => 'required',
    //             'payment_option' => 'required',
    //         ],
    //         [],
    //         [
    //             'quotation_id' => 'Quotation Id',
    //             'payment_amount_checkbox' => 'Payment Amount Checkbox',
    //             'payment_option_checkbox' => 'Payment Option Checkbox',
    //             'payment_option' => 'Payment Option',
    //         ]
    //     );

    //     if ($validator->fails())
    //     {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }
    //     else
    //     {
    //         $quotation_id = $request->quotation_id;
    //         $quotation = Quotation::find($quotation_id);
    //         $lead_id = $quotation->lead_id;

    //         $customer_id = $quotation->customer_id;

    //         $total_amount = $quotation->grand_total;
    //         $payment_method = $request->payment_option;

    //         $payment_amount_checkbox = $request->payment_amount_checkbox;

    //         $payment_amount_arr = [];
    //         $payment_amount = 0;

    //         foreach($payment_amount_checkbox as $item)
    //         {
    //             if(!empty($item))
    //             {
    //                 $payment_amount += $item;

    //                 $payment_amount_arr[] = $item;
    //             }
    //         }

    //         if($total_amount == $payment_amount)
    //         {
    //             $type = "full";
    //             $payment_status = "paid";
    //         }
    //         else
    //         {
    //             $type = "advance";
    //             $payment_status = "partial_paid";
    //         }

    //         // lead payment detail start

    //         $leadPaymentInfo = new LeadPaymentInfo();
    //         $leadPaymentInfo->quotation_id = $quotation_id;
    //         $leadPaymentInfo->lead_id = $lead_id;
    //         $leadPaymentInfo->customer_id = $customer_id;
    //         $leadPaymentInfo->payment_method = $payment_method;
    //         $leadPaymentInfo->payment_amount = $payment_amount;
    //         $leadPaymentInfo->payment_type = $type;
    //         $leadPaymentInfo->total_amount = $total_amount;

    //         $leadPaymentInfo->save();

    //         // lead payment detail end

    //         // lead offline payment detail start

    //         $payment_option_checkbox = $request->payment_option_checkbox;

    //         $insert_offline_payment_data = [];

    //         for($i=0; $i<count($payment_amount_arr); $i++)
    //         {
    //             if(!empty($payment_amount_arr[$i]))
    //             {
    //                 $insert_offline_payment_data[] = [
    //                     'lead_payment_id' => $leadPaymentInfo->id,
    //                     'lead_id' => $lead_id,
    //                     'quotation_id' => $quotation_id,
    //                     'payment_option' => $payment_option_checkbox[$i],
    //                     'amount' => $payment_amount_arr[$i],
    //                     'created_at' => Carbon::now(),
    //                     'updated_at' => Carbon::now(),
    //                 ];
    //             }
    //         }

    //         // payment proof start

    //         $image_type_arr = [];
    //         $image_file_arr = [];

    //         if($request->hasFile('payment_proof'))
    //         {
    //             $payment_proof_arr = $request->file('payment_proof');

    //             $j = 0;
    //             for($i=0; $i<count($payment_amount_checkbox); $i++)
    //             {
    //                 if(!empty($payment_amount_checkbox[$i]))
    //                 {
    //                     $image_type_arr[$i] = $payment_option_checkbox[$j];
    //                     $j++;
    //                 }
    //                 else
    //                 {
    //                     $image_type_arr[$i] = "";
    //                 }
    //             }

    //             for($i=0; $i<count($payment_amount_checkbox); $i++)
    //             {
    //                 $image = $payment_proof_arr[$i] ?? null;

    //                 if($image)
    //                 {
    //                     $ext = $image->extension();

    //                     $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

    //                     $image->move(public_path('uploads/payment_proof'), $payment_proof_file);

    //                     $image_file_arr[$i] = $payment_proof_file;
    //                 }
    //             }

    //             $j = 0;
    //             for($i=0; $i<count($image_type_arr); $i++)
    //             {
    //                 if($image_type_arr[$i] != "")
    //                 {
    //                     if($insert_offline_payment_data[$j]['payment_option'] == $image_type_arr[$i])
    //                     {
    //                         $insert_offline_payment_data[$j]['payment_proof'] = $image_file_arr[$i] ?? null;
    //                     }

    //                     $j++;
    //                 }
    //             }
    //         }

    //         // payment proof end

    //         DB::table('lead_offline_payment_details')->insert($insert_offline_payment_data);

    //         // lead offline payment detail end

    //         // invoice no
    //         $quotation_no = $quotation->quotation_no;
    //         $temp_arr = explode("-", $quotation_no);
    //         $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
    //         $invoice_no = implode("-", $temp_arr);

    //         $quotation->invoice_no = $invoice_no;
    //         $quotation->payment_status = $payment_status;
    //         $quotation->save();

    //         if(Lead::find($lead_id))
    //         {
    //             $lead = Lead::find($lead_id);

    //             $lead->status = 4;
    //             $lead->invoice_no = $invoice_no;
    //             $lead->payment_status = $payment_status;
    //             $lead->save();
    //         }

    //         // quotation start

    //         $quotation = Quotation::find($quotation_id);
    //         $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation->id)->get();

    //         $ServiceAddress = ServiceAddress::find($quotation->service_address);

    //         if($ServiceAddress)
    //         {
    //             $quotation->service_address_details = $ServiceAddress->address;
    //             $quotation->service_address_unit_number = $ServiceAddress->unit_number;
    //         }
    //         else
    //         {
    //             $quotation->service_address_details = "";
    //             $quotation->service_address_unit_number = "";
    //         }

    //         // quotation end

    //         // lead payment detail start

    //         $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $quotation_id)->get();

    //         $deposit = 0;
    //         $balance = 0;
    //         foreach($lead_payment_detail as $item)
    //         {
    //             $deposit += $item->payment_amount;
    //         }
    //         $balance = $quotation->grand_total - $deposit;

    //         // lead payment detail end

    //         // calculation start

    //         $subtotal = 0;

    //         foreach($quotation_details as $item)
    //         {
    //             $subtotal += $item->unit_price;
    //         }

    //         $nettotal = $quotation->amount;

    //         if($quotation->discount_type == "percentage")
    //         {
    //             $discount_amt = $nettotal * $quotation->discount/100;
    //         }
    //         else
    //         {
    //             $discount_amt = $quotation->discount;
    //         }

    //         $total = $nettotal - $discount_amt;

    //         $quotation->subtotal = $subtotal;
    //         $quotation->nettotal = $nettotal;
    //         $quotation->discount_amt = $discount_amt;
    //         $quotation->total = $total;
    //         $quotation->deposit = $deposit;
    //         $quotation->balance = $balance;

    //         // calculation end

    //         $company = Company::where('id', $request->company_id)->first();

    //         if(!empty($company->company_logo))
    //         {
    //             $company->image_path = "/company_logos/$company->company_logo";
    //         }
    //         else
    //         {
    //             $company->image_path = "";
    //         }

    //         $customer = DB::table('customers')->where('id', $request->customer_id)->first();
    //         $payment_terms = PaymentTerms::find($customer->payment_terms);

    //         if($payment_terms)
    //         {
    //             $customer->payment_terms_value = $payment_terms->payment_terms;
    //         }
    //         else
    //         {
    //             $customer->payment_terms_value = "";
    //         }

    //         $term_condition = TermCondition::where('company_id', $request->company_id)->get();

    //         // mail send start

    //         $emailTemplate = EmailTemplate::where('id', $request->email_template_id)->first();

    //         if($request->filled('email_cc'))
    //         {
    //             $email_cc = $request->email_cc;

    //             $cc_arr = [];

    //             foreach(json_decode($email_cc) as $value)
    //             {
    //                 $temp = $value->value;

    //                 array_push($cc_arr, $temp);
    //             }

    //             $new_cc = implode(',', $cc_arr);
    //         }
    //         else
    //         {
    //             $new_cc = '';
    //             $cc_arr = [];
    //         }

    //         $data['title'] = $emailTemplate->title;
    //         $data['subject'] = $request->email_subject;
    //         $data['body'] = $request->email_body;
    //         $data["to_email"] = $request->email_to;
    //         $data["cc"] = $cc_arr;
    //         $data["bcc"] = $request->email_bcc;

    //         $data['quotation'] = $quotation;
    //         $data['quotation_details'] = $quotation_details;
    //         $data['company'] = $company;
    //         $data['customer'] = $customer;
    //         $data['term_condition'] = $term_condition;

    //         $files = Pdf::loadView('admin.quotation.tax-invoice', $data);
    //         $file_name = $quotation->invoice_no.".pdf";

    //         Mail::send('admin.quotation.mail', $data, function ($message) use ($data, $files, $file_name) {
    //             // $message->from(Auth::user()->email);
    //             $message->to($data['to_email'])
    //                     ->cc($data['cc'])
    //                     ->bcc($data['bcc'])
    //                     ->subject($data['subject'])
    //                     ->attachData($files->output(), $file_name);
    //         });

    //         // mail send end

    //         return response()->json(['status' => 'success', 'message' => 'Payment updated successfully!']);
    //     }
    // }

    // send payment advice (offline by email)

    public function send_payment_offline(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'quotationId' => 'required',
                'payment_amount' => 'required',
                'payment_option' => 'required',
            ],
            [],
            [
                'quotationId' => 'quotation Id',
                'payment_amount' => 'Payment Amount',
                'payment_option' => 'Payment Option',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        if(Quotation::where('id', $request->quotationId)->whereIn('status', [3, 4])->exists())
        {
            return response()->json(['status'=>'failed', 'message' => 'Quotation already Approved']);
        }

        // quotation start

        $quotation_id = $request->quotationId;
        $quotation = Quotation::find($quotation_id);
        $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

        // quotation end

        $customer_id = $quotation->customer_id;

        $total_amount = $quotation->grand_total;

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

        $quotation = Quotation::where('id', $quotation_id)->first();
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

        $data['quotation_id'] = $quotation_id;
        $data['quotation'] = $quotation;
        $data['quotation_details'] = $quotation_details;
        $data['company'] = $company;
        $data['customer'] = $customer;
        $data['term_condition'] = $term_condition;
        
        $data['payment_amount'] = $payment_amount;

        $data['send_payment_advice'] = true;

        // invoice no
        $quotation_no = $quotation->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $quotation->invoice_no = implode("-", $temp_arr);

        try {

            $files = Pdf::loadView('admin.quotation.tax-invoice', $data);
            $file_name = $quotation->invoice_no.".pdf";

            if($request->filled('check_attach_invoice'))
            {
                Mail::send('admin.quotation.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
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
                Mail::send('admin.quotation.payment-mail', $data, function ($message) use ($data, $files, $file_name) {
                        // $message->from(Auth::user()->email);
                        $message->to($data['to_email'])
                                ->cc($data['cc'] ?? [])
                                ->bcc($data['bcc'] ?? [])
                                ->subject($data['subject']);
                });
            }

            // mail send end

            // quotation

            $quotation = Quotation::find($quotation_id);
            $quotation->status = 3;
            $quotation->payment_advice = 2;
            $quotation->save();

            // lead

            if(Lead::find($quotation->lead_id))
            {
                $lead = Lead::find($quotation->lead_id);
                $lead->status = 3;
                $lead->payment_advice = 2;
                $lead->save();
            }

            // log data store start

            LogController::store('quotation', 'Payment Advice Send', $quotation_id);

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

        if($request->filled('quotationId'))
        {
            if(Quotation::where('id', $request->quotationId)->whereIn('status', [3, 4])->exists())
            {
                return response()->json(['status'=>'failed', 'message' => 'Quotation already Approved']);
            }

            $quotationId = $request->quotationId;

            $quotation = Quotation::find($quotationId);

            if ($quotation)
            {           
                $quotation->status = 3;
                $quotation->payment_advice = 2;
                $quotation->save();
                                
                // lead start

                $lead = Lead::find($quotation->lead_id);

                if($lead)
                {                 
                    $lead->status = 3;
                    $lead->payment_advice = 2;
                    $lead->save();     
                }

                // lead end

                // log data store start

                LogController::store('quotation', 'Quotation Approved', $quotationId);

                // log data store end

                return response()->json(['status'=>'success', 'message'=>'Quotation Confirmed successfully']);
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

    // view received payment

    public function received_payment(Request $request)
    {
        $quotationId = $request->input('quotation_id');

        $quotation = Quotation::select('quotations.*')
        // ->leftJoin('quotation_payment_detail', 'quotation_customer_details.id', '=', 'quotation_payment_detail.quotation_id')
        //  ->join('quotation_price_info', 'quotation_customer_details.id', '=', 'quotation_price_info.quotation_id')
        ->where('quotations.id', $quotationId)
        ->first();

        $customerId = $quotation->customer_id;

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

        $service_address = ServiceAddress::where('customer_id', $customerId)->where('id', $quotation->service_address)->first();
        $billing_address = BillingAddress::where('customer_id', $customerId)->where('id', $quotation->billing_address)->first();

        $company = Company::where('id', $quotation->company_id)->first();

        $service = Quotation::where('id', $quotationId)->get();

        // start

        $subtotal = 0;

        foreach($service as $item)
        {
            $subtotal += $item->unit_price * $item->quantity;
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

        // end

        $asiaOptions = PaymentMethod::where('payment_method', "Asia Pay")->get();
        $offlineOptions = PaymentMethod::where('payment_method', "Offline")->get();

        $emailTemplates = EmailTemplate::get();

        $term_condition = TermCondition::where('company_id', $quotation->company_id)->get();

        $imagePath = 'application/public/company_logos/' . $company->company_logo;

        // invoice no
        $quotation_no = $quotation->quotation_no;
        $temp_arr = explode("-", $quotation_no);
        $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
        $quotation->temp_invoice_no = implode("-", $temp_arr);

        return view('admin.quotation.received-payment', compact('quotationId', 'quotation', 'customer', 'company', 'asiaOptions', 'offlineOptions', 'service', 'emailTemplates', 'service_address', 'billing_address', 'term_condition', 'imagePath'));
    }

    // store received payment

    public function received_payment_store(Request $request)
    {
        // return $request->all();

        $rules = [
            'quotationId' => 'required',
            'payment_option' => 'required',
        ];

        $rules_msg = [
            'quotationId' => 'Quotation Id',
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

        if($request->payment_option == "Offline")
        {
            if($request->filled('payment_amount_checkbox') && $request->filled('payment_option_checkbox'))
            {
                $quotation_id = $request->quotationId;
                $quotation = Quotation::find($quotation_id);
                $lead_id = $quotation->lead_id;

                // $quotation = Quotation::where('id', $quotation_id)->first();
                // $quotation_id = $quotation->id;

                $customer_id = $quotation->customer_id;

                $total_amount = $quotation->grand_total;
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

                    // quotation payment detail start

                    $quotationPaymentInfo = new LeadPaymentInfo();
                    $quotationPaymentInfo->quotation_id = $quotation_id;
                    $quotationPaymentInfo->lead_id = $lead_id;
                    $quotationPaymentInfo->customer_id = $customer_id;
                    $quotationPaymentInfo->payment_method = $payment_method;
                    $quotationPaymentInfo->payment_amount = $payment_amount;
                    $quotationPaymentInfo->payment_type = $type;
                    $quotationPaymentInfo->total_amount = $total_amount;
                    $quotationPaymentInfo->payment_remarks = $request->payment_remarks;
                    $quotationPaymentInfo->created_by_id = Auth::user()->id;
                    $quotationPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                    $quotationPaymentInfo->save();

                    // quotation payment detail end

                    // quotation offline payment detail start

                    $payment_option_checkbox = $request->payment_option_checkbox;

                    $insert_offline_payment_data = [];
                    $insert_payment_proof_data = [];

                    for($i=0; $i<count($payment_amount_arr); $i++)
                    {
                        if(!empty($payment_amount_arr[$i]))
                        {
                            $insert_offline_payment_data[] = [
                                'lead_payment_id' => $quotationPaymentInfo->id,
                                'lead_id' => $lead_id,
                                'quotation_id' => $quotation_id,
                                'payment_option' => $payment_option_checkbox[$i],
                                'amount' => $payment_amount_arr[$i],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];

                            $insert_payment_proof_data[] = [
                                'payment_id' => $quotationPaymentInfo->id,
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

                    // quotation offline payment detail end

                    // quotation start

                    $quotation = Quotation::find($quotation_id);
                    $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

                    // invoice no
                    $quotation_no = $quotation->quotation_no;
                    $temp_arr = explode("-", $quotation_no);
                    $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                    $invoice_no = implode("-", $temp_arr);

                    $quotation->status = 4;
                    $quotation->payment_status = $payment_status;
                    $quotation->invoice_no = $invoice_no;
                    $quotation->invoice_date = Carbon::now();
                    $quotation->save();

                    // quotation end

                    // lead start

                    if(Lead::find($quotation->lead_id))
                    {
                        $lead = Lead::find($quotation->lead_id);

                        $lead->status = 4;
                        $lead->invoice_no = $invoice_no;
                        $lead->invoice_date = Carbon::now();
                        $lead->payment_status = $payment_status;
                        $lead->save();
                    }

                    // lead end

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

                    LogController::store('quotation', 'Payment received', $quotation_id);
                    LogController::store('quotation', 'Invoice Created '.$invoice_no, $quotation_id);
                    LogController::store('quotation', 'Sales Order Created from Quotation', $quotation_id);
                    LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $data->id);
               
                    LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no);
                    LogController::store('invoice', 'Payment received from Quotation', $invoice_no);

                    LogController::store('payment', 'Payment received from Quotation '.$quotation->quotation_no.' for Invoice No. '.$invoice_no, $quotation->id);

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
            $quotation_id = $request->quotationId;
            $quotation = Quotation::find($quotation_id);
            $lead_id = $quotation->lead_id;

            if(SalesOrder::where('quotation_id', $quotation->id)->doesntExist())
            {                           
                // quotation start

                $quotation = Quotation::find($quotation_id);
                $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

                // invoice no
                $quotation_no = $quotation->quotation_no;
                $temp_arr = explode("-", $quotation_no);
                $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                $invoice_no = implode("-", $temp_arr);

                $quotation->status = 4;
                $quotation->payment_status = "unpaid";
                $quotation->invoice_no = $invoice_no;
                $quotation->invoice_date = Carbon::now();
                $quotation->save();

                // quotation end

                // lead start

                if(Lead::find($quotation->lead_id))
                {
                    $lead = Lead::find($quotation->lead_id);

                    $lead->status = 4;
                    $lead->invoice_no = $invoice_no;
                    $lead->invoice_date = Carbon::now();
                    $lead->payment_status = "unpaid";
                    $lead->save();
                }

                // lead end

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

                LogController::store('quotation', 'Invoice Created '.$invoice_no, $quotation_id);
                LogController::store('quotation', 'Sales Order Created from Quotation', $quotation_id);
                LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $data->id);

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

    // received payment store and send email

    public function received_payment_send_email(Request $request)
    {
        // return $request->all();

        $rules = [
            'quotationId' => 'required',
            'payment_option' => 'required',
        ];

        $rules_msg = [
            'quotationId' => 'Quotation Id',
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

        if($request->payment_option == "Offline")
        {
            if($request->filled('payment_amount_checkbox') && $request->filled('payment_option_checkbox'))
            {
                $quotation_id = $request->quotationId;
                $quotation = Quotation::find($quotation_id);
                $lead_id = $quotation->lead_id;

                // $quotation = Quotation::where('id', $quotation_id)->first();
                // $quotation_id = $quotation->id;

                $customer_id = $quotation->customer_id;

                $total_amount = $quotation->grand_total;
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

                    // quotation payment detail start

                    $quotationPaymentInfo = new LeadPaymentInfo();
                    $quotationPaymentInfo->quotation_id = $quotation_id;
                    $quotationPaymentInfo->lead_id = $lead_id;
                    $quotationPaymentInfo->customer_id = $customer_id;
                    $quotationPaymentInfo->payment_method = $payment_method;
                    $quotationPaymentInfo->payment_amount = $payment_amount;
                    $quotationPaymentInfo->payment_type = $type;
                    $quotationPaymentInfo->total_amount = $total_amount;
                    $quotationPaymentInfo->payment_remarks = $request->payment_remarks;
                    $quotationPaymentInfo->created_by_id = Auth::user()->id;
                    $quotationPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;

                    $quotationPaymentInfo->save();

                    // quotation payment detail end

                    // quotation offline payment detail start

                    $payment_option_checkbox = $request->payment_option_checkbox;

                    $insert_offline_payment_data = [];
                    $insert_payment_proof_data = [];

                    for($i=0; $i<count($payment_amount_arr); $i++)
                    {
                        if(!empty($payment_amount_arr[$i]))
                        {
                            $insert_offline_payment_data[] = [
                                'lead_payment_id' => $quotationPaymentInfo->id,
                                'lead_id' => $lead_id,
                                'quotation_id' => $quotation_id,
                                'payment_option' => $payment_option_checkbox[$i],
                                'amount' => $payment_amount_arr[$i],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];

                            $insert_payment_proof_data[] = [
                                'payment_id' => $quotationPaymentInfo->id,
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

                    // quotation offline payment detail end

                    // quotation start

                    $quotation = Quotation::find($quotation_id);
                    $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

                    // invoice no
                    $quotation_no = $quotation->quotation_no;
                    $temp_arr = explode("-", $quotation_no);
                    $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                    $invoice_no = implode("-", $temp_arr);

                    $quotation->status = 4;
                    $quotation->payment_status = $payment_status;
                    $quotation->invoice_no = $invoice_no;
                    $quotation->invoice_date = Carbon::now();
                    $quotation->save();

                    // quotation end

                    // lead start

                    if(Lead::find($quotation->lead_id))
                    {
                        $lead = Lead::find($quotation->lead_id);

                        $lead->status = 4;
                        $lead->invoice_no = $invoice_no;
                        $lead->invoice_date = Carbon::now();
                        $lead->payment_status = $payment_status;
                        $lead->save();
                    }

                    // lead end

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

                    $quotation = Quotation::where('id', $quotation_id)->first();
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

                    $data['quotation_id'] = $quotation_id;
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

                    LogController::store('quotation', 'Payment received', $quotation_id);
                    LogController::store('quotation', 'Invoice Created '.$invoice_no, $quotation_id);
                    LogController::store('quotation', 'Sales Order Created from Quotation', $quotation_id);
                    LogController::store('sales_order', 'Sales Order Created from Quotation '.$quotation->quotation_no, $SalesOrder->id);

                    LogController::store('invoice', 'Invoice Created '.$invoice_no, $invoice_no);
                    LogController::store('invoice', 'Payment received from Quotation', $invoice_no);

                    LogController::store('payment', 'Payment received from Quotation '.$quotation->quotation_no.' for Invoice No. '.$invoice_no, $quotation->id);

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
            $quotation_id = $request->quotationId;
            $quotation = Quotation::find($quotation_id);
            $lead_id = $quotation->lead_id;

            if(SalesOrder::where('quotation_id', $quotation->id)->doesntExist())
            {                           
                // quotation start

                $quotation = Quotation::find($quotation_id);
                $quotation_details = QuotationServiceDetail::where('quotation_id', $quotation_id)->get();

                // invoice no
                $quotation_no = $quotation->quotation_no;
                $temp_arr = explode("-", $quotation_no);
                $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                $invoice_no = implode("-", $temp_arr);

                $quotation->status = 4;
                $quotation->payment_status = "unpaid";
                $quotation->invoice_no = $invoice_no;
                $quotation->invoice_date = Carbon::now();
                $quotation->save();

                // quotation end

                // lead start

                if(Lead::find($quotation->lead_id))
                {
                    $lead = Lead::find($quotation->lead_id);

                    $lead->status = 4;
                    $lead->invoice_no = $invoice_no;
                    $lead->invoice_date = Carbon::now();
                    $lead->payment_status = "unpaid";
                    $lead->save();
                }

                // lead end

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

                $quotation = Quotation::where('id', $quotation_id)->first();
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
                
                $data['quotation_id'] = $quotation_id;
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

                LogController::store('quotation', 'Invoice Created '.$invoice_no, $quotation_id);
                LogController::store('quotation', 'Sales Order Created from Quotation', $quotation_id);
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

    // download send payment advice pdf start

    public function payment_advice_view_pdf(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'quotationId' => 'required',
                'payment_amount' => 'required',
                'payment_option' => 'required',
            ],
            [],
            [
                'quotationId' => 'quotation Id',
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
            $quotation_id = $request->quotationId;
            $quotation = Quotation::find($quotation_id);

            $total_amount = $quotation->grand_total;

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

            $data['quotation_id'] = $quotation_id;
            $data['company_id'] = $request->company_id;
            $data['payment_amount'] = $payment_amount;
            $data['payment_method'] = $payment_method;
            $data['total_amount'] = $total_amount;

            // return $data;

            return response()->json(['status'=>'success', 'route'=>route('quotation.payment-advice.view-download-pdf', $data)]);
        }
    }

    public function payment_advice_view_download_pdf(Request $request)
    {
        $data['quotation_id'] = $request->quotation_id;
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

        return view('admin.quotation.view-payment-advice-pdf', $data);
    }

    // download send payment advice pdf end

}
