<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Crm;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\LeadOfflinePaymentDetail;
use App\Models\LeadPaymentInfo;
use App\Models\PaymentHistory;
use App\Models\PaymentHistoryDetail;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\ServiceAddress;
use App\Models\TermCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentHistoryController extends Controller
{
    public function index()
    {
        return view('admin.payment.payment-history');
    }

    // public function store(Request $request)
    // {
    //     // return $request->all();

    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'payment_proof' => 'required'
    //         ],
    //         [],
    //         [
    //             'payment_proof' => "Payment Proof"
    //         ]
    //     );

    //     if($validator->fails())
    //     {
    //         $error = $validator->errors();

    //         return response()->json(['status' => "error", 'error'=>$error]);
    //     }
    //     else
    //     {
    //         // payment proof start

    //         if($request->hasFile('payment_proof'))
    //         {
    //             $image = $request->file('payment_proof');

    //             $ext = $image->extension();

    //             $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

    //             $image->move(public_path('uploads/payment_proof'), $payment_proof_file);
    //         }

    //         // payment proof end

    //         // payment history start

    //         $payment_history = new PaymentHistory();
    //         $payment_history->customer_id = $request->customer_id;
    //         $payment_history->payment_method = "online";
    //         $payment_history->total_amount = $request->total_amount;
    //         $payment_history->payment_proof = $payment_proof_file;
    //         $result1 = $payment_history->save();

    //         // payment history end

    //         if($result1)
    //         {
    //             $quotation_id = $request->quotation_id;
    //             $lead_id = $request->lead_id;

    //             // payment history details start

    //             for($i=0; $i<count($quotation_id); $i++)
    //             {
    //                 if($request->pay_amount[$i] != 0)
    //                 {
    //                     // $insert_data = [
    //                     //     "payment_history_id" => $payment_history->id,
    //                     //     "quotation_id" => $quotation_id[$i],
    //                     //     "payment_amount" => $request->pay_amount[$i],
    //                     //     'created_at' => Carbon::now(),
    //                     //     'updated_at' => Carbon::now()
    //                     // ];

    //                     $PaymentHistoryDetail = new PaymentHistoryDetail();
    //                     $PaymentHistoryDetail->payment_history_id = $payment_history->id;
    //                     $PaymentHistoryDetail->quotation_id = $quotation_id[$i];
    //                     $PaymentHistoryDetail->payment_amount = $request->pay_amount[$i];
    //                     $PaymentHistoryDetail->created_at = Carbon::now();
    //                     $PaymentHistoryDetail->updated_at = Carbon::now();
    //                     $result2 = $PaymentHistoryDetail->save();

    //                     // $insert_payment_details = [
    //                     //     "lead_id" => $lead_id[$i],
    //                     //     "quotation_id" => $quotation_id[$i],
    //                     //     "customer_id" =>  $request->customer_id,
    //                     //     "payment_method" => "online",
    //                     //     "payment_amount" => $request->pay_amount[$i],
    //                     //     'created_at' => Carbon::now(),
    //                     //     'updated_at' => Carbon::now(),
    //                     //     'created_by_id' => Auth::user()->id,
    //                     //     'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name,
    //                     // ];                                               

    //                     $LeadPaymentInfo = new LeadPaymentInfo();  
    //                     $LeadPaymentInfo->lead_id = $lead_id[$i];
    //                     $LeadPaymentInfo->quotation_id = $quotation_id[$i];
    //                     $LeadPaymentInfo->customer_id =  $request->customer_id;
    //                     $LeadPaymentInfo->payment_method = "online";
    //                     $LeadPaymentInfo->payment_amount = $request->pay_amount[$i];
    //                     $LeadPaymentInfo->created_at = Carbon::now();
    //                     $LeadPaymentInfo->updated_at = Carbon::now();
    //                     $LeadPaymentInfo->created_by_id = Auth::user()->id;
    //                     $LeadPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;
    //                     $result3 = $LeadPaymentInfo->save();

    //                     $insert_payment_proof = [
    //                         'payment_id' => $LeadPaymentInfo->id,
    //                         "lead_id" => $lead_id[$i],
    //                         "quotation_id" => $quotation_id[$i],
    //                         "payment_proof" =>  $payment_proof_file,
    //                         'created_at' => Carbon::now(),
    //                         'updated_at' => Carbon::now(),
    //                     ];

    //                     $result4 = DB::table('payment_proof')->insert($insert_payment_proof);
    //                 }
    //             }             

    //             // payment history details end

    //             if($result2)
    //             {
    //                 // update laed start

    //                 for($i=0; $i<count($quotation_id); $i++)
    //                 {
    //                     if($request->pay_amount[$i] != 0)
    //                     {
    //                         $get_quotation_data = Quotation::find($quotation_id[$i]);

    //                         $quotation_grand_total = $get_quotation_data->grand_total;

    //                         $get_lead_payment_details = LeadPaymentInfo::where('quotation_id', $quotation_id[$i])->get();

    //                         $lead_payment_amount = 0;
    //                         foreach($get_lead_payment_details as $list)
    //                         {
    //                             $lead_payment_amount += $list->payment_amount;
    //                         }

    //                         if($quotation_grand_total == $lead_payment_amount)
    //                         {
    //                             $payment_status = "paid";
    //                         }
    //                         else
    //                         {
    //                             $payment_status = "partial_paid";
    //                         }

    //                         // invoice no
    //                         $quotation_no = $get_quotation_data->quotation_no;
    //                         $temp_arr = explode("-", $quotation_no);
    //                         $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
    //                         $invoice_no = implode("-", $temp_arr);

    //                         $get_quotation_data->invoice_no = $invoice_no;
    //                         $get_quotation_data->payment_status = $payment_status;
    //                         $get_quotation_data->save();

    //                         if(Lead::find($lead_id[$i]))
    //                         {          
    //                             $get_lead_data = Lead::find($lead_id[$i]);
                                
    //                             $get_lead_data->invoice_no = $invoice_no;
    //                             $get_lead_data->payment_status = $payment_status;
    //                             $get_lead_data->save();
    //                         }
    //                     }
    //                 }

    //                 // update lead end

    //                 return response()->json(['status'=>'success', 'message'=>'Payment successfull']);
    //             }
    //             else
    //             {
    //                 $payment_history->delete();
    //                 return response()->json(['status'=>'failed', 'message'=>'Payment failed']);
    //             }
    //         }
    //         else
    //         {
    //             return response()->json(['status'=>'failed', 'message'=>'Payment failed']);
    //         }
    //     }
    // }

    public function store(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'payment_proof' => 'nullable',
                'pay_amount.*' => 'required'
            ],
            [],
            [
                'payment_proof' => "Payment Proof",
                'pay_amount.*' => 'Payment Amount'
            ]
        );

        if($validator->fails())
        {
            $error = $validator->errors();

            return response()->json(['status' => "error", 'error'=>$error]);
        }
        else
        {
            // payment history start

            if($request->total_amount > 0)
            {
                $payment_history = new PaymentHistory();
                $payment_history->customer_id = $request->customer_id;
                $payment_history->payment_method = "offline";
                $payment_history->total_amount = $request->total_amount;                 
                $result1 = $payment_history->save();

                // payment history end

                if($result1)
                {
                    $quotation_id = $request->quotation_id;
                    $lead_id = $request->lead_id;

                    // payment history details start

                    for($i=0; $i<count($quotation_id); $i++)
                    {
                        if($request->pay_amount[$i] != 0)
                        {
                            if($request->hasFile('payment_proof'))
                            {
                                $image = $request->file('payment_proof')[$i] ?? null;

                                if($image)
                                {
                                    $ext = $image->extension();

                                    $payment_proof_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                                    $image->move(public_path('uploads/payment_proof'), $payment_proof_file);     
                                }                      
                            }

                            $PaymentHistoryDetail = new PaymentHistoryDetail();
                            $PaymentHistoryDetail->payment_history_id = $payment_history->id;
                            $PaymentHistoryDetail->quotation_id = $quotation_id[$i];
                            $PaymentHistoryDetail->payment_amount = $request->pay_amount[$i];
                            $PaymentHistoryDetail->created_at = Carbon::now();
                            $PaymentHistoryDetail->updated_at = Carbon::now();
                            $PaymentHistoryDetail->payment_proof = $payment_proof_file ?? '';   
                            $PaymentHistoryDetail->payment_remarks = $request->payment_remarks[$i];    
                            $PaymentHistoryDetail->payment_method = $request->payment_method[$i];            
                            $result2 = $PaymentHistoryDetail->save();                                                                        

                            $LeadPaymentInfo = new LeadPaymentInfo();  
                            $LeadPaymentInfo->lead_id = $lead_id[$i];
                            $LeadPaymentInfo->quotation_id = $quotation_id[$i];
                            $LeadPaymentInfo->customer_id =  $request->customer_id;
                            $LeadPaymentInfo->payment_method = $request->payment_method[$i];
                            $LeadPaymentInfo->payment_amount = $request->pay_amount[$i];
                            $LeadPaymentInfo->created_at = Carbon::now();
                            $LeadPaymentInfo->updated_at = Carbon::now();
                            $LeadPaymentInfo->created_by_id = Auth::user()->id;
                            $LeadPaymentInfo->created_by_name = Auth::user()->first_name . " " . Auth::user()->last_name;
                            $LeadPaymentInfo->payment_remarks = $request->payment_remarks[$i];        
                            $result3 = $LeadPaymentInfo->save();

                            if($request->hasFile('payment_proof'))
                            {
                                if($image)
                                {
                                    $insert_payment_proof = [
                                        'payment_id' => $LeadPaymentInfo->id,
                                        "lead_id" => $lead_id[$i],
                                        "quotation_id" => $quotation_id[$i],
                                        "payment_proof" =>  $payment_proof_file ?? '',
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ];
                                    
                                    $result4 = DB::table('payment_proof')->insert($insert_payment_proof);
                                }
                            }
                        }
                    }             

                    // payment history details end

                    if($result2)
                    {
                        $bulk_msg = "";

                        // update laed start

                        for($i=0; $i<count($quotation_id); $i++)
                        {
                            if($request->pay_amount[$i] != 0)
                            {
                                $get_quotation_data = Quotation::find($quotation_id[$i]);

                                $quotation_grand_total = $get_quotation_data->grand_total;

                                $get_lead_payment_details = LeadPaymentInfo::where('quotation_id', $quotation_id[$i])->get();

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
                                $quotation_no = $get_quotation_data->quotation_no;
                                $temp_arr = explode("-", $quotation_no);
                                $temp_arr[0] = substr_replace($temp_arr[0],"I",-1);
                                $invoice_no = implode("-", $temp_arr);

                                $get_quotation_data->invoice_no = $invoice_no;
                                $get_quotation_data->payment_status = $payment_status;
                                $get_quotation_data->save();

                                if(Lead::find($lead_id[$i]))
                                {          
                                    $get_lead_data = Lead::find($lead_id[$i]);
                                    
                                    $get_lead_data->invoice_no = $invoice_no;
                                    $get_lead_data->payment_status = $payment_status;
                                    $get_lead_data->save();
                                }

                                // log data store start

                                LogController::store('payment', 'Payment Received for Invoice No. ' .$get_quotation_data->invoice_no, $quotation_id[$i]);
                                LogController::store('invoice', 'Payment Received for Invoice No. ' .$get_quotation_data->invoice_no, $get_quotation_data->invoice_no);

                                // log data store end

                                $bulk_msg .= "Payment received successfully for Invoice: " . $get_quotation_data->invoice_no . ", Amount: $" . number_format($request->pay_amount[$i], 2) . ", Date: " . date('d-m-Y')."<br>";                            
                            }
                        }

                        // update lead end   
                        
                        $msg = $bulk_msg;

                        return response()->json(['status'=>'success', 'message'=>$msg]);
                    }
                    else
                    {
                        $payment_history->delete();
                        return response()->json(['status'=>'failed', 'message'=>'Payment failed']);
                    }
                }
                else
                {
                    return response()->json(['status'=>'failed', 'message'=>'Payment failed']);
                }
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Total Payment amount must be greater than 0']);
            }
        }
    }

    public function send_email(Request $request)
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
            if($request->pay_amount > 0)
            {            
                $get_quotation_data = Quotation::find($request->quotation_id);

                FinanceController::temp_store_make_payment($request);


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

                LogController::store('payment', 'Payment Received and Email Send for Invoice No. ' .$get_quotation_data->invoice_no, $request->quotation_id);
                LogController::store('invoice', 'Payment Received and Email Send for Invoice No. ' .$get_quotation_data->invoice_no, $get_quotation_data->invoice_no);

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

    // public function get_table_data()
    // {
    //     $payment_history = PaymentHistory::leftjoin('customers', 'payment_history.customer_id', '=', 'customers.id')
    //                                     ->select('payment_history.*', 'customers.customer_type', 'customers.customer_name', 'customers.individual_company_name as company_name')    
    //                                     ->get();

    //     $new_data = [];

    //     foreach ($payment_history as $item) 
    //     {          
    //         $action = '<a href="'.route('payment-history.show', $item->id).'"><span class="badge bg-info">View</span></a>';
            
    //         $new_data[] = [
    //             ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->company_name,
    //             "$".$item->total_amount,
    //             $item->payment_method,
    //             $item->created_at->format('d-m-Y'),
    //             $action
    //         ];
    //     }

    //     $output = [
    //         "draw" => request()->draw,
    //         "recordsTotal" => $payment_history->count(),
    //         "recordsFiltered" => $payment_history->count(),
    //         "data" => $new_data
    //     ];

    //     echo json_encode($output);
    // }

    // public function show($id)
    // {
    //     $data['payment_history'] = PaymentHistory::find($id);
    //     $data['payment_history_details'] = PaymentHistoryDetail::where('payment_history_id', $id)->get();

    //     foreach($data['payment_history_details'] as $item)
    //     {
    //         $quotation = Quotation::find($item->quotation_id);

    //         $item->grand_total = $quotation->grand_total;
    //         $item->invoice_no = $quotation->invoice_no;
    //     }

    //     // return $data;

    //     return view('admin.payment.view-payment-history', $data);
    // }

    public function get_table_data()
    {
        $payment_details = LeadPaymentInfo::leftjoin('customers', 'lead_payment_detail.customer_id', '=', 'customers.id')
                                            ->select('lead_payment_detail.customer_id', 'customers.customer_type', 'customers.customer_name as customer_name', 'customers.individual_company_name as company_name', 'customers.mobile_number')    
                                            ->groupBy('lead_payment_detail.customer_id')
                                            ->get();

        $new_data = [];

        foreach($payment_details as $key => $item)
        {
            $item->no_of_payments = count(LeadPaymentInfo::where('customer_id', $item->customer_id)->get());

            $action = '<a href="'.route('payment-history.show', $item->customer_id).'"><span class="badge bg-info">View</span></a>';
            $action .= '<a href="'.route('payment-history.log-report', $item->customer_id).'" style="margin-left: 10px;"><span class="badge bg-info">Log Report</span></a>';

            $new_data[] = [
                $key+1,
                ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->company_name,
                "+65 ". $item->mobile_number,
                $item->no_of_payments,
                $action
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $payment_details->count(),
            "recordsFiltered" => $payment_details->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
        
    }

    public function show($id)
    {
        $data['customer_id'] = $id;

        $data['customer'] = Crm::find($id);

        // total outstanding amount

        $balance_amount = $this->get_balance_amount($id);

        $data['customer']->balance_amount = $balance_amount;

        return view('admin.payment.view-payment-history', $data);
    }

    public function view_get_table_data(Request $request)
    {
        $customer_id = $request->customer_id;

        $payment_details = LeadPaymentInfo::where('lead_payment_detail.customer_id', $customer_id)
                                        ->leftjoin('customers', 'lead_payment_detail.customer_id', '=', 'customers.id')
                                        ->where('lead_payment_detail.payment_status', '!=', 0)
                                        ->select('lead_payment_detail.*', 'customers.customer_name as customer_name', 'customers.individual_company_name as company_name')    
                                        ->orderBy('lead_payment_detail.created_at', 'desc')
                                        ->get();

        $new_data = [];

        foreach ($payment_details as $key => $item) 
        {                  
            $quotation = Quotation::find($item->quotation_id);

            $offline_payment_details = LeadOfflinePaymentDetail::where('lead_payment_id', $item->id)->get();
         
            if(!$offline_payment_details->isEmpty())
            {
                $payment_options_arr = [];

                foreach($offline_payment_details as $list)
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
                $payment_method = ucfirst($item->payment_method) . " (". $payment_options .")";
            }
            else
            {
                $payment_method = ucfirst($item->payment_method);
            }

            $invoice_action = '<a href="'.route('finance.view-invoice', $item->quotation_id).'" target="_blank" style="color: black;">'.$quotation->invoice_no ?? "".'</a>';

            $action = '<a href="#" class="view_payment_proof_btn" data-id="'.$item->id.'" style="margin-right: 10px;"><span class="badge bg-info">View payment Proof</span></a>';
            
            if($item->payment_status == "1")
            {
                $action .= '<a href="#" class="reject_payment_btn" data-id="'.$item->id.'"><span class="badge bg-warning">Reject</span></a>';
            }
            elseif($item->payment_status == "2")
            {
                $action .= '<span class="badge bg-danger">Rejected</span>';
            }

            $new_data[] = [
                $key+1,
                $invoice_action,
                "$".number_format($item->payment_amount, 2),
                $payment_method,
                $item->created_at->format('d-m-Y'),
                $item->payment_remarks,
                $item->created_by_name,
                $action
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $payment_details->count(),
            "recordsFiltered" => $payment_details->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    // reject payment

    public function reject_payment(Request $request)
    {
        $payment_id = $request->payment_id;

        $lead_payment_detail = LeadPaymentInfo::find($payment_id);

        if($lead_payment_detail)
        {
            $lead_payment_detail->payment_status = 2;
            $result = $lead_payment_detail->save();

            if($result)
            {
                $quotation_id = $lead_payment_detail->quotation_id;
                $lead_id = $lead_payment_detail->lead_id;

                $get_quotation_data = Quotation::find($quotation_id);

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
                else if($lead_payment_amount == 0)
                {
                    $payment_status = "unpaid";
                }
                else
                {
                    $payment_status = "partial_paid";
                }
                        
                $get_quotation_data->payment_status = $payment_status;
                $get_quotation_data->save();

                if(Lead::find($lead_id))
                {          
                    $get_lead_data = Lead::find($lead_id);
                    
                    $get_lead_data->payment_status = $payment_status;
                    $get_lead_data->save();
                }

                $customer_id = $lead_payment_detail->customer_id;

                $data['balance_amount'] = number_format($this->get_balance_amount($customer_id), 2);

                // log data store start

                LogController::store('payment', 'Payment is Rejected for Invoice No. ' .$get_quotation_data->invoice_no, $quotation_id);
                LogController::store('invoice', 'Payment is Rejected for Invoice No. ' .$get_quotation_data->invoice_no, $get_quotation_data->invoice_no);

                // log data store end

                return response()->json(['status' => 'success', 'message' => 'Payment is Rejected', 'data' => $data]);
            }
            else
            {
                return response()->json(['status' => 'failed', 'message' => 'Payment is not Rejected']);
            }
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'Data not found']);
        }
    }

    public static function get_balance_amount($customer_id)
    {
        // total amount start     

        $quotation = Quotation::WhereNotNull('invoice_no')
                                ->where('quotations.customer_id', $customer_id)                  
                                ->get();

        $total_amount = 0;
        foreach($quotation as $item)
        {
            $total_amount += $item->grand_total;
        }

        // total amount end

        // payment amount start

        $lead_payment_details = LeadPaymentInfo::where('customer_id', $customer_id)->get();

        $payment_amount = 0;
        foreach($lead_payment_details as $list)
        {
            if($list->payment_status == 1)
            {
                $payment_amount += $list->payment_amount;
            }
        }

        // payment amount end

        // total outstanding amount start

        $balance_amount = 0;
        
        if($total_amount == $payment_amount)
        {
            $balance_amount = 0;
        }
        else if($total_amount > $payment_amount)
        {
            $balance_amount = $total_amount - $payment_amount;
        }
        else
        {
            $balance_amount = 0;
        }

        // total outstanding amount end

        return $balance_amount;
    }

    // log report

    public function log_report($customer_id)
    {
        $data['customer'] = Crm::find($customer_id);

        $quotation_id_arr = LeadPaymentInfo::where('customer_id', $customer_id)->groupBy('lead_payment_detail.quotation_id')->pluck('quotation_id')->toArray();

        $log_details = DB::table('log_details')
                        ->where('module', 'payment')
                        ->whereIn('ref_no', $quotation_id_arr)
                        ->paginate(30);

        $data['log_details'] = $log_details;

        return view('admin.payment.log-report', $data);
    }
}
