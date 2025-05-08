<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceCompanyController extends Controller {
    public function index() {
        return view( 'admin.services.company.index' );
    }

    public function create() {
        return view( 'admin.services.company.create' );

    }

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'company_name' => 'required|string|max:255',
            'person_incharge_name' => 'required',
            'contact_number' => 'required' ,
            'email_id' => 'required|email'

        ],
        [
            'contact_number.required' => 'The mobile number field is required.',
            'email.required' => 'The Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'person_incharge_name.required' => 'Please Enter Person Incharge name',
            'company_name.required' => 'Please Enter Company name'

        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
        }

        $company_logo = $request->company_logo->getClientOriginalName();
        $request->company_logo->move(public_path('/company_logos'), $company_logo);

        if ( $request->input('id') ) {
            Company::where( 'id', $request->id )->update( [
                'company_name' => $request->input( 'company_name' ),
                'person_incharge_name' => $request->input( 'person_incharge_name' ),
                'contact_number' => $request->input( 'contact_number' ),
                'email_id' => $request->input( 'email_id' ),
                'description' => $request->input( 'description' ),
                'company_address' => $request->input( 'company_address' ),
                'website' => $request->input( 'company_website' ),
                'company_logo' => $company_logo,

            ] );
            return response()->json( [ 'success' => 'Customer Updated successfully!' ] );

        } else {

            $company = new Company( [
                'company_name' => $request->input( 'company_name' ),
                'person_incharge_name' => $request->input( 'person_incharge_name' ),
                'contact_number' => $request->input( 'contact_number' ),
                'email_id' => $request->input( 'email_id' ),
                'description' => $request->input( 'description' ),
                'company_address' => $request->input( 'company_address' ),
                'website' => $request->input( 'company_website' ),
                'company_logo' => $company_logo,

            ] );
            $company->save();
            return response()->json( [ 'success' => 'Customer registered successfully!' ] );

        }

    }

    public function fetchData() {

        $companyData = Company::get();
        $data = [];
        $new_data = [];
        foreach ( $companyData as $key => $item ) {

            $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_company_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
            $action .= '<a href="#" class="btn btn_delete_crm" onclick="delete_crm_commercial(' . $item->id . ')" data-company-id=" ' .$item->id. ' ">
                    <i class="fa-solid fa-trash me-2 text-red"></i>
                    </a>
                    ';

            $new_data[] = array(
                'sno' => $key + 1,
                'company_name' => $item->company_name,
                'description' => $item->description,
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
        $company = Company::find( $id );
        if ( $company ) {
            $company->delete();

            return response()->json( [ 'success' => 'Company deleted successfully' ], 200 );
        } else {
            return response()->json( [ 'error' => 'Company not found' ], 404 );
        }

    }

    public function edit() {
        $data = Company::find( request()->id );

        return view( 'admin.services.company.edit', compact( 'data' ) );
    }

}
