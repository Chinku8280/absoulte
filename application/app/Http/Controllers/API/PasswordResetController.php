<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class PasswordResetController extends Controller
{
    // forgot password start

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $response = [
                'success' => false,
                'message' => 'We are sorry, but the provided email is not registered with our system. Please check your email',
                'Data' =>[]
            ];
            // return response()->json($response, 422);
            return response()->json($response);
        }

        $otp = rand(100000, 999999);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['user_id' => $user->id, 'token' => Str::random(40), 'otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        Mail::to($user->email)->send(new ResetPasswordMail($passwordReset));

        $response = [
            'success' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'OTP' => $otp,
                'token' => $passwordReset->token,
            ]
        ];

        return response()->json($response, 200);

    }

    public function verifyOtp(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$passwordReset) {

            $response = [
                'success' => false,
                'message' => 'Invalid token or OTP',
                'data' => []
            ];

            return response()->json($response, 422);

        }

        $passwordReset->delete();

        $response = [
            'success' => true,
            'message' => 'OTP verify successfully',
            'data' => []
        ];

        return response()->json($response, 200);

        return response()->json(['message' => 'Password reset successful']);
    }
    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if($validator->fails())
        {
            // $error = $validator->errors();
            // return response()->json(['success'=> false, 'message' => 'error', 'error'=>$error]);

            $error = $validator->errors()->all();

            foreach($error as $item)
            {
                return response()->json(['success' => false, 'message' => $item]);
            }
        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $response = [
                'success' => false,
                'message' => 'User not found',
                'data' => []
            ];

            return response()->json($response, 404);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // update xin_employee
        DB::table('xin_employees')->where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        $response = [
            'success' => true,
            'message' => 'Password updated successfully',
            'data' => []
        ];

        return response()->json($response, 200);
    }

    // forgot password end

    // change password

    public function change_password(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'new_password' => 'required|confirmed',
                'new_password_confirmation' => 'required',
            ],
            [],
            [
                'old_password' => 'Current Password',
                'new_password' => 'New Password',
                'new_password_confirmation' => 'Confirm Password',
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
            $user_id = Auth::user()->id;

            $old_password = $request->old_password;
            $new_password = $request->new_password;

            $user = User::find($user_id);

            if(Hash::check($old_password, $user->password))
            {
                if($old_password != $new_password)
                {
                    $user->password = Hash::make($new_password);
                    $result = $user->save();

                    if($result)
                    {
                        $result1 = DB::table('xin_employees')
                                        ->where('user_id', $user_id)                       
                                        ->update(['password' => Hash::make($new_password)]);

                        if($result1)
                        {
                            return response()->json([
                                'status' => true,
                                'message' => 'Password Changed Successfully',
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
                else
                {
                    return response()->json([
                        'status' => false, 
                        'message' => "Old Password and New Password can not be same"
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'status' => false, 
                    'message' => "Old Password does not match"
                ]);
            }
        }
    }
}
