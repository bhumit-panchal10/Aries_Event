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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitorsExport;
use App\Models\StateMaster;


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

    // public function VisitordataUpload(Request $request)
    // {
    //     $request->validate([
    //         'type'     => 'required|in:industry,pre_register,visited',
    //         'expo_id'  => 'nullable',
    //         'industry_id'  => 'nullable',
    //         'user_id'  => 'required',
    //         'user_name'  => 'required',
    //         'file'     => 'required|mimes:xls,xlsx',
    //     ]);

    //     $rows = Excel::toArray([], $request->file('file'))[0];

    //     DB::beginTransaction();
    //     try {

    //         foreach ($rows as $key => $row) {
    //             if ($key == 0) continue; // skip header

    //             $name    = $row[0] ?? null;
    //             $mobile  = $row[1] ?? null;
    //             $email   = $row[2] ?? null;
    //             $company = $row[3] ?? null;
    //             $state   = $row[4] ?? null;
    //             $city    = $row[5] ?? null;

    //             if (!$mobile) continue;

    //             /** -------------------------------
    //              * FIND OR CREATE VISITOR
    //              * --------------------------------*/
    //             $visitor = Visitor::where('mobileno', $mobile)->first();

    //             if (!$visitor) {
    //                 $visitor = Visitor::create([
    //                     'name'        => $name,
    //                     'mobileno'    => $mobile,
    //                     'email'       => $email,
    //                     'companyname' => $company,
    //                     'stateid'     => $state,
    //                     'cityid'      => $city,
    //                     'expo_id'     => $request->expo_id,
    //                     'industry_id'     => $request->industry_id,
    //                     'enter_by'    => $request->user_name ?? '',
    //                 ]);
    //             }

    //             /** -------------------------------
    //              * PHASE 1 – INDUSTRY
    //              * --------------------------------*/
    //             if ($request->type === 'industry') {
    //                 continue; // only visitor insert
    //             }

    //             /** -------------------------------
    //              * CHECK VISITOR VISIT
    //              * --------------------------------*/
    //             $visit = Visitorvisit::where([
    //                 'visitor_id' => $visitor->id,
    //                 'expo_id'    => $request->expo_id
    //             ])->first();

    //             /** -------------------------------
    //              * PHASE 2 – PRE REGISTER
    //              * --------------------------------*/
    //             if ($request->type === 'pre_register') {

    //                 if ($visit) {
    //                     $visit->update([
    //                         'Is_Pre' => 1
    //                     ]);
    //                 } else {
    //                     Visitorvisit::create([
    //                         'visitor_id' => $visitor->id,
    //                         'expo_id'    => $request->expo_id,
    //                         'Is_Pre'     => 1,
    //                         'Is_Visit'  => 0,
    //                         'user_id'   => $request->user_id ?? 0,
    //                     ]);
    //                 }
    //             }

    //             /** -------------------------------
    //              * PHASE 3 – VISITED VISITOR
    //              * --------------------------------*/
    //             if ($request->type === 'visited') {

    //                 if ($visit) {
    //                     $visit->update([
    //                         'Is_Visit' => 1
    //                     ]);
    //                 } else {
    //                     Visitorvisit::create([
    //                         'visitor_id' => $visitor->id,
    //                         'expo_id'    => $request->expo_id,
    //                         'Is_Pre'     => 0,
    //                         'Is_Visit'  => 1,
    //                         'user_id'   => $request->user_id ?? 0,
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Visitor Excel uploaded successfully'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function VisitordataUpload(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:industry,pre_register,visited',
            'expo_id'    => 'nullable',
            'industry_id'=> 'nullable',
            'user_id'    => 'required',
            'user_name'  => 'required',
            'file'       => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $rows = Excel::toArray([], $file)[0];
        
        // 1. Header check
        $expectedHeaders = ['Name', 'Mobile', 'Email', 'Company', 'State', 'City'];
        $actualHeaders = $rows[0] ?? [];
        
        // Check if all required headers are present
        foreach ($expectedHeaders as $expectedHeader) {
            if (!in_array($expectedHeader, $actualHeaders)) {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid Excel format. Missing header: '$expectedHeader'. Required headers: " . implode(', ', $expectedHeaders)
                ], 400);
            }
        }
        
        // Validate header order (optional)
        for ($i = 0; $i < count($expectedHeaders); $i++) {
            if (($actualHeaders[$i] ?? null) !== $expectedHeaders[$i]) {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid header order. Expected order: " . implode(', ', $expectedHeaders)
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            $importedCount = 0;
            $skippedCount = 0;
            $errors = [];
            
            // Cache states and cities to reduce database queries
            $states = StateMaster::pluck('stateId', 'stateName')->mapWithKeys(function ($id, $name) {
                return [strtolower(trim($name)) => $id];
            });
            
            $cities = CityMaster::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
                return [strtolower(trim($name)) => $id];
            });

            foreach ($rows as $key => $row) {
                if ($key == 0) continue; // skip header
                
                $name    = trim($row[0] ?? '');
                $mobile  = trim($row[1] ?? '');
                $email   = trim($row[2] ?? '');
                $company = trim($row[3] ?? '');
                $stateName = trim($row[4] ?? '');
                $cityName  = trim($row[5] ?? '');
                
                // 2. Put validation for each row
                $rowErrors = [];
                
                // Validate mobile
                if (!$mobile) {
                    $rowErrors[] = "Row " . ($key + 1) . ": Mobile number is required";
                } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
                    $rowErrors[] = "Row " . ($key + 1) . ": Invalid mobile number format (should be 10 digits)";
                }
                
                // Validate name
                if (!$name) {
                    $rowErrors[] = "Row " . ($key + 1) . ": Name is required";
                }
                
                // Validate email
                if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $rowErrors[] = "Row " . ($key + 1) . ": Invalid email format";
                }
                
                // 3. Set state and city ID instead of name and check name validation
                $stateId = null;
                $cityId = null;
                
                if ($stateName) {
                    $stateKey = strtolower($stateName);
                    $stateId = $states[$stateKey] ?? null;
                    if (!$stateId) {
                        $rowErrors[] = "Row " . ($key + 1) . ": Invalid state name '$stateName'";
                    }
                }
                
                if ($cityName) {
                    $cityKey = strtolower($cityName);
                    $cityId = $cities[$cityKey] ?? null;
                    if (!$cityId) {
                        $rowErrors[] = "Row " . ($key + 1) . ": Invalid city name '$cityName'";
                        
                        // Optional: Try to find city by partial match or suggest alternatives
                        $similarCities = CityMaster::where('name', 'LIKE', '%' . $cityName . '%')->pluck('name')->toArray();
                        if (!empty($similarCities)) {
                            $rowErrors[count($rowErrors) - 1] .= ". Did you mean: " . implode(', ', array_slice($similarCities, 0, 3));
                        }
                    }
                }
                
                // If there are errors for this row, skip it
                if (!empty($rowErrors)) {
                    $errors = array_merge($errors, $rowErrors);
                    $skippedCount++;
                    continue;
                }
                
                // Check if mobile already exists (but not for industry type)
                if ($request->type !== 'industry') {
                    $existingVisitor = Visitor::where('mobileno', $mobile)
                        ->where('expo_id', $request->expo_id)
                        ->first();
                        
                    if ($existingVisitor) {
                        $visitor = $existingVisitor;
                    } else {
                        // For non-industry imports, visitor must exist in the system
                        $errors[] = "Row " . ($key + 1) . ": Visitor with mobile $mobile not found in system. Please import industry data first.";
                        $skippedCount++;
                        continue;
                    }
                } else {
                    // For industry type, find or create visitor
                    $visitor = Visitor::where('mobileno', $mobile)->first();
                }
                
                if (!$visitor) {
                    $visitorData = [
                        'name'        => $name,
                        'mobileno'    => $mobile,
                        'email'       => $email ?: null,
                        'companyname' => $company ?: null,
                        'stateid'     => $stateId,
                        'cityid'      => $cityId,
                        'enter_by'    => $request->user_name ?? '',
                    ];
                    
                    // Only set these if provided
                    if ($request->expo_id) {
                        $visitorData['expo_id'] = $request->expo_id;
                    }
                    if ($request->industry_id) {
                        $visitorData['industry_id'] = $request->industry_id;
                    }
                    
                    $visitor = Visitor::create($visitorData);
                } else {
                    // Update existing visitor with new data if needed
                    $updateData = [];
                    if ($name && !$visitor->name) $updateData['name'] = $name;
                    if ($email && !$visitor->email) $updateData['email'] = $email;
                    if ($company && !$visitor->companyname) $updateData['companyname'] = $company;
                    if ($stateId && !$visitor->stateid) $updateData['stateid'] = $stateId;
                    if ($cityId && !$visitor->cityid) $updateData['cityid'] = $cityId;
                    
                    if (!empty($updateData)) {
                        $visitor->update($updateData);
                    }
                }
                
                /** -------------------------------
                 * PHASE 1 – INDUSTRY
                 * --------------------------------*/
                if ($request->type === 'industry') {
                    $importedCount++;
                    continue; // only visitor insert
                }
                
                /** -------------------------------
                 * CHECK VISITOR VISIT
                 * --------------------------------*/
                $visit = Visitorvisit::where([
                    'visitor_id' => $visitor->id,
                    'expo_id'    => $request->expo_id
                ])->first();
                
                /** -------------------------------
                 * PHASE 2 – PRE REGISTER
                 * --------------------------------*/
                if ($request->type === 'pre_register') {
                    if ($visit) {
                        $visit->update([
                            'Is_Pre' => 1
                        ]);
                    } else {
                        Visitorvisit::create([
                            'visitor_id' => $visitor->id,
                            'expo_id'    => $request->expo_id,
                            'Is_Pre'     => 1,
                            'Is_Visit'   => 0,
                            'user_id'    => $request->user_id ?? 0,
                        ]);
                    }
                    $importedCount++;
                }
                
                /** -------------------------------
                 * PHASE 3 – VISITED VISITOR
                 * --------------------------------*/
                if ($request->type === 'visited') {
                    if ($visit) {
                        $visit->update([
                            'Is_Visit' => 1
                        ]);
                    } else {
                        Visitorvisit::create([
                            'visitor_id' => $visitor->id,
                            'expo_id'    => $request->expo_id,
                            'Is_Pre'     => 0,
                            'Is_Visit'   => 1,
                            'user_id'    => $request->user_id ?? 0,
                        ]);
                    }
                    $importedCount++;
                }
            }

            DB::commit();
            
            $response = [
                'success' => true,
                'message' => 'Visitor Excel uploaded successfully',
                'stats' => [
                    'total_rows' => count($rows) - 1, // excluding header
                    'imported' => $importedCount,
                    'skipped' => $skippedCount,
                    'has_errors' => !empty($errors)
                ]
            ];
            
            if (!empty($errors)) {
                $response['errors'] = array_slice($errors, 0, 20); // Show first 20 errors
                if (count($errors) > 20) {
                    $response['message'] .= ' (' . count($errors) . ' errors found, showing first 20)';
                }
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function adminVisitorList(Request $request)
    {
        try {
            $request->validate([
                'expo_id'  => 'required',
                'industry_id'  => 'required',
                'Is_Pre'   => 'required',
                'Is_Visit'   => 'required'
            ]);
            $perPage = 10;
            $page = $request->page ?? 1;

            $query = Visitorvisit::with(['visitor.state', 'visitor.city'])
            ->where('expo_id', $request->expo_id)
            ->whereHas('visitor', function ($q) use ($request) {
                $q->where('industry_id', $request->industry_id);
            });

            if ($request->Is_Pre != 2) {
                $query->where('Is_Pre', $request->Is_Pre);
            }

            if ($request->Is_Visit != 2) {
                $query->where('Is_Visit', $request->Is_Visit);
            }

            $visitors = $query->paginate($perPage, ['*'], 'page', $page);
            if ($visitors->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visitor not found',
                ], 404);
            } else {
                $data = [];
                foreach ($visitors as $visit) {
                    $data[] = [
                        'visitorid'   => $visit->visitor->id,
                        'mobileno'    => $visit->visitor->mobileno,
                        'companyname' => $visit->visitor->companyname,
                        'name'        => $visit->visitor->name,
                        'email'       => $visit->visitor->email,
                        'stateid'     => $visit->visitor->stateid,
                        'cityid'      => $visit->visitor->cityid,
                        'state_name'  => $visit->visitor->state ? $visit->visitor->state->stateName : null, // Assuming 'stateName' column
                        'city_name'   => $visit->visitor->city ? $visit->visitor->city->name : null,    // Assuming 'cityName' column
                    ];
                }
                return response()->json([
                    'success' => true,
                    'count'   => $visitors->total(),
                    'data'    => $data,
                    'meta'    => [
                        'total'        => $visitors->total(),
                        'per_page'     => $visitors->perPage(),
                        'current_page' => $visitors->currentPage(),
                        'last_page'    => $visitors->lastPage(),
                        'from'         => $visitors->firstItem(),
                        'to'           => $visitors->lastItem(),
                    ],
                    'message' => 'Visitor record fetched successfully',
                ], 200);
            }     
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function exportVisitors(Request $request)
    {
        try {
            $request->validate([
                'expo_id'     => 'required',
                'industry_id' => 'required',
                'Is_Pre'      => 'required',
                'Is_Visit'    => 'required'
            ]);

            // Generate filename with timestamp
            $filename = 'visitors_' . date('Y_m_d_His') . '.xlsx';
            
            // Return Excel download
            return Excel::download(
                new VisitorsExport(
                    $request->expo_id,
                    $request->industry_id,
                    $request->Is_Pre,
                    $request->Is_Visit
                ),
                $filename
            );

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}
