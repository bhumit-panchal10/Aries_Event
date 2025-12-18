<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityMaster;
use App\Models\Visitor;
use App\Models\Visitorvisit;
use App\Models\ExpoMaster;
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
use Illuminate\Validation\Rule;



class VisitorApiController extends Controller

{
    public function visitoradd(Request $request)
    {
        try {

            DB::beginTransaction();

            $request->validate([
                'mobileno' => [
                    'required',
                    Rule::unique('visitor')->where(function ($query) use ($request) {
                        return $query->where('expo_id', $request->expoid)
                            ->where('iSDelete', 0);
                    }),
                ],
                'companyname' => 'nullable',
                'name'        => 'nullable',
                'email'       => 'nullable|email',
                'stateid'     => 'nullable',
                'cityid'      => 'nullable',
                'userid'      => 'required',
                'expo_slugname' => 'required',
                'username'    => 'nullable',
            ]);
            $expomaster = ExpoMaster::where('slugname', $request->expo_slugname)->first();


            // 1️⃣ Insert Visitor
            $visitor = Visitor::create([
                'mobileno'    => $request->mobileno,
                'companyname' => $request->companyname,
                'name'        => $request->name,
                'email'       => $request->email,
                'stateid'     => $request->stateid,
                'cityid'      => $request->cityid,
                'user_id'     => $request->userid,
                'expo_id'     => $expomaster->id,
                'iStatus'     => 1,
                'iSDelete'    => 0,
                'enter_by'    => $request->username,
            ]);

            // 2️⃣ Insert Visitor Visit
            Visitorvisit::create([
                'visitor_id' => $visitor->id,
                'expo_id'    => $expomaster->id,
                'user_id'    => $request->userid,
            ]);

            // 3️⃣ Today count
            $visitortodaycount = Visitorvisit::where('expo_id', $expomaster->id)
                ->where('user_id', $request->userid)
                ->whereDate('created_at', now()->toDateString())
                ->count();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $visitor,
                'today_visitor_count' => $visitortodaycount,
                'message' => 'Visitor Added Successfully',
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getByMobile(Request $request)
    {
        try {

            $request->validate([
                'mobileno' => 'required',
                'expo_slugname'   => 'nullable',
                'userid'   => 'required',
            ]);
            $expomaster = ExpoMaster::where('slugname', $request->expo_slugname)->first();


            // 1️⃣ Find visitor
            $visitor = Visitor::where('mobileno', $request->mobileno)
                ->where('iSDelete', 0)
                ->first();

            if (!$visitor) {
                return response()->json([
                    'success' => false,
                    'message' => 'No record found for this mobile number',
                ], 404);
            }

            // 2️⃣ Detect if any update fields are sent
            $hasUpdateData = $request->filled([
                'companyname',
                'name',
                'email',
                'stateid',
                'cityid'
            ]);

            // 3️⃣ Update visitor (safe update)
            if ($hasUpdateData) {
                $visitor->update([
                    'companyname' => $request->companyname ?? $visitor->companyname,
                    'name'        => $request->name ?? $visitor->name,
                    'email'       => $request->email ?? $visitor->email,
                    'stateid'     => $request->stateid ?? $visitor->stateid,
                    'cityid'      => $request->cityid ?? $visitor->cityid,
                    'user_id'     => $request->userid,
                ]);
            }

            // 4️⃣ If expoid NOT provided → only update visitor
            if (!$request->filled('expoid')) {
                return response()->json([
                    'success' => true,
                    'data' => $visitor,
                    'message' => $hasUpdateData
                        ? 'Visitor Updated Successfully'
                        : 'Visitor fetched successfully',
                ], 200);
            }

            // 5️⃣ Check visit exists for same expo
            $visitExists = Visitorvisit::where('visitor_id', $visitor->id)
                ->where('expo_id', $expomaster->id)
                ->exists();

            if ($visitExists) {
                return response()->json([
                    'success' => true,
                    'data' => $visitor,
                    'message' => $hasUpdateData
                        ? 'Visitor Updated Successfully'
                        : 'Number already exists for this expo',
                ], 200);
            }

            // 6️⃣ Create new visit
            Visitorvisit::create([
                'visitor_id' => $visitor->id,
                'expo_id'    => $expomaster->id,
                'user_id'    => $request->userid,
            ]);

            return response()->json([
                'success' => true,
                'data' => $visitor,
                'message' => 'Visitor updated and visit added successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function visitorlist(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'label'   => 'nullable',
            ]);

            $perPage = 10;
            $page = $request->page ?? 1;

            $query = Visitor::with(['state', 'city'])
                ->where('user_id', $request->user_id);

            // ✅ Total visitors OR Today visitors
            if ($request->label !== 'TotalVisitors') {
                $query->whereDate('created_at', now()->toDateString());
            }

            $visitors = $query->paginate($perPage, ['*'], 'page', $page);
            if ($visitors->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visitor not found',
                ], 404);
            }

            $data = [];
            foreach ($visitors as $visit) {
                $data[] = [
                    'visitorid'   => $visit->id,
                    'mobileno'    => $visit->mobileno,
                    'companyname' => $visit->companyname,
                    'name'        => $visit->name,
                    'email'       => $visit->email,
                    'stateid'     => $visit->stateid,
                    'cityid'      => $visit->cityid,
                    'stateName'   => $visit->state->stateName ?? '',
                    'cityName'    => $visit->city->name ?? '',
                ];
            }

            return response()->json([
                'success' => true,
                'count'   => $visitors->total(),
                'data'    => $data,
                'message' => 'Visitor record fetched successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function visitorshow(Request $request)
    {
        try {
            $request->validate([
                'visitorid' => 'nullable',

            ]);
            $visitors = Visitor::with(['state', 'city'])
                ->where('id', $request->visitorid)
                ->get();

            if ($visitors->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visitor not found',
                ], 404);
            }

            $data = [];
            foreach ($visitors as $visit) {
                $data[] = [
                    'visitorid'   => $visit->id,
                    'mobileno'    => $visit->mobileno,
                    'companyname' => $visit->companyname,
                    'name'        => $visit->name,
                    'email'       => $visit->email,
                    'stateid'     => $visit->stateid,
                    'cityid'      => $visit->cityid,
                    'stateName'   => $visit->state->stateName ?? '',
                    'cityName'    => $visit->city->name ?? '',
                ];
            }

            return response()->json([
                'success' => true,
                'count'   => count($data),
                'data'    => $data,
                'message' => 'Visitor record fetched successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function userVisitorCount(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:User,id',
            ]);

            $totalVisitors = Visitor::where('user_id', $request->user_id)->count();

            $todayVisitors = Visitor::where('user_id', $request->user_id)
                ->whereDate('created_at', now()->toDateString())
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $request->user_id,
                    'total_visitors' => $totalVisitors,
                    'today_visitors' => $todayVisitors,
                ],
                'message' => 'User wise visitor count fetched successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function ExpowiseCount(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:User,id',
                'expo_slugname' => 'required',
            ]);
            $expomaster = ExpoMaster::where('slugname', $request->expo_slugname)->first();

            $totalVisitors = Visitorvisit::where('user_id', $request->user_id)
                ->where('expo_id', $expomaster->id)
                ->count();

            $todayVisitors = Visitorvisit::where('user_id', $request->user_id)
                ->where('expo_id', $expomaster->id)
                ->whereDate('created_at', now()->toDateString())
                ->count();



            return response()->json([
                'success' => true,
                'totalVisitors' => $totalVisitors,
                'todayVisitors' => $todayVisitors,
                'message' => 'Expo-wise visitor count fetched successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function visitorupdate(Request $request)
    {
        try {

            $request->validate([
                'visitorid' => 'required|exists:visitor,id',
            ]);

            $visitor = Visitor::findOrFail($request->visitorid);

            // ✅ Check if vendor already updated today
            if (!$visitor->created_at->isToday()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only today visitor record can be updated.'
                ], 403);
            }

            // ✅ Validation
            $request->validate([
                'name'          => 'required|string|max:255',
                'mobile'        => 'required|digits:10|unique:visitor,mobileno,' . $visitor->id,
                'email'         => 'required|email|unique:visitor,email,' . $visitor->id,
                'companyname'       => 'required|string',
                'state_id' => 'required|exists:state,stateId',
                'city_id' => 'required|exists:City,id',

            ]);

            // ✅ Update Vendor
            $visitor->update([
                'name'          => $request->name,
                'mobile'        => $request->mobile,
                'email'         => $request->email,
                'companyname'       => $request->companyname,
                'stateid' => $request->state_id,
                'cityid' => $request->city_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visitor updated successfully.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
