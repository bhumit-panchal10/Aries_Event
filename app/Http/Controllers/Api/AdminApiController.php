<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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


class AdminApiController extends Controller
{
    public function adminlogin(Request $request)
    {
        try {
            //dd('hy');
            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            $customer = Admin::where('email', $request->email)->first();
            //  dd($customer);
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found.',
                ], 404);
            }

            if ($customer) {
                // Attempt Driver Login
                if (Auth::guard('admin')->attempt($credentials)) {
                    $authenticatedcustomer = Auth::guard('admin')->user();

                    $data = [
                        "id" => $authenticatedcustomer->id,
                        "first_name" => $authenticatedcustomer->first_name,
                        "email" => $authenticatedcustomer->email,
                        "mobile_number" => $authenticatedcustomer->mobile_number,

                    ];

                    $token = JWTAuth::fromUser($authenticatedcustomer);

                    return response()->json([
                        'success' => true,
                        'message' => 'Admin login successful.',
                        'data' => $data,
                        'authorisation' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ],
                    ], 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Admin credentials.',
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

    public function profiledetails(Request $request)
    {
        try {
            if (Auth::guard('admin')->check()) {


                $request->validate([
                    'admin_id' => 'required|integer'

                ]);

                $Admin = Admin::where('id', $request->admin_id)->first();
                //dd($Admin);
                if (!$Admin) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Admin not found.',
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => [
                        "id" => $Admin->id,
                        "first_name" => $Admin->first_name,
                        "last_name" => $Admin->last_name,
                        "email" => $Admin->email,
                        "mobile_number" => $Admin->mobile_number,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin is not Authorised.',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching profile details.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function profileUpdate(Request $request)
    {
        try {

            if (Auth::guard('admin')->check()) {

                $customer = Auth::guard('admin')->user();

                $request->validate([
                    'admin_id' => 'required'
                ]);

                $admin = Admin::where(['id' => $request->admin_id])->first();

                if (!$admin) {
                    return response()->json([
                        'success' => false,
                        'message' => "admin not found."
                    ]);
                }

                // Start building the Vendor data
                $adminData = [];

                // Add fields conditionally
                if ($request->has('name')) {
                    $adminData["first_name"] = $request->name;
                }
                if ($request->has('email')) {
                    $adminData["email"] = $request->email;
                }
                if ($request->has('mobile_no')) {
                    $adminData["mobile_number"] = $request->mobile_no;
                }



                // Always update 'updated_at'
                $adminData['updated_at'] = now();

                DB::beginTransaction();

                try {

                    Admin::where(['id' => $request->admin_id])->update($adminData);

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => "Admin Profile updated successfully.",

                    ], 200);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    throw $th;
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin is not authorized.',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function change_password(Request $request)
    {
        try {

            if (Auth::guard('admin')->check()) {

                $request->validate(
                    [
                        "admin_id" => 'required',
                        "old_password" => 'required',
                        "new_password" => 'required',
                        "confirm_new_password" => 'required|same:new_password'
                    ],
                    [
                        'admin_id.required' => 'admin ID is required.',
                        'old_password.required' => 'Old Password is required.',
                        'new_password.required' => 'New Password is required.',
                        'new_password.same' => 'New password and confirmation password must match.'
                    ]
                );

                $Admin =  Admin::where(['id' => $request->admin_id])->first();
                if (!$Admin) {
                    return response()->json([
                        'success' => false,
                        'message' => "Admin not found."
                    ]);
                }

                if (Hash::check($request->old_password, $Admin->password)) {

                    $newpassword = $request->new_password;
                    $confirmpassword = $request->confirm_new_password;

                    if ($newpassword == $confirmpassword) {

                        $Admin->update([
                            'password' => Hash::make($confirmpassword)
                            //'is_changepasswordfirsttime' => 1
                        ]);
                        return response()->json([
                            'success' => true,
                            'message' => 'Password updated successfully...',
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'password and confirm password does not match',
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current Password does not match',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin is not Authorised.',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            // If there's an error, rollback any database transactions and return an error response.
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {

        try {
            // Validate the vendorid passed in the request
            $request->validate([
                'admin_id' => 'required|integer'
            ]);
            // Optionally, fetch the vendor by vendorid (if you need to check or log something)
            $Admin = Admin::where('id', $request->admin_id)->first();
            if (!$Admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found.'
                ], 404);
            }
            Auth::logout();
            session()->flush();
            // Optional: If you want to send the vendor details in the response
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out.',
                'Technicial_id' => $Admin->id,  // Including the vendorid in the response
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
