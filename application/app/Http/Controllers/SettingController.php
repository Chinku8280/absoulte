<?php

namespace App\Http\Controllers;

use App\Models\ConstantSetting;
use App\Models\PaymentMethod;
use App\Models\ServiceType;
use App\Models\SourceSetting;
use App\Models\Tax;
use App\Models\ZoneSetting;
use Google\Service\Books\Resource\Series;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Svg\Tag\Rect;

class SettingController extends Controller
{
    public function index()
    {
        $zone_data = DB::table('zone_settings')->get();
        $salutation_data = DB::table('constant_settings')->get();
        $paymentMethod = PaymentMethod::get();
        $sourceData = SourceSetting::get();
        $tax = Tax::get();

        return view('admin.settings.index', compact('zone_data','salutation_data','paymentMethod', 'tax','sourceData'));
    }

    // saluation start

    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:constant_settings,salutation_name',
        ], [
            'name.required' => 'The salutation name field is required.',
            'name.unique' => 'The salutation name has already exists.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Set an error message in the session
            session()->flash('error_message', $validator->errors()->first('name'));

            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // If validation passes, create and save the new ConstantSetting record
        $constant = new ConstantSetting();
        $constant->salutation_name = $request->name;
        $constant->save();

        // log data store start

        LogController::store('saluation', 'Salutation Created', $request->name);

        // log data store end

        // Set a success message in the session
        session()->flash('success_message', 'Constant created successfully');

        return response()->json(['message' => 'Constant Created successfully', "salutation_id"=>$constant->id]);
    }

    public function salutation_update(Request $request, $id) {

        $request->validate([
            'name' => 'required|string',
            // Add any other validation rules as needed
        ]);

        // Find the record by its ID
        $salutation = ConstantSetting::find($id);

        if (!$salutation) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        // Update the name field
        $salutation->salutation_name = $request->input('name');

        // Save the changes to the database
        $salutation->save();

        // log data store start

        LogController::store('saluation', 'Saluation Updated', $request->input('name'));

        // log data store end

        return response()->json(['message' => 'Record updated successfully'], 200);

    }

    public function salutation_destroy($id)
    {
        $salutation = ConstantSetting::find($id);

        if (!$salutation) {
            return response()->json(['message' => 'Salutation not found'], 404);
        }

        $salutation_name = $salutation->salutation_name;

        $salutation->delete($id);

        // log data store start

        LogController::store('saluation', 'Saluation Deleted', $salutation_name);

        // log data store end

        return response()->json(['message' => 'Salutation  deleted successfully']);
    }

    // saluation end

    // zone start

    public function zone_store(Request $request)
    {
        $zoneNumbers = implode(',', $request->input('zone_number'));

        $zone_data = new ZoneSetting();

        $zone_data->zone_name = $request->input('zone_name');
        $zone_data->postal_code = $zoneNumbers;
        $zone_data->zone_color = $request->input('zone_color');
        $zone_data->status = $request->input('zone_status');

        $zone_data->save();

        // log data store start

        LogController::store('zone', 'Zone Created', $request->input('zone_name'));

        // log data store end

        return redirect()->back();
    }

    public function fetch($id) {

        $zone = ZoneSetting::find($id);
        return view( 'admin.settings.edit', compact( 'zone' ) );

    }

    public function edit($id) {
        $data = ZoneSetting::find(request()->id);
        $zone_list = ZoneSetting::get();

        return view( 'admin.settings.edit', compact( 'data', 'zone_list' ) );
    }

    public function update(Request $request,$id)
    {
        $zone_data = ZoneSetting::find($id);

        $zoneNumbers = implode(',', $request->input('zone_number'));
        $zone_data->zone_name = $request->input('zone_name');
        $zone_data->postal_code = $zoneNumbers;
        $zone_data->zone_color = $request->input('zone_color');
        $zone_data->status = $request->input('zone_status');

        $zone_data->save();

        // log data store start

        LogController::store('zone', 'Zone Updated', $request->input('zone_name'));

        // log data store end

        return redirect()->back();
    }

    public function destroy($id)
    {
        $zone = ZoneSetting::find($id);
        if (!$zone) {
            return response()->json(['message' => 'Zone not found'], 404);
        }

        $zone_name = $zone->zone_name;

        $zone->delete($id);

        // log data store start

        LogController::store('zone', 'Zone Deleted', $zone_name);

        // log data store end

        return response()->json(['message' => 'Zone deleted successfully']);
    }

    // zone end

    // source start

    public function source_store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'name' => 'required|unique:constant_settings,salutation_name',
        ], [
            'name.required' => 'The Source name field is required.',
            'name.unique' => 'The Source name has already exists.',
        ]);

        if ($validator->fails()) {

            session()->flash('error_message', $validator->errors()->first('name'));

            return response()->json(['errors' => $validator->errors()], 422);
        }

        $source = new SourceSetting();
        $source->source_name = $request->name;
        $source->save();

        // log data store start

        LogController::store('source', 'Source Created', $request->name);

        // log data store end

        // session()->flash('success_message', 'Source created successfully');

        return response()->json(['message' => 'Source Created successfully', 'source_id' => $source->id]);
    }

    public function source_update(Request $request, $id) {

        $request->validate([
            'source_name' => 'required|string',

        ]);

        // Find the record by its ID
        $salutation = SourceSetting::find($id);

        if (!$salutation) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        // Update the name field
        $salutation->source_name = $request->input('source_name');

        // Save the changes to the database
        $salutation->save();

        // log data store start

        LogController::store('source', 'Source Updated', $request->source_name);

        // log data store end

        return response()->json(['message' => 'Source updated successfully'], 200);

    }

    public function source_destroy($id)
    {
        $source = SourceSetting::find($id);
        if (!$source) {
            return response()->json(['message' => 'Source not found'], 404);
        }

        $source_name = $source->source_name;

        $source->delete($id);

        // log data store start

        LogController::store('source', 'Source Deleted', $source_name);

        // log data store end

        return response()->json(['message' => 'Source deleted successfully']);
    }

    // source end

    // payment method start

    public function paymentMethodStore(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required',
            'payment_option' => 'required|unique:payment_methods,payment_option',
        ], [
            'payment_method.required' => 'The payment method field is required.',
            'payment_option.required' => 'The payment option field is required.',
            'payment_option.unique' => 'The payment option has already exists.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Set an error message in the session
            session()->flash('error_message', $validator->errors()->first('payment_option'));

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If validation passes, create and save the new PaymentMethod record
        $payment = new PaymentMethod();
        $payment->payment_method = $request->input('payment_method');
        $payment->payment_option = $request->input('payment_option');

        $payment->save();

        // log data store start

        LogController::store('payment_method', 'Payment Method Created', $request->input('payment_option'));

        // log data store end

        // Set a success message in the session
        session()->flash('status', 'success');
        session()->flash('message', 'Payment method created successfully');

        return redirect()->back();
    }

    public function paymentEdit($id) {
        $payment = PaymentMethod::find($id);

        return view( 'admin.settings.payment-edit', compact( 'payment') );
    }

    public function paymentMethodUpdate(Request $request)
    {
        $payment = PaymentMethod::where('id',$request->id)->first();
        $payment->payment_method = $request->input('payment_method');
        $payment->payment_option = $request->input('payment_option');

        $payment->save();

        // log data store start

        LogController::store('payment_method', 'Payment Method Updated', $request->input('payment_option'));

        // log data store end

        session()->flash('status', 'success');
        session()->flash('message', 'Payment method Updated successfully');

        return redirect()->back();
    }

    public function paymentDelete($id){
        $PaymentMethod = PaymentMethod::find($id);

        if($PaymentMethod)
        {
            $payment_option = $PaymentMethod->payment_option;

            $PaymentMethod->delete();

            // log data store start

            LogController::store('payment_method', 'Payment Method Deleted', $payment_option);

            // log data store end

            session()->flash('status', 'success');
            session()->flash('message', 'Payment method Deleted successfully');
        }
        else
        {
            session()->flash('status', 'failed');
            session()->flash('message', 'Payment method Not Found');
        }

        return redirect()->back();
    }

    // payment method end

    // tax start

    public function tax_store(Request $request)
    {
        // return $request->all();

        // DB::table('tax')->delete();

        $tax = new Tax();
        $tax->tax_name = $request->tax_name;
        $tax->tax = $request->tax;
        $tax->from_date = $request->from_date;
        $tax->to_date = $request->to_date;

        $result = $tax->save();

        if($result)
        {
            $taxes = Tax::all();

            // log data store start

            LogController::store('tax', 'Tax Created', $request->tax . "%");

            // log data store end

            return response()->json(['status' => 'success', 'message'=>'Tax added successfully', 'data' => $taxes]);
        }
        else
        {
            return response()->json(['status'=>'failed', 'message'=>'Tax is not added successfully']);
        }
    }

    public function get_tax_table_data()
    {
        $tax = Tax::all();

        $new_data = [];

        foreach ($tax as $key => $item)
        {          
            $action = '<button type="button" class="btn btn-primary cursor-pointer me-2 text-white btn_tax_edit" data-id="'.$item->id.'">
                            <i class="fa-solid fa-pencil"></i>
                        </button>'.
                        '<button type="button" class="btn btn-danger cursor-pointer me-2 text-white btn-tax-delete" data-id="'.$item->id.'">
                            <i class="fa-solid fa-trash"></i>
                        </button>';

            if($item->set_default == 0)
            {
                $action .= '<button type="button" class="btn btn-primary cursor-pointer me-2 text-white btn_tax_default" data-id="'.$item->id.'">
                                Set Default
                            </button>';
            }

            $new_data[] = [
                $item->tax_name,
                $item->tax,
                date('d-m-Y', strtotime($item->from_date)),
                date('d-m-Y', strtotime($item->to_date)),
                $action
            ];
            
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $tax->count(),
            "recordsFiltered" => $tax->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    public function tax_edit(Request $request)
    {
        // return $request->all();

        $tax_id = $request->tax_id;

        $data['tax'] = Tax::find($tax_id);

        return response()->json($data);
    }

    public function tax_update(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'tax_name' => 'required',
                'tax' => 'required',
                'from_date' => 'required',
                'to_date' => 'required'
            ],
            [],
            [
                'tax_name' => 'Tax Name',
                'tax' => 'Tax',
                'from_date' => 'From date',
                'to_date' => 'To Date'
            ]
        );

        if ($validator->fails()) 
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $tax_id = $request->tax_id;

            $tax = Tax::find($tax_id);

            $tax->tax_name = $request->tax_name;
            $tax->tax = $request->tax;
            $tax->from_date = $request->from_date;
            $tax->to_date = $request->to_date;

            $result = $tax->save();

            if($result)
            {
                // log data store start

                LogController::store('tax', 'Tax Updated', $request->tax . "%");

                // log data store end

                return response()->json(['status' => 'success', 'message'=>'Tax Updated successfully']);
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Tax is not Updated successfully']);
            }
        }
    }

    public function tax_destroy($id)
    {
        $tax = Tax::find($id);
        if (!$tax) {
            return response()->json(['message' => 'Tax not found'], 404);
        }

        $tax_value = $tax->tax;

        $tax->delete($id);

        // log data store start

        LogController::store('tax', 'Tax Deleted', $tax_value . "%");

        // log data store end

        return response()->json(['message' => 'Tax deleted successfully']);
    }

    public function tax_set_default(Request $request)
    {
        // return $request->all();

        $taxId = $request->taxId;

        $tax = Tax::find($taxId);

        if($tax)
        {
            foreach (Tax::all() as $key => $item)
            {
                $item->set_default = 0;
                $item->save();
            }
    
            $tax->set_default = 1;
            $tax->save();

            // log data store start

            LogController::store('tax', 'Set Default', $tax->tax . "%");

            // log data store end
    
            return response()->json(['status'=>'success', 'message'=>'Set Default Successfully']);
        }
        else
        {
            return response()->json(['status'=>'failed', 'message' => 'Tax not found']);
        }
    }

    // tax end

    // service type start

    public function service_type_store(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'service_type' => 'required'
            ],
            [],
            []
        );

        if($validator->fails())
        {
            $errors = $validator->errors();

            return response()->json(['status' => 'error', 'errors' => $errors]);
        }
        else
        {
            $service_type = new ServiceType();
            $service_type->service_type = $request->service_type;
            $result = $service_type->save();

            if($result)
            {
                // log data store start

                LogController::store('type_of_service', 'Service Type Created', $request->service_type);

                // log data store end

                return response()->json(['status'=>'success', 'message'=>'Service Type Added Successfully']);
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Service Type is not Added Successfully']);
            }
        }
    }

    public function get_service_type_table_data()
    {
        $service_type = ServiceType::all();

        $new_data = [];

        foreach ($service_type as $key => $item)
        {          
            $action = '<button type="button" class="btn btn-primary cursor-pointer me-2 text-white btn_service_type_edit" data-id="'.$item->id.'">
                            <i class="fa-solid fa-pencil"></i>
                        </button>'.
                        '<button type="button" class="btn btn-danger cursor-pointer me-2 text-white btn_service_type_delete" data-id="'.$item->id.'">
                            <i class="fa-solid fa-trash"></i>
                        </button>';

            $new_data[] = [
                $key+1,
                $item->service_type,
                $action
            ];
            
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $service_type->count(),
            "recordsFiltered" => $service_type->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    public function service_type_delete(Request $request)
    {
        $service_type_id = $request->service_type_id;

        $ServiceType = ServiceType::find($service_type_id);

        if ($ServiceType) 
        {
            $service_type_name = $ServiceType->service_type;

            $ServiceType->delete($service_type_id);

            // log data store start

            LogController::store('type_of_service', 'Service Type Deleted', $service_type_name);

            // log data store end

            return response()->json(['status'=>'success', 'message' => 'Service Type deleted successfully']);
        }
        else
        {
            return response()->json(['status'=>'failed', 'message' => 'Service Type is not deleted successfully']);
        }
    }

    public function service_type_edit(Request $request)
    {
        $service_type_id = $request->service_type_id;

        $data['service_type'] = ServiceType::find($service_type_id);

        return response()->json($data);
    }

    public function service_type_update(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'edit_service_type' => 'required'
            ],
            [],
            []
        );

        if ($validator->fails()) 
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            $edit_service_type_id = $request->edit_service_type_id;

            $service_type = ServiceType::find($edit_service_type_id);
            $service_type->service_type = $request->edit_service_type;
            $result = $service_type->save();

            if($result)
            {
                // log data store start

                LogController::store('type_of_service', 'Service Type Updated', $request->edit_service_type);

                // log data store end

                return response()->json(['status'=>'success', 'message'=>'Service Type Updated Successfully']);
            }
            else
            {
                return response()->json(['status'=>'failed', 'message'=>'Service Type is not Updated Successfully']);
            }
        }
    }

    // service type end

}
