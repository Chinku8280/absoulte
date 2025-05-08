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

        return view('admin.setting.territory.create');

    }

    public function showData()
    {
        $draw = intval($_GET['draw']);
        $start = intval($_GET['start']);
        $length = intval($_GET['length']);
        $search = $_GET['search']['value'];

        $query = Territory::query();

        // Implement your search logic here
        if (!empty($search)) {
            $query->where('territory_name', 'like', '%' . $search . '%');
        }

        $totalRecords = $query->count();
        $territoryData = $query->skip($start)->take($length)->get();

        $new_data = [];

        foreach ($territoryData as $key => $item) {
            $action = '<button class="btn btn-primary" onclick="edit_territory_modal(' . $item->id . ')"><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp;' .
                '<form class="delete-form" action="' . route('delete-territory', $item->id) . '" method="POST" style="display: inline;">'
                . csrf_field()
                . method_field('DELETE')
                . '<button type="submit" class="btn btn-danger delete-btn"><i class="fa fa-trash" aria-hidden="true"></i></button>'
                . '</form>';
            $new_data[] = array(
                'serial' => $key + 1,
                'territory_name' => $item->territory_name,
                'action' => $action
            );
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $new_data,
        );

        echo json_encode($output);
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

            // log data store start

            LogController::store('territory', 'Territory Updated', $request->input( 'territory_name' ));

            // log data store end

            return response()->json( [ 'success' => 'Territory updated successfully!' ] );

        } else {
            $territory = new Territory( [
                'territory_name' => $request->input( 'territory_name' ),
            ] );
            $territory->save();

            // log data store start

            LogController::store('territory', 'Territory Created', $request->input( 'territory_name' ));

            // log data store end

            return response()->json( [ 'success' => 'Territory Added successfully!' ] );
        }

    }

    public function delete( $id ) {
        $territory = Territory::find( $id );

        if($territory)
        {
            $territory_name = $territory->territory_name;

            $territory->delete();

            // log data store start
    
            LogController::store('territory', 'Territory Deleted', $territory_name);
    
            // log data store end
    
            return response()->json( [
                'status' => 'success',
                'message' => 'Territory deleted successfully',
            ], 200 );
        }
        else
        {
            return response()->json( [
                'status' => 'failed',
                'message' => 'Territory not found',
            ], 200 );
        }
    }

    public function edit() {
        $data = Territory::find( request()->id );

        return view( 'admin.setting.territory.edit', compact( 'data' ) );

    }
}
