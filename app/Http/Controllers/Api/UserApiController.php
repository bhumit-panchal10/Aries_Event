<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityMaster;
use App\Models\User;
use App\Models\ExpoAssignToUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


class UserApiController extends Controller

{

    public function AssignExpolist(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required'
            ]);

            $data = ExpoAssignToUser::with([
                'expomaster.city',
                'expomaster.state'
            ])
                ->where('user_id', $request->user_id)
                ->get()
                ->map(function ($item) {
                    return [
                        'assign_id'   => $item->id,
                        'expo_name'   => $item->expomaster->name ?? '',
                        'expo_date'   => $item->expomaster->date ?? '',
                        'city_name'   => $item->expomaster->city->name ?? '',
                        'state_name'  => $item->expomaster->state->stateName ?? '',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Expo list fetched successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function UserAdd(Request $request)
    {
        try {

            $request->validate([
                "name" => 'required',
                "mobile" => 'required',
                "address" => 'required',
                "department_id" => 'required',
                "password" => 'required',
            ]);

            $User = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'depart_id' => $request->department_id,
                'password' => Hash::make($request->password),
                'created_at' => now()

            ]);
            return response()->json([
                'success' => true,
                'data' => $User,
                'message' => 'User Added Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function UserList(Request $request)
    {
        try {

            $Users = User::with('department')->get();
            foreach ($Users as $User) {
                $data[] = [
                    'Userid' => $User->id,
                    'iStatus' => $User->iStatus,
                    'name' => $User->name,
                    'mobile' => $User->mobile,
                    'expo_count' => $User->expo_count,
                    'address' => $User->address,
                    'departname' => $User->department->name ?? '',
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'User Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Usershow(Request $request)
    {
        $request->validate(
            [
                'user_id' => 'required',

            ]
        );
        try {

            $Users = User::with('department')->where('id', $request->user_id)->first();
            $data[] = [
                'Usersid' => $Users->id,
                'name' => $Users->name,
                'mobile' => $Users->mobile,
                'address' => $Users->address,
                'department' => $Users->department->name ?? '',

            ];
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'User Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function UserUpdate(Request $request)
    {
        $request->validate(
            [
                "user_id" => 'required',
                "name" => 'required',
                "mobile" => 'required',
                "address" => 'required',
                "department_id" => 'required',

            ]
        );
        try {

            $User = User::find($request->user_id);

            if ($User) {
                $User->update([
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'depart_id' => $request->department_id,
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully.',
                    'data' => $User,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function UserDelete(Request $request)
    {
        try {
            $request->validate([

                "user_id" => 'required'
            ]);
            $User = User::where('id', $request->user_id);
            if ($User) {
                $User->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:User,id',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ]);

            $user = User::find($request->user_id);

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:User,id',
                'iStatus' => 'required|in:0,1',
            ]);

            $user = User::find($request->user_id);
            $user->iStatus = $request->iStatus;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => $request->iStatus == 1
                    ? 'User activated successfully'
                    : 'User deactivated successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Userlogin(Request $request)
    {
        try {
            //dd('hy');
            $request->validate([
                'mobile' => 'required',
                'password' => 'required',
            ]);
            $credentials = [
                'mobile' => $request->mobile,
                'password' => $request->password,
            ];

            $customer = User::where('mobile', $request->mobile)->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            if ($customer) {
                // Attempt Driver Login
                if (Auth::guard('api')->attempt($credentials)) {
                    $authenticateduser = Auth::guard('api')->user();

                    $data = [
                        "id" => $authenticateduser->id,
                        "name" => $authenticateduser->name,
                        "mobile" => $authenticateduser->mobile,
                        "address" => $authenticateduser->address,

                    ];

                    $token = JWTAuth::fromUser($authenticateduser);

                    return response()->json([
                        'success' => true,
                        'message' => 'User login successful.',
                        'data' => $data,
                        'authorisation' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ],
                    ], 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid User credentials.',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function user_changePassword(Request $request)
    {
        try {
            $request->validate([
                'user_id'          => 'required|exists:User,id',
                'old_password'     => 'required',
                'new_password'     => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ]);

            $user = auth('api')->user();

            // if (!$user) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized user',
            //     ], 401);
            // }

            $user = User::find($request->user_id);

            // 🔐 Check old password
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Old password is incorrect',
                ], 400);
            }

            // 🔁 Prevent same password
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'New password cannot be same as old password',
                ], 400);
            }

            // ✅ Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function userprofile(Request $request)
    {
        try {

            $request->validate([
                'user_id'          => 'required|exists:User,id'
            ]);
            $Users = User::where('id', $request->user_id)->first();
            return response()->json([
                'success' => true,
                'data' => $Users,
                'message' => 'User Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {

        try {
            // Validate the vendorid passed in the request
            $request->validate([
                'user_id'          => 'required|exists:User,id'
            ]);
            // Optionally, fetch the vendor by vendorid (if you need to check or log something)
            $User = User::where('id', $request->user_id)->first();
            if (!$User) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }
            Auth::logout();
            session()->flush();
            // Optional: If you want to send the vendor details in the response
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out.',
                'User_id' => $User->id,  // Including the vendorid in the response
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token. Unable to logout.',
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
