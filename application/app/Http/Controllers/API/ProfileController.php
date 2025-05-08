<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;

use function Pest\Laravel\json;

class ProfileController extends Controller
{
    // get profile
    public function get_profile(Request $request)
    {
        $user_id = Auth::user()->id;

        $xin_employee = DB::table('xin_employees')
                            ->where('user_id', $user_id)
                            ->select('user_id', 'employee_id', 'username', 'first_name', 'last_name', 'email', 'gender', 'date_of_birth', 'contact_no as phone', 'profile_picture')
                            ->first();

        $xin_employee->full_name = $xin_employee->first_name . " " . $xin_employee->last_name;

        if(!empty($xin_employee->profile_picture))
        {
            $xin_employee->profile_picture = asset("/hrms/uploads/profile/".$xin_employee->profile_picture);
        }
        else
        {
            $xin_employee->profile_picture = "";
        }

        if($xin_employee)
        {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $xin_employee
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
                'data' => $xin_employee
            ]);
        }
    }

    // update profile
    public function update_profile(Request $request)
    {
        // return $request->all();  
        
        try
        {
            $user_id = Auth::user()->id;

            $validator = Validator::make(
                $request->all(),
                [
                    'username' => 'required|unique:users,username,' . $user_id,
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|email|unique:users,email,' . $user_id,
                    'phone' => 'required|digits:8|unique:users,phone,' . $user_id,
                    'gender' => 'required',
                    'date_of_birth' => 'required|date',
                    'profile_picture' => 'nullable',
                ],
                [],
                [
                    'username' => 'Username',
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'gender' => 'Gender',
                    'date_of_birth' => 'Date of Birth',
                    'profile_picture' => 'Profile Picture'
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
                // profile picture start

                if($request->hasFile('profile_picture'))
                {
                    $profile_picture = $request->file('profile_picture');

                    $ext = $profile_picture->extension();
                    $profile_picture_file = "profile_".date("YmdHis").".".$ext;

                    $profile_picture->move('hrms/uploads/profile', $profile_picture_file);
                }
                else
                {
                    $profile_picture_file = "";
                }

                // profile picture end

                $user = User::find($user_id);
                $user->username = $request->username;
                $user->email = $request->email;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->phone = $request->phone;
                $result = $user->save();

                if($result)
                {
                    $update = [
                        'username' => $request->username,
                        'email' => $request->email,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'contact_no' => $request->phone,
                        'date_of_birth' => $request->date_of_birth,
                        'gender' => $request->gender,
                        'profile_picture' => $profile_picture_file,
                    ];

                    $result1 = DB::table('xin_employees')
                                    ->where('user_id', $user_id)                       
                                    ->update($update);

                    if($result1)
                    {
                        return response()->json([
                            'status' => true,
                            'message' => 'Profile Updated Successfully',
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
