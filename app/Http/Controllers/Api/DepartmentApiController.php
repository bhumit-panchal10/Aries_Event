<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StateMaster;
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


class DepartmentApiController extends Controller

{
    public function DepartmentAdd(Request $request)
    {
        try {

            $request->validate([
                "name" => 'required',
            ]);

            $Department = Department::create([
                'name' => $request->name,
                'created_at' => now()

            ]);
            return response()->json([
                'success' => true,
                'data' => $Department,
                'message' => 'Department Added Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function DepartList(Request $request)
    {
        try {

            $DepartmentMaster = Department::get();
            return response()->json([
                'success' => true,
                'data' => $DepartmentMaster,
                'message' => 'Department Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Departshow(Request $request)
    {
        $request->validate(
            [
                'department_id' => 'required',

            ]
        );
        try {

            $DepartmentMaster = Department::where('id', $request->department_id)->first();
            return response()->json([
                'success' => true,
                'data' => $DepartmentMaster,
                'message' => 'Department Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function DepartUpdate(Request $request)
    {
        $request->validate(
            [
                'department_id' => 'required',
                'name' => 'required',

            ]
        );
        try {

            $Department = Department::find($request->department_id);

            if ($Department) {
                $Department->update([
                    'name' => $request->name,
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Department updated successfully.',
                    'data' => $Department,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function DepartDelete(Request $request)
    {
        try {
            $request->validate([

                "department_id" => 'required'
            ]);
            $DepartmentMaster = Department::find($request->department_id);
            if ($DepartmentMaster) {
                $DepartmentMaster->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Department Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found',
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
