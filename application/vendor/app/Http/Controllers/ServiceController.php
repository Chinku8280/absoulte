<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller {
    public function create() {
        $company_list = Company::get();
        return view( 'admin.services.service.create', compact( 'company_list' ) );

    }

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'company' => 'required|',
            'service_name' => 'required',
            'price' => 'required' ,
            'description' => 'required',
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

            ] );
            return response()->json( [ 'success' => 'Service Updated successfully!' ] );

        } else {

            $company = new Services( [
                'company' => $request->input( 'company' ),
                'price' => $request->input( 'price' ),
                'service_name' => $request->input( 'service_name' ),
                'description' => $request->input( 'description' ),

            ] );
            $company->save();
            return response()->json( [ 'success' => 'Service registered successfully!' ] );

        }

    }

    public function fetchData() {

        $serviceData = Services::join( 'company', 'services.company', '=', 'company.id' )
        ->select( 'services.*', 'company.company_name AS company_name' )
        ->get();
        $data = [];
        $new_data = [];
        foreach ( $serviceData as $key => $item ) {

            $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_service_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
            $action .= '<a href="#" class="btn btn_delete_service" onclick="delete_sevice(' . $item->id . ')" data-service-id=" ' .$item->id. ' ">
                    <i class="fa-solid fa-trash me-2 text-red"></i>
                    </a>
                    ';

            $new_data[] = array(
                'sno' => $key + 1,
                'company' => $item->company_name,
                'service_name'=> $item->service_name,
                'price'=> $item->price,
                'action' => $action

            );

        }

        $output = array(
            'draw' => intval( $_GET[ 'draw' ] ),
            'recordsTotal' => count( $data ),
            'recordsFiltered' => count( $data ),
            'data' => $new_data,
        );
        // dd( $output );
        echo json_encode( $output );
    }

    public function delete( Request $request, $id ) {
        $service = Services::find( $id );
        if ( $service ) {
            $service->delete();

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
}
