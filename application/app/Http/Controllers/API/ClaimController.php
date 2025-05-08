<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClaimController extends Controller
{
    // claim list

    public function claim_list()
    {
        $user_id = Auth::user()->id;

        $claim_list = DB::table('xin_employee_claim')
                        ->where('employee_id', $user_id)
                        ->Join('xin_claim_type', 'xin_employee_claim.claim_type_id', '=', 'xin_claim_type.claim_type_id')
                        ->select('xin_employee_claim.*', 'xin_claim_type.name')
                        ->get();

        if(!$claim_list->isEmpty())
        {
            foreach($claim_list as $item)
            {
                $item->status = ucfirst($item->status);
                $item->claim_date = date('D, d M Y', strtotime($item->created_at));
            }

            return response()->json([
                'status' => true,
                'message' => 'Claim List',
                'date' => $claim_list
            ]);
        }
        else
        {
            return response()->json([
                'status' => true,
                'message' => 'Data not found',
                'date' => $claim_list
            ]);
        }
    }

    // claim type list

    public function claim_type_list(Request $request)
    {
        $user_id = Auth::user()->id;

        $xin_claim_type = DB::table('xin_claim_type')->get();

        return response()->json([
            'status' => true,
            'message' => 'Claim Type List',
            'data' => $xin_claim_type
        ]);   
    }

    // claim appliaction

    public function claim_application(Request $request)
    {
        // return $request->all();

        try
        {
            $user_id = Auth::user()->id;

            $validator = Validator::make(
                $request->all(),
                [
                    'claim_type_id' => 'required|exists:xin_claim_type,claim_type_id',
                    'claim_amount' => 'required',
                    'claim_description' => 'nullable',
                    'claim_media' => 'nullable'
                ],
                [],
                [
                    'claim_type_id' => 'Claim Type',
                    'claim_amount' => 'Claim Amount',
                    'claim_description' => 'Claim Description',
                    'claim_media' => 'Claim Media'
                ]
            );

            if($validator->fails())
            {
                // $error = $validator->errors();
                // return response()->json(['status'=> false, 'message' => 'error', 'error'=>$error]);

                $error = $validator->errors()->all();

                foreach($error as $item)
                {
                    return response()->json(['status' => false, 'message' => $item]);
                }
            }
            else
            {              
                // claim media start

                if($request->hasFile('claim_media'))
                {
                    $claim_media = $request->file('claim_media');

                    $ext = $claim_media->extension();
                    $claim_media_file = rand(100000000, 99999999999).date("YmdHis").".".$ext;

                    $claim_media->move('hrms/uploads/claims', $claim_media_file);
                }
                else
                {
                    $claim_media_file = "";
                }

                // claim media end

                $insert_data = [
                    'claim_type_id' => $request->claim_type_id,
                    'claim_year' => date('Y'),
                    'amount' => $request->claim_amount,               
                    'employee_id' => $user_id,
                    'claim_description' => $request->claim_description ?? '',
                    'claim_media' => $claim_media_file,
                ];

                $result = DB::table('xin_employee_claim')->insert($insert_data);

                if($result)
                {      
                    return response()->json([
                        'status' => true,
                        'message' => 'Claim Applied Successfully',
                    ]);                   
                }
                else
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed',
                    ]);
                }
            }
        }
        catch (Exception $e) 
        {
            return response()->json(['status'=> false, 'message' => $e->getMessage()]);
        }
    }
}
