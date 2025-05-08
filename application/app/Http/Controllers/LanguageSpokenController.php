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

            // log data store start

            LogController::store('language', 'Language Updated', $request->language_name);

            // log data store end

            return response()->json( [ 'success' => 'Language updated successfully!' ] );

        } else {
            $language = new LanguageSpoken( [
                'language_name' => $request->input( 'language_name' ),
            ] );
            $language->save();

            // log data store start

            LogController::store('language', 'Language Created', $request->language_name);

            // log data store end

            return response()->json( [ 'success' => 'Language Added successfully!' ] );

        }

    }

    public function showData()
    {
        $draw = intval($_GET['draw']);
        $start = intval($_GET['start']);
        $length = intval($_GET['length']);
        $search = $_GET['search']['value'];

        $query = LanguageSpoken::query();

        // Implement your search logic here
        if (!empty($search)) {
            $query->where('language_name', 'like', '%' . $search . '%');
        }

        $totalRecords = $query->count();
        $languageData = $query->skip($start)->take($length)->get();

        $new_data = [];

        foreach ($languageData as $key => $item) {
            $action = '<button class="btn btn-primary" onclick="edit_language_modal(' . $item->id . ')"><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp;' .

                '<form class="delete-form-language" action="' . route('delete-languageSpoken', $item->id) . '" method="POST" style="display: inline;">'
                . csrf_field()
                . method_field('DELETE')
                . '<button type="submit" class="btn btn-danger delete-btn-language"><i class="fa fa-trash" aria-hidden="true"></i></button>'
                . '</form>';
            $new_data[] = array(
                'language_name' => $item->language_name,
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
        $language = LanguageSpoken::find( $id );

        if($language)
        {
            $language_name = $language->language_name;

            $language->delete();

            // log data store start
    
            LogController::store('language', 'Language Deleted', $language_name);
    
            // log data store end
    
            return response()->json( [
                'status' => 'success',
                'message' => 'Language deleted successfully',
            ], 200 );
        }
        else
        {
            return response()->json( [
                'status' => 'failed',
                'message' => 'Language Not Found',
            ], 200 );
        }
    }

    public function edit() {
        $data = LanguageSpoken::find( request()->id );

        return view( 'admin.setting.languageSpoken.edit', compact( 'data' ) );

    }
}
