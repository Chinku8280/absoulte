<?php

namespace App\Http\Controllers;

use App\Models\Crm;
use App\Models\Company;
use App\Models\Quotation;

use Illuminate\Http\Request;

class QuotationController extends Controller {
    public function index() {
        $companyList = Company::get();
        $quotation = Quotation::select('quotations.id','quotations.customer_id','quotations.quotation_no','quotations.created_at','customers.id as custm_id','customers.customer_name','customers.email','customers.mobile_number','customers.customer_type')
                    ->leftjoin('customers','quotations.customer_id','=','customers.id')->get();
        // echo"<pre>"; print_r($quotation); exit;
        foreach ($quotation as $key => $value) {
            $data = Quotation::leftjoin('customers','quotations.customer_id','=','customers.id')->where('quotations.id',$value->id)->first();
        }
        return view( 'admin.quotation.index',compact('companyList','quotation','data') );

    }

    public function create() {
        return view( 'admin.quotation.create' );

    }

    public function search( Request $request ) {
        // dd('hlo');
        $search = $request->search;

        if ( $request->type == '1' ) {
            $customers = Crm::where('customers.customer_type','residential_customer_type')->where(function($query) use($search){
        $query->where('customers.customer_name', 'like', '%' . $search . '%')
              ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
            });
        } else {
            $customers = Crm::where('customers.customer_type', 'commercial_customer_type')
      ->where(function ($query) use ($search) {
        $query->where('customers.customer_name', 'like', '%' . $search . '%')
              ->orWhere('customers.mobile_number', 'like', '%' . $search . '%');
      })
      ->get();

        }
        // echo "<pre>"; print_r($customers); exit;
  return response()->json($customers);
    }

    public function delete($id){
        Quotation::where('id',$id)->delete();
        return redirect()->back();
    }

    public function view($id){
        $this->data['quotation']= $quotation = Quotation::where('id',$id)->first();
        return view('admin.quotation.view',$this->data);
    }

}
