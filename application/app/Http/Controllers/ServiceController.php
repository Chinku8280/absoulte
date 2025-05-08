<?php

namespace App\Http\Controllers;

use App\Imports\BulkImportClass;
use App\Models\Company;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class ServiceController extends Controller {
    public function create() {
        $company_list = Company::get();
        return view( 'admin.services.service.create', compact( 'company_list' ) );

    }

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'company' => 'required|',
            'service_name' => 'required',
            'price' => 'required'
        ],
        [
            'service_name.required' => 'Please Enter service name.',
            'price.required' => 'Please Enter Person Incharge name',
            'company.required' => 'Please Enter Company name',
            'description.required' => 'Please Enter Description'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
        }

        if ( $request->input( 'id' ) ) {
            Services::where( 'id', $request->id )->update( [
                'company' => $request->input( 'company' ),
                'price' => $request->input( 'price' ),
                'service_name' => $request->input( 'service_name' ),
                'description' => $request->input( 'description' ),
                'man_power' => $request->input( 'man_power_required' ),
                'product_code' => $request->input( 'product_code' ),
                'hour_session' => $request->input( 'hour_session' ),
                'weekly_freq' => $request->input( 'weekly_freq' ),
                'total_session' => $request->input( 'total_session' ),
            ] );

            // log data store start

            LogController::store('service', 'Service Updated', $request->id);

            // log data store end

            return response()->json( [ 'success' => 'Service Updated successfully!' ] );

        } else {
            $company = new Services( [
                'company' => $request->input( 'company' ),
                'price' => $request->input( 'price' ),
                'service_name' => $request->input( 'service_name' ),
                'description' => $request->input( 'description' ),
                'man_power' => $request->input( 'man_power_required' ),
                'product_code' => $request->input( 'product_code' ),
                'hour_session' => $request->input( 'hour_session' ),
                'weekly_freq' => $request->input( 'weekly_freq' ),
                'total_session' => $request->input( 'total_session' ),
                'created_by' => Auth::user()->id,
                'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name
            ] );
            $company->save();

            // log data store start

            LogController::store('service', 'Service Created', $company->id);

            // log data store end

            return response()->json( [ 'success' => 'Service registered successfully!' ] );

        }

    }

    public function fetchData()
    {
        $draw = intval($_GET['draw']);
        $start = intval($_GET['start']);
        $length = intval($_GET['length']);
        $search = $_GET['search']['value'];

        $query = Services::join('company', 'services.company', '=', 'company.id')
            ->select('services.*', 'company.company_name AS company_name');

        // Implement your search logic here
        if (!empty($search)) {
            $query->where('company.company_name', 'like', '%' . $search . '%')
                ->orWhere('services.service_name', 'like', '%' . $search . '%')
                ->orWhere('services.price', 'like', '%' . $search . '%');
        }

        $totalRecords = $query->count();
        $serviceData = $query->skip($start)->take($length)->get();

        $data = [];
        $new_data = [];

        $currentPage = floor($start / $length) + 1; // Calculate current page number

        foreach ($serviceData as $key => $item) {
            $sno = $key + 1 + ($currentPage - 1) * $length; // Calculate serial number
            $action = '<a href="#" class="btn btn-primary btn btn-edit_crm" onclick="edit_service_modal(' . $item->id . ')"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;';
            
            // $action .= '<a href="#" class="btn btn-danger btn btn_delete_service" onclick="delete_sevice(' . $item->id . ')" data-service-id="' . $item->id . ' ">
            // <i class="fa fa-trash" aria-hidden="true"></i>
            //         </a>';

            $action .= '<a href="#" class="btn btn-danger btn btn_delete_service" data-service-id="' . $item->id . ' ">
            <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>';

            $new_data[] = array(
                'sno' => $sno,
                'company' => $item->company_name,
                'service_name' => $item->service_name,
                'service_description' => $item->description,
                'price' => "$".number_format($item->price, 2),
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

    public function delete(Request $request, $id ) {
        $service = Services::find( $id );
        if ( $service ) {
            $service->delete();

            // log data store start

            LogController::store('service', 'Service Deleted', $request->id);

            // log data store end

            return response()->json( [ 'success' => 'service deleted successfully' ], 200 );
        } else {
            return response()->json( [ 'error' => 'service not found' ], 404 );
        }

    }

    public function edit() {
        $data = Services::find( request()->id );
        $company_list = Company::get();
        return view( 'admin.services.service.edit', compact( 'data', 'company_list' ) );
    }

    public function uploadBulkService(Request $request)
    {
        // return $request->all();

        $request->validate([
            'serviceFile' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('serviceFile');

        // Process the Excel file and save data to the database
        Excel::import(new BulkImportClass, $file);

        // log data store start

        LogController::store('service', 'Bulk Services Created');

        // log data store end

        return response()->json( [ 'status' => 'success', 'message' => 'Bulk service uploaded successfully.' ] );
    }
    public function downloadSampleFile()
    {
        $filePath = public_path('uploads/service_assets/Bulk_service_sample_file.xlsx');

        $fileName = 'Bulk_service_sample_file.xlsx';

        return Response::download($filePath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
