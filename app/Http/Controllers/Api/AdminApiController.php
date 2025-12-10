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
                        //'Customerdetail' => $data,
                        'authorisation' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ],
                    ], 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Customer credentials.',
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
}
