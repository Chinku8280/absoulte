<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\LeadOfflinePaymentDetail;
use App\Models\LeadPaymentInfo;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;
use App\Models\ServiceAddress;
use App\Models\TermCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentApprovalController extends Controller
{
    // view page

    public function index()
    {
        $data['emailTemplates'] = EmailTemplate::get();

        return view('admin.payment-approve.index', $data);
    }

    // get table data

    public function get_table_data()
    {
        $payment_details = LeadPaymentInfo::leftjoin('customers', 'lead_payment_detail.customer_id', '=', 'customers.id')
                                        ->where('lead_payment_detail.payment_status', 0)
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

            // sales order
            $sales_order = SalesOrder::where('quotation_id', $item->quotation_id)->first();

            $invoice_action = '<a href="'.route('finance.view-invoice', $item->quotation_id).'" target="_blank" style="color: black;">'.$quotation->invoice_no ?? "".'</a>';

            // $action = '<a href="#" class="view_payment_proof_btn" data-id="'.$item->id.'" style="margin-right: 10px;"><span class="badge bg-info">View payment Proof</span></a>';                     
            // $action .= '<a href="#" class="approve_payment_btn" data-id="'.$item->id.'" data-quotation_id="'.$item->quotation_id.'" style="margin-right: 10px;"><span class="badge bg-success">Approve</span></a>';
            // $action .= '<a href="#" class="send_received_payment_mail_btn" data-id="'.$item->id.'" data-quotation_id="'.$item->quotation_id.'"><span class="badge bg-info">Send by mail</span></a>';
            
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
                                    <a class="dropdown-item border-bottom view_btn" href="#" data-id="'.$item->id.'">
                                        <i class="fa-solid fa-eye me-2 text-blue"></i>
                                        View
                                    </a>
                                    <a class="dropdown-item border-bottom approve_payment_btn" href="#" data-id="'.$item->id.'" data-quotation_id="'.$item->quotation_id.'">
                                        <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                        Approve
                                    </a>';

                $action .= '</div>
                        </div>';


            $new_data[] = [
                $key+1,             
                $invoice_action,
                $sales_order->id,
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

    // get payment details

    public function get_payment_details(Request $request)
    {
        $payment_id = $request->payment_id;

        $lead_payment_detail = LeadPaymentInfo::find($payment_id);

        // quotation
        $quotation = Quotation::find($lead_payment_detail->quotation_id);

        // sales order
        $sales_order = SalesOrder::where('quotation_id', $lead_payment_detail->quotation_id)->first();

        $lead_payment_detail->invoice_no = $quotation->invoice_no;
        $lead_payment_detail->sales_order_id = $sales_order->id;
        $lead_payment_detail->payment_date = $lead_payment_detail->created_at->format('Y-m-d');      

        $data['lead_payment_detail'] = $lead_payment_detail;

        // payment proof
        $data['payment_proof'] = DB::table('payment_proof')->where('payment_id', $payment_id)->get();

        foreach($data['payment_proof'] as $item)
        {
            if(!empty($item->payment_proof))
            {
                $item->payment_proof_url = asset('application/public/uploads/payment_proof/'.$item->payment_proof);
            }
            else
            {
                $item->payment_proof_url = "";
            }
        }

        return response()->json($data);
    }

    // approve payment

    public function approve_payment(Request $request)
    {
        $payment_id = $request->payment_id;

        $lead_payment_detail = LeadPaymentInfo::find($payment_id);

        if($lead_payment_detail)
        {
            $lead_payment_detail->payment_status = 1;
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

                // log data store start

                LogController::store('payment', 'Payment is Approved for Invoice No. ' .$get_quotation_data->invoice_no, $quotation_id);
                LogController::store('invoice', 'Payment is Approved for Invoice No. ' .$get_quotation_data->invoice_no, $get_quotation_data->invoice_no);

                // log data store end

                return response()->json(['status' => 'success', 'message' => 'Payment is Approved']);
            }
            else
            {
                return response()->json(['status' => 'failed', 'message' => 'Payment is not Approved']);
            }
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'Data not found']);
        }
    }

    public function get_quotation_data(Request $request)
    {
        // return $request->all();

        $quotation_id = $request->quotation_id;
        $payment_id = $request->payment_id;

        $data['quotation'] = Quotation::find($quotation_id);

        $data['lead_payment_detail'] = LeadPaymentInfo::find($payment_id);

        return response()->json($data);
    }

    // approve payment and send email

    public function send_email(Request $request)
    {
        $payment_id = $request->payment_id;

        $lead_payment_detail = LeadPaymentInfo::find($payment_id);

        if($lead_payment_detail)
        {
            $lead_payment_detail->payment_status = 1;
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


                // quotation start

                $quotation = Quotation::find($quotation_id);
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

                LogController::store('payment', 'Payment is Approved and Email Send for Invoice No. ' .$get_quotation_data->invoice_no, $quotation_id);
                LogController::store('invoice', 'Payment is Approved and Email Send for Invoice No. ' .$get_quotation_data->invoice_no, $get_quotation_data->invoice_no);

                // log data store end

                return response()->json(['status' => 'success', 'message' => 'Payment is Approved']);
            }
            else
            {
                return response()->json(['status' => 'failed', 'message' => 'Payment is not Approved']);
            }
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'Data not found']);
        }
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

                // log data store start

                LogController::store('payment', 'Payment is Rejected for Invoice No. ' .$get_quotation_data->invoice_no, $quotation_id);
                LogController::store('invoice', 'Payment is Rejected for Invoice No. ' .$get_quotation_data->invoice_no, $get_quotation_data->invoice_no);

                // log data store end

                return response()->json(['status' => 'success', 'message' => 'Payment is Rejected']);
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
}
