<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'nullable',
            'phone' => 'nullable',
            'password' => 'required',
            'player_id' => 'required'
        ]);

        if ($validator->fails()) {
            // $response = [
            //     'success' => false,
            //     'message' => $validator->errors()
            // ];
            // return response()->json($response, 400);

            $error = $validator->errors()->all();

            foreach($error as $item)
            {
                $response = [
                    'success' => false,
                    'message' => $item
                ];

                return response()->json($response);
            }
        }

        // get user role id
        $roles_id = $this->get_user_roles_id();

        $credentials = [
            'password' => $request->input('password'),
        ];

        if ($request->has('username')) {
            $credentials['username'] = $request->input('username');
            $errorMessage = 'Invalid username or password';
        } elseif ($request->has('phone')) {
            $credentials['phone'] = $request->input('phone');
            $errorMessage = 'Invalid phone number or password';
        } else {
            $response = [
                'success' => false,
                'message' => 'Username or phone number is required'
            ];
            return response()->json($response);
        }

        if (auth()->attempt($credentials)) 
        {
            $user = auth()->user();            

            $xin_employee = DB::table('xin_employees')
                                ->where('user_id', auth()->user()->id)   
                                ->whereIn('user_role_id', $roles_id)                        
                                ->first();

            if($xin_employee)
            {
                // palyer id save start
                
                $db_user = User::find(auth()->user()->id);
                $db_user->player_id = $request->player_id;
                $db_user->save();

                // palyer id save end

                $token = $user->createToken('MyApp')->plainTextToken;

                $user->full_name = $user->first_name . " " . $user->last_name;

                if(!empty($xin_employee->profile_picture))
                {
                    $user->profile_picture = asset("/hrms/uploads/profile/".$xin_employee->profile_picture);
                }
                else
                {
                    $user->profile_picture = "";
                }
    
                $response = [
                    'success' => true,
                    'message' => 'Logged in successfully',
                    'token' => $token,
                    'data' => [
                        'user' => $user->only(['id', 'username', 'first_name', 'last_name', 'full_name', 'email', 'phone', 'profile_picture'])
                        // 'user' => $user
                    ]
                ];
    
                return response()->json($response);
            }
            else
            {
                $response = [
                    'success' => false,
                    'message' => 'You do not have permission to login'
                ];
    
                return response()->json($response);
            }
        } 
        else 
        {
            $response = [
                'success' => false,
                'message' => $errorMessage
            ];

            return response()->json($response);
        }
    }

    public static function get_user_roles_id()
    {
        $xin_user_roles = DB::table('xin_user_roles')
                            ->whereIn('role_name', ['Cleaner', 'Driver', 'Superviser'])
                            ->pluck('role_id')
                            ->toArray();

        return $xin_user_roles;
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = [
            'success' => true,
            'message' => 'Logged out successfully'
        ];

        return response()->json($response, 200);
    }

    // get_player_id

    public function get_player_id(Request $request)
    {
        $user_id = Auth::user()->id;

        if($request->filled('player_id'))
        {
            $player_id = $request->player_id;

            $user = User::find($user_id);

            $user->player_id = $player_id;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Player Id is saved'
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Player Id is required'
            ]);
        }
    }
}
