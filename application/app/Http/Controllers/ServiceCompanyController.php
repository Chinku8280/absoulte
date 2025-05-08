<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceCompanyController extends Controller
{
    public function index()
    {
        return view('admin.services.company.index');
    }

    public function create()
    {
        return view('admin.services.company.create');
    }

    public function store(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'company_name' => 'required|string|max:255',
                'person_incharge_name' => 'required',
                'contact_number' => 'required',
                'email_id' => 'required|email',
                'company_address' => 'required'
            ],
            [
                'contact_number.required' => 'The mobile number field is required.',
                'email.required' => 'The Email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'person_incharge_name.required' => 'Please Enter Person Incharge name',
                'company_name.required' => 'Please Enter Company name',
                'company_address.required' => 'Please Enter Company Address'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if ($request->hasFile('company_logo')) 
        {
            $company_logo = $request->file('company_logo');
            $company_name = str_replace(" ", "_", $request->input('company_name'));

            $company_logo_orginal_name = $company_logo->getClientOriginalName();
            $ext = $company_logo->extension();

            $company_logo_file = $company_name."_logo.".$ext;

            $company_logo->move(public_path('/company_logos'), $company_logo_file);
        }

        if ($request->hasFile('qr_code')) 
        {
            $qr_code = $request->file('qr_code');
            $company_name = str_replace(" ", "_", $request->input('company_name'));

            $qr_code_orginal_name = $qr_code->getClientOriginalName();
            $ext = $qr_code->extension();

            $qr_code_file = $company_name."_qrCode.".$ext;

            $qr_code->move(public_path('/qr_code'), $qr_code_file);
        }

        
        if ($request->hasFile('stamp')) 
        {
            $stamp = $request->file('stamp');
            $company_name = str_replace(" ", "_", $request->input('company_name'));

            $stamp_orginal_name = $stamp->getClientOriginalName();
            $ext = $stamp->extension();

            $stamp_file = $company_name."_stamp.".$ext;

            $stamp->move(public_path('/stamp'), $stamp_file);
        }

        if ($request->has('id')) 
        {
            $update_data = [
                'company_name' => $request->input('company_name'),
                'person_incharge_name' => $request->input('person_incharge_name'),
                'contact_number' => $request->input('contact_number'),
                'email_id' => $request->input('email_id'),
                'description' => $request->input('description'),
                'company_address' => $request->input('company_address'),
                'website' => $request->input('website'),
                'telephone' => $request->input('telephone'),
                'fax' => $request->input('fax'),
                'co_register_no' => $request->input('co_register_no'),
                'gst_register_no' => $request->input('gst_register_no'),
                'short_name' => $request->input('short_name'),
                'bank_name' => $request->input('bank_name'),
                'ac_number' => $request->input('ac_number'),
                'bank_code' => $request->input('bank_code'),
                'branch_code' => $request->input('branch_code'),
                'uen_no' => $request->input('uen_no'),
            ];

            if ($request->hasFile('company_logo')) 
            {
                $update_data['company_logo'] = $company_logo_file;
            }

            if ($request->hasFile('qr_code')) 
            {
                $update_data['qr_code'] = $qr_code_file;
            }

            if ($request->hasFile('stamp')) 
            {
                $update_data['stamp'] = $stamp_file;
            }

            if($request->filled('gst_required'))
            {
                $update_data['gst_required'] = 1;
            }
            else
            {
                $update_data['gst_required'] = 0;
            }

            Company::where('id', $request->id)->update($update_data);

            if($request->hasFile('invoice_footer_logo'))
            {
                $invoice_footer_logo_data = [];

                $company_name = str_replace(" ", "_", $request->input('company_name'));

                foreach($request->file('invoice_footer_logo') as $invoice_footer_logo)
                {
                    $ext = $invoice_footer_logo->extension();
                    $invoice_footer_logo_file_name = $company_name ."_". rand(100000000, 99999999999).date("YmdHis").".".$ext;

                    $invoice_footer_logo->move(public_path('uploads/invoice_footer_logo'), $invoice_footer_logo_file_name);

                    $invoice_footer_logo_data[] = [
                        'company_id' => $request->id,
                        'invoice_footer_logo' => $invoice_footer_logo_file_name,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }

                if(DB::table('company_invoice_footer_logo')->where('company_id', $request->id)->exists())
                {
                    DB::table('company_invoice_footer_logo')->where('company_id', $request->id)->delete();
                }

                DB::table('company_invoice_footer_logo')->insert($invoice_footer_logo_data);
            }

            // log data store start

            LogController::store('company', 'Company Updated', $request->id);

            // log data store end

            return response()->json(['success' => 'Company Updated successfully!']);
        } 
        else 
        {
            $input_data = [
                'company_name' => $request->input('company_name'),
                'person_incharge_name' => $request->input('person_incharge_name'),
                'contact_number' => $request->input('contact_number'),
                'email_id' => $request->input('email_id'),
                'description' => $request->input('description'),
                'company_address' => $request->input('company_address'),
                'website' => $request->input('website'),
                'telephone' => $request->input('telephone'),
                'fax' => $request->input('fax'),
                'co_register_no' => $request->input('co_register_no'),
                'gst_register_no' => $request->input('gst_register_no'),
                'short_name' => $request->input('short_name'),
                'bank_name' => $request->input('bank_name'),
                'ac_number' => $request->input('ac_number'),
                'bank_code' => $request->input('bank_code'),
                'branch_code' => $request->input('branch_code'),
                'uen_no' => $request->input('uen_no'),
                'created_by' => Auth::user()->id,
                'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name
            ];

            if ($request->hasFile('company_logo')) 
            {
                $input_data['company_logo'] = $company_logo_file;
            }

            if ($request->hasFile('qr_code')) 
            {
                $input_data['qr_code'] = $qr_code_file;
            }

            if ($request->hasFile('stamp')) 
            {
                $input_data['stamp'] = $stamp_file;
            }

            if($request->filled('gst_required'))
            {
                $input_data['gst_required'] = 1;
            }
            else
            {
                $input_data['gst_required'] = 0;
            }

            $company = new Company($input_data);

            $company->save();

            if($request->hasFile('invoice_footer_logo'))
            {
                $invoice_footer_logo_data = [];

                $company_name = str_replace(" ", "_", $request->input('company_name'));

                foreach($request->file('invoice_footer_logo') as $invoice_footer_logo)
                {
                    $ext = $invoice_footer_logo->extension();
                    $invoice_footer_logo_file_name = $company_name ."_". rand(100000000, 99999999999).date("YmdHis").".".$ext;

                    $invoice_footer_logo->move(public_path('uploads/invoice_footer_logo'), $invoice_footer_logo_file_name);

                    $invoice_footer_logo_data[] = [
                        'company_id' => $company->id,
                        'invoice_footer_logo' => $invoice_footer_logo_file_name,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }

                DB::table('company_invoice_footer_logo')->insert($invoice_footer_logo_data);
            }

            // log data store start

            LogController::store('company', 'Company Created', $company->id);

            // log data store end

            return response()->json(['success' => 'Company registered successfully!']);
        }
    }

    public function fetchData()
    {
        $draw = intval($_GET['draw']);
        $start = intval($_GET['start']);
        $length = intval($_GET['length']);
        $search = $_GET['search']['value'];

        $query = Company::query();

        // Implement your search logic here
        if (!empty($search)) {
            $query->where('company_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $totalRecords = $query->count();
        $companyData = $query->skip($start)->take($length)->get();

        $data = [];
        $new_data = [];

        foreach ($companyData as $key => $item) {
            $action = '<a href="#" class="btn btn-primary btn btn-edit_crm" onclick="edit_company_modal(' . $item->id . ')"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;';
            $action .= '<a href="#" class="btn btn-danger btn btn_delete_crm" onclick="delete_crm_commercial(' . $item->id . ')" data-company-id="' . $item->id . '">
            <i class="fa fa-trash" aria-hidden="true"></i>
                </a>';

            $new_data[] = array(
                'sno' => $key + 1,
                'company_id' => $item->id,
                'company_name' => $item->company_name,
                'description' => $item->description,
                'action' => $action
            );
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords, // For now, it's the same as recordsTotal
            'data' => $new_data,
        );

        echo json_encode($output);
    }

    public function delete(Request $request, $id)
    {
        $company = Company::find($id);

        if ($company) 
        {
            $company_invoice_footer_logo = DB::table('company_invoice_footer_logo')->where('company_id', $id);

            if($company_invoice_footer_logo->exists())
            {
                $company_invoice_footer_logo->delete();
            }

            $company->delete();

            // log data store start

            LogController::store('comapny', 'Company Deleted', $id);

            // log data store end
          
            return response()->json(['success' => 'Company deleted successfully'], 200);
        } 
        else 
        {
            return response()->json(['error' => 'Company not found'], 404);
        }
    }

    public function edit()
    {
        $data = Company::find(request()->id);

        $data->company_invoice_footer_logo = DB::table('company_invoice_footer_logo')
                                                ->where('company_id', request()->id)
                                                ->get();

        return view('admin.services.company.edit', compact('data'));
    }
}
