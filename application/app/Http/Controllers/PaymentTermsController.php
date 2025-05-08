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
            'payment_terms_value' => 'required',
        ], 
        [],
        [
            'payment_terms' => 'Payment Terms',
            'payment_terms_value' => 'Payment Terms Value',
        ]);

        if ( $validators->fails() ) {
            return response()->json( [ 'errors'=> $validators->errors() ] );
        }

        if ( $request->input( 'id' ) ) {
            PaymentTerms::where( 'id', $request->id )->update( [
                'payment_terms' => $request->input( 'payment_terms' ),
                'payment_terms_value' => $request->input( 'payment_terms_value' ),
            ] );

            // log data store start

            LogController::store('payment_terms', 'Payment Terms Updated', $request->payment_terms);

            // log data store end

            return response()->json( [ 'success' => 'Payment Updated successfully!' ] );

        } else {
            $PaymentTerms = new PaymentTerms( [
                'payment_terms' => $request->input( 'payment_terms' ),
                'payment_terms_value' => $request->input( 'payment_terms_value' ),
            ] );
            
            $PaymentTerms->save();

            // log data store start

            LogController::store('payment_terms', 'Payment Terms Added', $request->payment_terms);

            // log data store end

            return response()->json( [ 'success' => 'Payment Terms Added successfully!' ] );
        }

    }

    public function showData()
    {
        $draw = intval($_GET['draw']);
        $start = intval($_GET['start']);
        $length = intval($_GET['length']);
        $search = $_GET['search']['value'];

        $query = PaymentTerms::query();

        // Implement your search logic here
        if (!empty($search)) {
            $query->where('payment_terms', 'like', '%' . $search . '%');
        }

        $totalRecords = $query->count();
        $paymentData = $query->skip($start)->take($length)->get();

        $new_data = [];

        foreach ($paymentData as $key => $item) {
            $action = '<button class="btn btn-primary" onclick="edit_payment_modal(' . $item->id . ')"><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp;' .

                '<form class="delete-form-payment" action="' . route('delete-paymentTerms', $item->id) . '" method="POST" style="display: inline;">'
                . csrf_field()
                . method_field('DELETE')
                . '<button type="submit" class="btn btn-danger delete-btn-payment"><i class="fa fa-trash" aria-hidden="true"></i></button>'
                . '</form>';
            $new_data[] = array(
                'payment_terms' => $item->payment_terms,
                'action' => $action
            );
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $new_data,
        );

        return response()->json($output);
    }

    public function delete( $id ) {
        $payment = PaymentTerms::find( $id );

        if($payment)
        {
            $payment_terms = $payment->payment_terms;

            $payment->delete();

            // log data store start

            LogController::store('payment_terms', 'Payment Terms Deleted', $payment_terms);

            // log data store end

            return response()->json( [
                'status' => 'success',
                'message' => 'Payment Terms deleted successfully',
            ], 200 );
        }
        else
        {
            return response()->json( [
                'status' => 'failed',
                'message' => 'Payment Terms not found',
            ], 200 );
        }
    }

    public function edit() {
        $data = PaymentTerms::find( request()->id );

        return view( 'admin.setting.paymentTerms.edit', compact( 'data' ) );

    }
}
