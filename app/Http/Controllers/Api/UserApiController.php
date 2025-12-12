<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityMaster;
use App\Models\User;
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
                    'name' => $User->name,
                    'mobile' => $User->mobile,
                    'address' => $User->address,
                    'departname' => $expo->department->name ?? '',
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
                    'department_id' => $request->department_id,
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
            $User = User::find($request->user_id);
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
}
