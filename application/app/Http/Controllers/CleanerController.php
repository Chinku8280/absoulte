<?php

namespace App\Http\Controllers;

use Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Svg\Tag\Rect;

class CleanerController extends Controller
{
    public function index()
    {
        return view('admin.cleaner.cleaner.index');
    }

    // cleaner start

    public function cleanerData()
    {
        $draw = intval($_GET['draw']);
        $start = intval($_GET['start']);
        $length = intval($_GET['length']);
        $search = $_GET['search']['value'];

        $query = DB::table('xin_employees')
            // ->leftJoin('company', 'xin_employees.company_id', '=', 'company.id')
            ->leftJoin('xin_companies', 'xin_employees.company_id', '=', 'xin_companies.company_id')
            ->leftJoin('zone_settings', function ($join) {
                $join->whereRaw('FIND_IN_SET(LEFT(xin_employees.zipcode, 2), REPLACE(zone_settings.postal_code, " ", ""))');
            })
            ->select(
                'xin_employees.employee_id',
                'xin_companies.name as company_name',
                'xin_employees.first_name',
                'xin_employees.last_name',
                'xin_employees.email',
                'xin_employees.contact_no',
                'xin_employees.date_of_joining',
                'xin_employees.user_id',
                'xin_employees.zipcode',
                'zone_settings.zone_name',
                'zone_settings.zone_color'
            )
            ->where('xin_employees.user_role_id', 10);

        // Implement your search logic here
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                // $query->where('company.company_name', 'like', '%' . $search . '%')
                $query->where('xin_companies.name', 'like', '%' . $search . '%')
                    ->orWhere('xin_employees.first_name', 'like', '%' . $search . '%')
                    ->orWhere('xin_employees.last_name', 'like', '%' . $search . '%')
                    ->orWhere('xin_employees.email', 'like', '%' . $search . '%')
                    ->orWhere('xin_employees.employee_id', 'like', '%' . $search . '%')
                    ->orWhere('xin_employees.contact_no', 'like', '%' . $search . '%')
                    ->orWhere('xin_employees.date_of_joining', 'like', '%' . $search . '%');
            });
        }

        $totalRecords = $query->count();
        $cleanerData = $query->skip($start)->take($length)->get();

        $data = [];
        $new_data = [];

        foreach ($cleanerData as $key => $item) {
            $fullName = $item->first_name . ' ' . $item->last_name;

            if(!empty($item->zone_color))
            {
                $zone_color_html = '<input type="color" value="'.$item->zone_color.'" disabled style="border: none;">';
            }
            else
            {
                $zone_color_html = "";
            }

            $action = '<a href="#" class="btn btn-primary edit_cleaner_btn" data-id="'.$item->user_id.'" style="margin-right: 10px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            $action .= '<a href="#" class="btn btn-primary view_cleaner_btn" data-id="'.$item->user_id.'"><i class="fa fa-eye" aria-hidden="true"></i></a>';

            $new_data[] = array(
                'sno' => $key + 1,
                'employee_id' => $item->employee_id,
                'employee_zipcode' => $item->zipcode,
                'employee_zone_name' => $item->zone_name,
                'employee_zone_color' => $zone_color_html,
                'company_name' => $item->company_name,
                'first_name' => $fullName,
                'email' => $item->email,
                'contact_no' => $item->contact_no,
                'date_of_joining' => $item->date_of_joining,
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

    public function cleaner_edit(Request $request)
    {
        $cleaner_id = $request->cleaner_id;

        $xin_employee = DB::table('xin_employees')
                            ->leftJoin('xin_companies', 'xin_employees.company_id', '=', 'xin_companies.company_id')
                            ->where('xin_employees.user_id', $cleaner_id)
                            ->select('xin_employees.*', 'xin_companies.name as company_name',)
                            ->first();

        $xin_employee->fullName = $xin_employee->first_name . ' ' . $xin_employee->last_name;

        $data['xin_employee'] = $xin_employee;

        return response()->json($data);
    }

    public function cleaner_update(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'cleaner_remarks' => 'required',              
            ],
            [],
            [
                'cleaner_remarks' => 'Remarks',    
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }
        else
        {
            DB::table('xin_employees')->where('user_id', $request->cleaner_id)->update(
                [
                    'remarks' => $request->cleaner_remarks
                ]
            );

            // log data store start

            LogController::store('cleaners', 'Remarks Added', $request->cleaner_id);

            // log data store end

            return response()->json(['status' => 'success', 'message' => 'Data Updated Successfully']);
        }
    }

    // cleaner end

    // team start

    // create team
    public function create() {
        $all_employees = DB::table('xin_employees')
        ->where('user_role_id', 10)
        ->get();
        return view( 'admin.cleaner.team.create', compact( 'all_employees' ) );
    }

    // team store / update
    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'team_name' => 'required',
            'employee_id' => 'required',
            'superviser_emp_id' => 'required'
        ],
        [
            'team_name.required' => 'Please Enter Team name.',
            'employee_id.required' => 'Please Select atleast one Employee'
        ],
        [
            'superviser_emp_id' => 'Superviser'
        ]);

        if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
        }

        $employee = implode(",", $request->input('employee_id'));

        $data = [
            'team_name' => $request->input('team_name'),
            'employee_id' => $employee,
            'superviser_employee_id' => $request->superviser_emp_id
        ];

        if ($request->has('id')) {
            DB::table('xin_team')->where('team_id', $request->input('id'))->update($data);

            // log data store start

            LogController::store('cleaners', 'Team Updated', $request->input('id'));

            // log data store end

            return response()->json(['success' => 'Team updated successfully!']);
        } else {
            $xin_team_id = DB::table('xin_team')->insertGetId($data);

            // log data store start

            LogController::store('cleaners', 'Team Created', $xin_team_id);

            // log data store end

            return response()->json(['success' => 'Team created successfully!']);
        }

    }

    // delete team
    public function delete( Request $request, $id ) {

        $team = DB::table('xin_team')->where('team_id', $id)->first();
        if ( $team ) {
            DB::table('xin_team')->where('team_id', $id)->delete();

            // log data store start

            LogController::store('cleaners', 'Team Deleted', $id);

            // log data store end

            return response()->json( [ 'success' => 'Team deleted successfully' ], 200 );
        } else {
            return response()->json( [ 'error' => 'Team not found' ], 404 );
        }

    }

    // edit team
    public function edit() {
        $data = DB::table('xin_team')->where('team_id', request()->id)->first();
        $all_employees = DB::table('xin_employees')
        ->where('user_role_id', 10)
        ->get();
        $emp=explode(",",$data->employee_id);

        $xin_employees = DB::table('xin_employees')
                            ->whereIn('user_id', $emp)
                            ->get();

        return view( 'admin.cleaner.team.edit', compact( 'data','all_employees','emp', 'xin_employees') );
    }

    public function teamData()
    {
        $draw = intval($_GET['draw']);
        $start = intval($_GET['start']);
        $length = intval($_GET['length']);
        $search = $_GET['search']['value'];

        $query = DB::table('xin_team');

        // Implement your search logic here
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('team_name', 'like', '%' . $search . '%')
                    ->orWhere('employee_id', 'like', '%' . $search . '%');
            });
        }

        $totalRecords = $query->count();
        $teamData = $query->skip($start)->take($length)->get();

        $data = [];
        $new_data = [];

        foreach ($teamData as $key => $item) {
            $action = '<a href="#" class="btn btn-primary btn btn-edit_crm" onclick="edit_team_modal(' . $item->team_id . ')"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;';
            $action .= '<a href="#" class="btn btn-danger btn btn_delete_team" data-team-id="' . $item->team_id . ' ">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>';

            $new_data[] = array(
                'sno' => $key + 1,
                'team_name' => $item->team_name,
                'employee_id' => $item->employee_id,
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

    public function get_superviser(Request $request)
    {
        // return $request->all();

        if($request->filled('emp_id'))
        {
            $emp_id = $request->emp_id;

            $data['xin_employees'] = DB::table('xin_employees')
                                        ->whereIn('user_id', $emp_id)
                                        ->get();
        }
        else
        {
            $data['xin_employees'] = [];
        }
        
        return response()->json($data);
    }

    // team end
}
