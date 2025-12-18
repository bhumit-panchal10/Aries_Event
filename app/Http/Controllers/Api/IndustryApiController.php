<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndustryMaster;
use App\Models\ExpoMaster;
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


class IndustryApiController extends Controller

{
    public function IndustryAdd(Request $request)
    {
        try {

            $request->validate([
                "name" => 'required',
            ]);

            $IndustryMaster = IndustryMaster::create([
                'name' => $request->name,
                'created_at' => now()

            ]);
            return response()->json([
                'success' => true,
                'data' => $IndustryMaster,
                'message' => 'Industry Added Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function IndustryList(Request $request)
    {
        try {

            $IndustryMaster = IndustryMaster::get();
            return response()->json([
                'success' => true,
                'data' => $IndustryMaster,
                'message' => 'Industry Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Industryshow(Request $request)
    {
        $request->validate(
            [
                'industry_id' => 'required',

            ]
        );
        try {

            $IndustryMaster = IndustryMaster::where('id', $request->industry_id)->first();
            return response()->json([
                'success' => true,
                'data' => $IndustryMaster,
                'message' => 'Industry Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function IndustryUpdate(Request $request)
    {
        $request->validate(
            [
                'industry_id' => 'required',
                'name' => 'required',

            ]
        );
        try {

            $Industry = IndustryMaster::find($request->industry_id);

            if ($Industry) {
                $Industry->update([
                    'name' => $request->name,
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Industry updated successfully.',
                    'data' => $Industry,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Industry not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function IndustryDelete(Request $request)
    {
        try {
            $request->validate([

                "industry_id" => 'required'
            ]);
            $IndustryMaster = IndustryMaster::find($request->industry_id);
            if ($IndustryMaster) {
                $IndustryMaster->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Industry Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Industry not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function IndustrywiseExpo(Request $request)
    {
        $request->validate(
            [
                'industry_id' => 'required',

            ]
        );
        try {

            $IndustrywiseExpo = ExpoMaster::where('industry_id', $request->industry_id)->get();
            $data = [];
            foreach ($IndustrywiseExpo as $industry) {
                $data[] = [
                    'expoid' => $industry->id,
                    'exponame' => $industry->name,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Industry Wise Expo Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
