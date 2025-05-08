<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\TermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TermConditionController extends Controller
{
    // view page

    public function index(Request $request)
    {
        $data['company'] = Company::get();
        $data['terms'] = TermCondition::get();

        return view('admin.term.index', $data);
    }

    // get table data

    public function get_table_data()
    {
        $terms = TermCondition::all();

        $data['terms'] = $terms;

        $new_data = [];

        foreach ($data['terms'] as $key => $item)
        {
            $action = '<a href="#" class="btn btn-primary" onclick="showFormModal('.$item->id.')">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="btn btn-danger delete_btn" data-term_id="'.$item->id.'">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>';

            $new_data[] = [
                $key+1,
                str_replace(PHP_EOL, "<br>", $item->term_condition),
                $action
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['terms']->count(),
            "recordsFiltered" => $data['terms']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }


    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'term_condition' => 'required|unique:term_conditions,term_condition,NULL,id,company_id,' . $request->company_id,
        ], [
            'term_condition.unique' => 'The term condition already exists for this company.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Add a custom error message for duplicate entry
            // $validator->errors()->add('term_condition', 'The term condition already exists for this company.');
            // return redirect()->back()->withErrors($validator)->withInput();

            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }

        // If validation passes, create and save the new TermCondition record
        $term = new TermCondition();
        $term->company_id = $request->company_id;
        $term->term_condition = $request->term_condition;
        $term->save();

        // log data store start

        LogController::store('Terms_and_condition', 'Terms and condition Created', $term->id);

        // log data store end

        // Set a success message
        // return redirect()->back()->with('success', 'Term condition added successfully!');

        return response()->json(['status' => 'success', 'message' => "Terms and condition added successfully!"]);
    }

    public function edit(Request $request)
    {
        $this->data['term'] = TermCondition::where('id', $request->termId)->first();
        // print_r($test->id); exit;
        $this->data['company'] = Company::get();
        return view('admin.term.edit', $this->data);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'company_id' => 'required',
                'term_condition' => 'required'
            ],
            [],
            []
        );

        if ($validator->fails()) 
        {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }

        $term = TermCondition::where('id', $request->id)->first();

        $term->company_id = $request->company_id;
        $term->term_condition = $request->term_condition;
        $term->save();

        // log data store start

        LogController::store('Terms_and_condition', 'Terms and condition Updated', $request->id);

        // log data store end

        // return redirect()->back()->with('success', "Terms Successfully Updated");

        return response()->json(['status' => 'success', 'message' => "Terms and condition Successfully Updated!"]);
    }

    // public function delete($id)
    // {
    //     TermCondition::where('id', $id)->delete();
    //     return redirect()->back()->with('success', 'Terms Deleted Successfully');
    // }

    public function delete(Request $request)
    {
        $TermCondition = TermCondition::find($request->term_id);

        if($TermCondition)
        {
            $TermCondition->delete();

            // log data store start

            LogController::store('Terms_and_condition', 'Terms and condition Deleted', $request->term_id);

            // log data store end

            return response()->json(['status' => 'success', 'message' => 'Data Deleted Successfully']);
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'Data is not Found']);
        }
    }
}
