<?php

namespace App\Http\Controllers;

use App\Models\LanguageSpoken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LanguageSpokenController extends Controller {
    public function index() {
        return view( 'admin.setting.languageSpoken.index' );
    }

    public function create() {
        return view( 'admin.setting.languageSpoken.create' );

    }

    public function store( Request $request ) {
        $validators = Validator::make( $request->all(), [
            'language_name' => 'required|string|max:255',
        ], [
            'language_name.required'=>'Please Enter Language Name'
        ] );
        if ( $validators->fails() ) {
            return response()->json( [ 'errors'=> $validators->errors() ] );
        }

        if ( $request->input( 'id' ) ) {
            LanguageSpoken::where( 'id', $request->id )->update( [
                'language_name' => $request->input( 'language_name' ),

            ] );
            return response()->json( [ 'success' => 'Language updated successfully!' ] );

        } else {
            $language = new LanguageSpoken( [
                'language_name' => $request->input( 'language_name' ),
            ] );
            $language->save();
            return response()->json( [ 'success' => 'Language Added successfully!' ] );

        }

    }

    public function showData() {
        $data[ 'language_list' ] = LanguageSpoken::all();
        $new_data = [];

        foreach ( $data[ 'language_list' ] as $item ) {
            $action = '<button class="btn btn-success btn-sm" onclick="edit_language_modal(' . $item->id . ')"><i class="fas fa-edit" style="font-size: 10px;"></i></button>'.

            '<form class="delete-form-language" action="' . route( 'delete-languageSpoken', $item->id ) . '" method="POST" style="display: inline;">'
            . csrf_field()
            . method_field( 'DELETE' )
            . '<button type="submit" class="btn btn-danger btn-sm delete-btn-language"><i class="fas fa-trash-alt" style="font-size: 10px;"></i></button>'
            . '</form>';
            $new_data[] = array(
                $item->language_name,
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
        $language = LanguageSpoken::findOrFail( $id );
        $language->delete();

        return response()->json( [
            'success' => 'Language deleted successfully',
        ], 200 );
    }

    public function edit() {
        $data = LanguageSpoken::find( request()->id );

        return view( 'admin.setting.languageSpoken.edit', compact( 'data' ) );

    }
}
