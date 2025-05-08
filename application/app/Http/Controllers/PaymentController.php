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
use App\Models\Company;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\LeadOfflinePaymentDetail;
use App\Models\LeadServices;
use App\Models\PaymentMethod;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\ServiceAddress;
use App\Models\TermCondition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\get;

class PaymentController extends Controller
{
    public function index()
    {
        // $data['customer'] = Lead::groupBy('customer_id')->get();

        $data['customer'] = Crm::all();

        foreach($data['customer'] as $item)
        {
            // $item->pending_invoice = Lead::where('payment_status', '=', 'partial_paid')
            //                             ->where('status', '!=', 5)
            //                             ->where('customer_id', $item->id)
            //                             ->WhereNotNull('invoice_no')
            //                             ->count();

            // $total_amount = Lead::where('customer_id', $item->id)->sum('grand_total');

            $item->pending_invoice = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
                                                ->where('customer_id', $item->id)
                                                ->WhereNotNull('invoice_no')
                                                ->count();

            $total_amount = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
                                        ->where('customer_id', $item->id)
                                        ->WhereNotNull('invoice_no')
                                        ->sum('grand_total');
                                

            // open amount start

            $lead_payment_details = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
                                        ->where('lead_payment_detail.customer_id', $item->id)
                                        ->whereIn('quotations.payment_status', ['partial_paid', 'unpaid'])
                                        ->WhereNotNull('quotations.invoice_no')
                                        ->select('lead_payment_detail.*')
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

            $item->total_amount = $total_amount;
            $item->open_amount = $open_amount;
        }

        // return $data;

        return view('admin.payment.index', $data);
    }

    public function received_payment($id)
    {
        // $data['lead'] = Lead::orderBy('lead_customer_details.id', 'desc')
        //                     ->leftjoin('customers', 'lead_customer_details.customer_id', '=', 'customers.id')
        //                     ->where('lead_customer_details.payment_status', '!=', 'paid')
        //                     ->where('lead_customer_details.status', '!=', 5)
        //                     ->where('lead_customer_details.customer_id', $id)
        //                     ->select('lead_customer_details.*', 'customers.customer_name as customer_name', 'customers.mobile_number as mobile_number', 'customers.email as email')
        //                     ->get();

        $data['lead'] = Quotation::orderBy('quotations.created_at', 'asc')
                            ->leftjoin('customers', 'quotations.customer_id', '=', 'customers.id')
                            ->where('quotations.payment_status', '!=', 'paid')
                            ->WhereNotNull('invoice_no')
                            ->where('quotations.customer_id', $id)
                            ->select('quotations.*', 'customers.customer_name as customer_name', 'customers.mobile_number as mobile_number', 'customers.email as email', 'customers.customer_type', 'customers.individual_company_name')
                            ->get();

        foreach($data['lead'] as $item)
        {
            $total_amount = $item->grand_total;

            // open amount start

            $lead_payment_details = LeadPaymentInfo::where('customer_id', $item->customer_id)
                                                    ->where('quotation_id', $item->id)
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

            $item->open_amount = $open_amount;
        }

        $data['customer_id'] = $id;

        // payment method
        $data['offline_payment_method'] = PaymentMethod::where('payment_method', "Offline")->get();

        $data['emailTemplates'] = EmailTemplate::get();

        // return $data;

        return view('admin.payment.receive', $data);
    }

    public function all_payments()
    {
        return view('admin.payment.all-payments');
    }

    public function all_payments_get_table_data()
    {
        $payment_details = LeadPaymentInfo::leftjoin('customers', 'lead_payment_detail.customer_id', '=', 'customers.id')
                                        ->select('lead_payment_detail.*', 'customers.customer_name as customer_name', 'customers.individual_company_name as company_name')    
                                        ->get();

        $new_data = [];

        foreach ($payment_details as $item) 
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

            $action = '<a href="#" class="view_payment_proof_btn" data-id="'.$item->id.'"><span class="badge bg-info">View payment Proof</span></a>';

            $new_data[] = [
                $quotation->invoice_no ?? "",
                $item->customer_name,
                $item->company_name,
                "$".$item->payment_amount,
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

    public function all_payments_view_payment_proof(Request $request)
    {
        $data['lead_offline_payment_details'] = DB::table('payment_proof')->where('payment_id', $request->id)->get();

        foreach($data['lead_offline_payment_details'] as $item)
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

    // get balance amount

    public static function get_balance_amount($quotation_id)
    {
        $quotation = Quotation::find($quotation_id ?? '');

        if($quotation)
        {
            $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $quotation_id)->get();

            $deposit = 0;
            $balance = 0;
            foreach($lead_payment_detail as $list)
            {
                if($list->payment_status == 1)
                {
                    $deposit += $list->payment_amount;
                }
            }
            $balance = $quotation->grand_total - $deposit;
        }
        else
        {
            $deposit = 0;
            $balance = 0;
        }

        return $balance;
    }

    // get deposit amount

    public static function get_deposit_amount($quotation_id)
    {
        $quotation = Quotation::find($quotation_id ?? '');

        if($quotation)
        {
            $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $quotation_id)->get();

            $deposit = 0;
            $balance = 0;
            foreach($lead_payment_detail as $list)
            {
                if($list->payment_status == 1)
                {
                    $deposit += $list->payment_amount;
                }
            }
            $balance = $quotation->grand_total - $deposit;
        }
        else
        {
            $deposit = 0;
            $balance = 0;
        }

        return $deposit;
    }
}
