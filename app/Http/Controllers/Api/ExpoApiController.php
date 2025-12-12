<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityMaster;
use App\Models\ExpoMaster;
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


class ExpoApiController extends Controller

{

    public function CityByState(Request $request)
    {
        $request->validate([
            'stateid' => 'required|integer'
        ]);

        try {
            $cities = CityMaster::where('stateid', $request->stateid)
                ->where('iSDelete', 0)   // optional, if needed
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $cities,
                'message' => 'City list fetched successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function ExpoAdd(Request $request)
    {
        try {

            $request->validate([
                "name" => 'required',
                "industry_id" => 'required',
                "state_id" => 'required',
                "city_id" => 'required',
                "date" => 'required',
            ]);

            $ExpoMaster = ExpoMaster::create([
                'name' => $request->name,
                'industry_id' => $request->industry_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'date' => $request->date,
                'created_at' => now()

            ]);
            return response()->json([
                'success' => true,
                'data' => $ExpoMaster,
                'message' => 'Expo Added Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function ExpoList(Request $request)
    {
        try {

            $ExpoMaster = ExpoMaster::with('state', 'city', 'industry')->get();
            foreach ($ExpoMaster as $expo) {
                $data[] = [
                    'Expoid' => $expo->id,
                    'name' => $expo->name,
                    'date' => $expo->date,
                    'statename' => $expo->state->stateName ?? '',
                    'cityname' => $expo->city->name ?? '',
                    'industryname' => $expo->industry->name ?? '',

                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Expo Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Exposhow(Request $request)
    {
        $request->validate(
            [
                'expo_id' => 'required',

            ]
        );
        try {

            $ExpoMaster = ExpoMaster::with('state', 'city', 'industry')->where('id', $request->expo_id)->first();
            $data[] = [
                'Expoid' => $ExpoMaster->id,
                'date' => $ExpoMaster->date,
                'name' => $ExpoMaster->name,
                'stateid' => $ExpoMaster->state->stateId ?? '',
                'cityid' => $ExpoMaster->city->id ?? '',
                'industryid' => $ExpoMaster->industry->id ?? '',
                'statename' => $ExpoMaster->state->stateName ?? '',
                'cityname' => $ExpoMaster->city->name ?? '',
                'industryname' => $ExpoMaster->industry->name ?? '',

            ];
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Expo Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function ExpoUpdate(Request $request)
    {
        $request->validate(
            [
                "expo_id" => 'required',
                "name" => 'required',
                "date" => 'required',
                "industry_id" => 'required',
                "state_id" => 'required',
                "city_id" => 'required',

            ]
        );
        try {

            $Expo = ExpoMaster::find($request->expo_id);

            if ($Expo) {
                $Expo->update([
                    'name' => $request->name,
                    'date' => $request->date,
                    'industry_id' => $request->industry_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Expo updated successfully.',
                    'data' => $Expo,
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

    public function ExpoDelete(Request $request)
    {
        try {
            $request->validate([

                "expo_id" => 'required'
            ]);
            $ExpoMaster = ExpoMaster::find($request->expo_id);
            if ($ExpoMaster) {
                $ExpoMaster->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Expo Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Expo not found',
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
