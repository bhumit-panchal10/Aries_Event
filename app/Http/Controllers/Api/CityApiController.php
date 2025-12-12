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
use Illuminate\Validation\Rule;


class CityApiController extends Controller

{
    public function CityAdd(Request $request)
    {
        try {
            $request->validate([
                "stateid" => "required",
                "name" => [
                    "required",
                    Rule::unique('City')->where(function ($query) use ($request) {
                        return $query->where('stateid', $request->stateid)
                            ->where('name', $request->name);
                    })
                ],
            ], [
                'name.unique' => 'This city already exists in this state.',
            ]);

            $CityMaster = CityMaster::create([
                'stateid' => $request->stateid,
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

            // Fetch cities with their state
            $cities = CityMaster::with('state')->get();

            // Format response
            $data = $cities->map(function ($city) {
                return [
                    'cityid'     => $city->id,
                    'name'       => $city->name,
                    'statename'  => $city->state->stateName ?? null,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'City Fetch Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }


    public function statelist(Request $request)
    {
        try {
            $request->validate([
                "page" => 'required',
            ]);

            $page = $request->page ?? 1;
            $perPage = 10;

            $StateMaster = StateMaster::select('stateId', 'stateName')
                ->paginate($perPage, ['stateId', 'stateName'], 'page', $page);

            // Create custom clean response
            $response = [
                'success' => true,
                'message' => 'State Fetch Successfully',
                'current_page' => $StateMaster->currentPage(),
                'per_page' => $StateMaster->perPage(),
                'total' => $StateMaster->total(),
                'last_page' => $StateMaster->lastPage(),
                'data' => $StateMaster->items()  // only data
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function Cityshow(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
        ]);

        try {

            // Load city with state relationship
            $city = CityMaster::with('state')->where('id', $request->city_id)->first();

            if (!$city) {
                return response()->json([
                    'success' => false,
                    'message' => 'City not found',
                ], 404);
            }

            // Format response
            $data = [
                'cityid'    => $city->id,
                'name'      => $city->name,
                'statename' => $city->state->stateName ?? null,
                'stateid' => $city->state->stateId ?? null,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'City Fetch Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function CityUpdate(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'state_id' => 'required',
            'name' => [
                'required',
                Rule::unique('City', 'name')
                    ->ignore($request->city_id, 'id')
                    ->where('stateid', $request->state_id),
            ],


        ], [
            'name.unique' => 'This city already exists in this state.',
        ]);

        try {
            $City = CityMaster::find($request->city_id);

            if ($City) {
                $City->update([
                    'stateid' => $request->state_id,
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
                    'message' => 'City not found.',
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
