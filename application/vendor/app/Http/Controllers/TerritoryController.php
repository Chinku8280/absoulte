<?php

namespace App\Http\Controllers;

use App\Models\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TerritoryController extends Controller {
    public function index() {
        return view( 'admin.setting.territory.index' );
    }

    public function create() {
        return view( 'admin.setting.territory.create' );

    }

    public function showData() {
        $data[ 'territory_list' ] = Territory::all();
        $new_data = [];
        $serial = 1;
        foreach ( $data[ 'territory_list' ] as $item ) {
            $action = '<button class="btn btn-success btn-sm" onclick="edit_territory_modal(' . $item->id . ')"><i class="fas fa-edit" style="font-size: 10px;"></i></button>'.
            '<form class="delete-form" action="' . route( 'delete-territory', $item->id ) . '" method="POST" style="display: inline;">'
            . csrf_field()
            . method_field( 'DELETE' )
            . '<button type="submit" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash-alt" style="font-size: 10px;"></i></button>'
            . '</form>';
            $new_data[] = array(
                'serial' => $serial,
                'territory_name' => $item->territory_name,
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

    public function store( Request $request ) {
        $validators = Validator::make( $request->all(), [
            'territory_name' => 'required|string|max:255',
        ], [
            'territory_name.required'=>'Please Enter Territory Name'
        ] );
        if ( $validators->fails() ) {
            return response()->json( [ 'errors'=> $validators->errors() ] );
        }
        if ( $request->input( 'id' ) ) {
            Territory::where( 'id', $request->id )->update( [
                'territory_name' => $request->input( 'territory_name' ),

            ] );
            return response()->json( [ 'success' => 'Territory updated successfully!' ] );

        } else {
            $territory = new Territory( [
                'territory_name' => $request->input( 'territory_name' ),
            ] );
            $territory->save();
            return response()->json( [ 'success' => 'Territory Added successfully!' ] );

        }

    }

    public function delete( $id ) {
        $territory = Territory::findOrFail( $id );
        $territory->delete();

        return response()->json( [
            'success' => 'Territory deleted successfully',
        ], 200 );
    }

    public function edit() {
        $data = Territory::find( request()->id );

        return view( 'admin.setting.territory.edit', compact( 'data' ) );

    }
}
