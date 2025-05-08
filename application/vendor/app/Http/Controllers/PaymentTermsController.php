<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentTermsController extends Controller {
    public function index() {
        return view( 'admin.setting.paymentTerms.index' );
    }

    public function create() {
        return view( 'admin.setting.paymentTerms.create' );

    }

    public function store( Request $request ) {
        $validators = Validator::make( $request->all(), [
            'payment_terms' => 'required|string|max:255',
        ], [
            'payment_terms.required'=>'Please Enter Payment Name'
        ] );
        if ( $validators->fails() ) {
            return response()->json( [ 'errors'=> $validators->errors() ] );
        }

        if ( $request->input( 'id' ) ) {
            PaymentTerms::where( 'id', $request->id )->update( [
                'payment_terms' => $request->input( 'payment_terms' ),

             ] );
                return response()->json( [ 'success' => 'Payment Updated successfully!' ] );

        } else {
            $language = new PaymentTerms( [
                'payment_terms' => $request->input( 'payment_terms' ),
            ] );
            $language->save();
            return response()->json( [ 'success' => 'Payment Added successfully!' ] );
        }

    }

    public function showData() {
        $data[ 'payment_list' ] = PaymentTerms::all();
        $new_data = [];

        foreach ( $data[ 'payment_list' ] as $item ) {
            $action = '<button class="btn btn-success btn-sm" onclick="edit_payment_modal(' . $item->id . ')"><i class="fas fa-edit" style="font-size: 10px;"></i></button>'.
            '<form class="delete-form-payment" action="' . route( 'delete-paymentTerms', $item->id ) . '" method="POST" style="display: inline;">'
            . csrf_field()
            . method_field( 'DELETE' )
            . '<button type="submit" class="btn btn-danger btn-sm delete-btn-payment"><i class="fas fa-trash-alt" style="font-size: 10px;"></i></button>'
            . '</form>';
            $new_data[] = array(
                $item->payment_terms,
                $action
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

    public function delete( $id ) {
        $payment = PaymentTerms::findOrFail( $id );
        $payment->delete();

        return response()->json( [
            'success' => 'Payment deleted successfully',
        ], 200 );
    }

    public function edit() {
        $data = PaymentTerms::find( request()->id );

        return view( 'admin.setting.paymentTerms.edit', compact( 'data' ) );

    }
}