<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    //
    public function index(){
        $this->data['templates'] = EmailTemplate::get();
        $this->data['company'] = Company::get();
        return view('admin.emailtemplate.index',$this->data);
    }

    public function store(Request $request){

         // return $request->all();

         $validator = Validator::make(
            $request->all(),
            [
                'company_id' => 'required',
                'title' => 'required',
                'subject' => 'required',
                'cc' => 'nullable',
                'bcc' => 'nullable',
                'body' => 'required',
            ],
            [],
            [
                'company_id' => 'Company',
                'title' => 'Title',
                'subject' => 'Subject',
                'cc' => 'CC',
                'bcc' => 'Bcc',
                'body' => 'Body',
            ]
        );

        if ( $validator->fails() ) {
            return response()->json( ['status'=>'error', 'errors' => $validator->errors() ] );
        }

        if($request->filled('cc'))
        {
            $cc = $request->cc;

            $cc_arr = [];
    
            foreach(json_decode($cc) as $value)
            {
                $temp = $value->value;
    
                array_push($cc_arr, $temp);
            }
    
            $new_cc = implode(',', $cc_arr);
        }
        else
        {
            $new_cc = '';
        }

        $template = new EmailTemplate();
        $template->company_id = $request->company_id;
        $template->title = $request->title;
        $template->subject = $request->subject;
        $template->cc = $new_cc;
        $template->bcc = $request->bcc ?? '';
        $template->body = $request->body;
        $template->save();

        // log data store start

        LogController::store('email_template', 'Email Template Created', $template->id);

        // log data store end

        return response()->json( ['status'=>'success', 'message' => "Email Template Successfully created" ] );

        // return redirect()->back()->with('success',"Email Template Successfully created");

    }

    public function update(Request $request){

        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'company_id' => 'required',
                'title' => 'required',
                'subject' => 'required',
                'cc' => 'nullable',
                'bcc' => 'nullable',
                'body' => 'required',
            ],
            [],
            [
                'company_id' => 'Company',
                'title' => 'Title',
                'subject' => 'Subject',
                'cc' => 'CC',
                'bcc' => 'Bcc',
                'body' => 'Body',
            ]
        );

        if ( $validator->fails() ) {
            return response()->json( ['status'=>'error', 'errors' => $validator->errors() ] );
        }

        if($request->filled('cc'))
        {
            $cc = $request->cc;

            $cc_arr = [];
    
            foreach(json_decode($cc) as $value)
            {
                $temp = $value->value;
    
                array_push($cc_arr, $temp);
            }
    
            $new_cc = implode(',', $cc_arr);
        }
        else
        {
            $new_cc = '';
        }

        $template = EmailTemplate::where('id',$request->id)->first();
        // print_r($request->id); exit;
        $template->company_id = $request->company_id;
        $template->title = $request->title;
        $template->subject = $request->subject;
        $template->cc = $new_cc;
        $template->bcc = $request->bcc ?? '';
        $template->body = $request->body;
        $template->save();

        // return redirect()->back()->with('success',"Email Template Successfully Updated");

        // log data store start

        LogController::store('email_template', 'Email Template Updated', $template->id);

        // log data store end

        return response()->json( ['status'=>'success', 'message' => "Email Template Successfully Updated" ] );
    }

    public function edit(Request $request){
        $this->data['template'] = $test = EmailTemplate::where('id',$request->template_id)->first();
        // print_r($test->id); exit;
        $this->data['company'] = Company::get();
        return view('admin.emailtemplate.edit',$this->data);
    }

    public function destroy($id){
        $template = EmailTemplate::where('id',$id)->delete();

        // log data store start

        LogController::store('email_template', 'Email Template Deleted', $id);

        // log data store end

        return redirect()->back();
    }

    public function test(){
        $this->data['company'] = Company::first();
        return view('pdf',$this->data);
    }
}
