<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;

class SalesOrderController extends Controller
{
    public function index(){
        $quotation = Quotation::select('quotations.id','quotations.customer_id','quotations.quotation_no','quotations.created_at','customers.id as custm_id','customers.customer_name','customers.email','customers.mobile_number','customers.customer_type')
                    ->leftjoin('customers','quotations.customer_id','=','customers.id')->get();

        foreach ($quotation as $key => $value) {
            $data = Quotation::leftjoin('customers','quotations.customer_id','=','customers.id')->where('quotations.id',$value->id)->first();
            // echo"<pre>"; print_r($data); exit;
        }
        return view('admin.salesOrder.index',compact('quotation','data'));

    }
}
