<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{
    //

    public function index(){
        $this->data['company'] = Company::get();
        $this->data['terms'] = TermCondition::get();
        // $this->data['data'] = TermCondition::where('id',$id)->first();
        return view('admin.term.index',$this->data);
    }

    public function store(Request $request){

        $term = new TermCondition();
        $term->company_id = $request->company_id;
        $term->term_condition = $request->term_condition;
        $term->save();

        return redirect()->back();
    }

    public function delete($id){
        TermCondition::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Terms Deleted Successfully');
    }
}
