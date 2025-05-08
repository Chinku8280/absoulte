<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller {
   

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'uen' => 'required',
            'branch_name' => 'required',
            'personan_incharge_name' => 'required',
            'nick_name' => 'required' ,
            'mobile_number' =>['required', 'regex:/^[0-9]{10}$/'],
            'fax_number' => 'required' ,
            'email' => 'required|email' ,
            'address' => 'required' ,
            'postal_code' => 'required' ,
            'country' => 'required' ,

        ],
    [
        'uen.required' => 'The UEN field is required.',
        'mobile_number.required' => 'The mobile number field is required.',
        'mobile_number.regex' => 'Please enter a valid 10-digit mobile number.',
        'email.required' => 'The Email field is required.',
        'email.email' => 'Please enter a valid email address.',
    ]
    );
        if ( $validator->fails() ) {
            return response()->json( [ 'errors'=>$validator->errors()]);
        }
        $branch = new Branch( [
            'uen' => $request->input( 'uen' ),
            'branch_name' => $request->input( 'branch_name' ),
            'personan_incharge_name' => $request->input( 'personan_incharge_name' ),
            'nick_name' => $request->input( 'nick_name' ),
            'mobile_number' => $request->input( 'mobile_number' ),
            'fax_number' => $request->input( 'fax_number' ),
            'email' => $request->input( 'email' ),
            'address' => $request->input( 'address' ),
            'postal_code' => $request->input( 'postal_code' ),
            'country' => $request->input( 'country' ),
        ] );
        $branch->save();
        return response()->json( [ 'success'=>'Branch created successfully' ] );
    }

    public function branch_list(){
        $branches = Branch::all();
         return response()->json($branches);

    }
}
