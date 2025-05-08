<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Company;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    //
    public function index(){
        $this->data['templates'] = EmailTemplate::get();
        $this->data['company'] = Company::get();
        return view('admin.emailtemplate.index',$this->data);
    }

    public function store(Request $request){
         
        $template = new EmailTemplate();
        $template->company_id = $request->company_id;
        $template->title = $request->title;
        $template->subject = $request->subject;
        $template->body = $request->body;
        $template->save();

        return redirect()->back()->with('success',"Email Template Successfully created");

    }

    public function edit($id){
        $this->data['template'] = EmailTemplate::where('id',$id)->first();
        return view('admin.emailtamplate.edit',$this->data);
    }

    public function destroy($id){
        $template = EmailTemplate::where('id',$id)->delete();
        return redirect()->back();
    }
    public function test(){
        return view('pdf');
    }
}
