<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityMaster;
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


class CityApiController extends Controller

{
    public function CityAdd(Request $request)
    {
        try {

            $request->validate([
                "name" => 'required',
            ]);

            $CityMaster = CityMaster::create([
                'name' => $request->name,
                'created_at' => now()

            ]);
            return response()->json([
                'success' => true,
                'data' => $CityMaster,
                'message' => 'City Added Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function CityList(Request $request)
    {
        try {

            $CityMaster = CityMaster::get();
            return response()->json([
                'success' => true,
                'data' => $CityMaster,
                'message' => 'City Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function statelist(Request $request)
    {
        try {

            $StateMaster = StateMaster::select('stateId', 'stateName')->get();
            return response()->json([
                'success' => true,
                'data' => $StateMaster,
                'message' => 'State Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Cityshow(Request $request)
    {
        $request->validate(
            [
                'city_id' => 'required',

            ]
        );
        try {

            $CityMaster = CityMaster::where('id', $request->city_id)->first();
            return response()->json([
                'success' => true,
                'data' => $CityMaster,
                'message' => 'City Fetch Successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function CityUpdate(Request $request)
    {
        $request->validate(
            [
                'city_id' => 'required',
                'name' => 'required',

            ]
        );
        try {

            $City = CityMaster::find($request->city_id);

            if ($City) {
                $City->update([
                    'name' => $request->name,
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'City updated successfully.',
                    'data' => $City,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Car not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function CityDelete(Request $request)
    {
        try {
            $request->validate([

                "city_id" => 'required'
            ]);
            $CityMaster = CityMaster::find($request->city_id);
            if ($CityMaster) {
                $CityMaster->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'City Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'City not found',
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
