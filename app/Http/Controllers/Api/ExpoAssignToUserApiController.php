<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpoAssignToUser;
use App\Models\User;
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


class ExpoAssignToUserApiController extends Controller

{
    public function ExpoUserAdd(Request $request)
    {
        try {

            $request->validate([
                "user_id" => 'required|exists:User,id',
                "industry_id" => 'required',
                "expo_id" => 'required|exists:expo-master,id',
            ]);
            
            $exists = ExpoAssignToUser::where([
                'user_id' => $request->user_id,
                'industry_id' => $request->industry_id,
                'expo_id' => $request->expo_id,
            ])->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This expo is already assigned to this user for the selected industry.',
                ], 409);
            }
            
            // Create assignment
            $assignment = ExpoAssignToUser::create([
                'user_id' => $request->user_id,
                'industry_id' => $request->industry_id,
                'expo_id' => $request->expo_id,
            ]);

            // Count total users assigned to this expo
            $totalUserCount = ExpoAssignToUser::where('user_id', $request->user_id)->count();

            // Update expo master count
            User::where('id', $request->user_id)
                ->update(['expo_count' => $totalUserCount]);

            return response()->json([
                'success' => true,
                'data' => $assignment,
                'message' => 'Expo assigned to user successfully',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function ExpoUserList(Request $request)
    {
        try {

            $request->validate([
                "user_id" => 'required|exists:User,id',
            ]);

            $Users = ExpoAssignToUser::with('user', 'industry', 'expomaster')
                ->where('user_id', $request->user_id)
                ->get();

            $data = []; // initialize

            foreach ($Users as $User) {
                $data[] = [
                    'expo_assign_user_id'      => $User->id,
                    'industryname' => $User->industry->name ?? '',
                    'username'     => $User->user->name ?? '',
                    'exponame'     => $User->expomaster->name ?? '',
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Expo Assign To User Fetch Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function ExpoUserDelete(Request $request)
    {
        try {
            $request->validate([
                "expo_assign_user_id" => 'required|exists:ExpoAssignToUser,id',
            ]);

            // Fetch record first
            $assign = ExpoAssignToUser::where('id', $request->expo_assign_user_id)->first();

            if (!$assign) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expo assignment not found',
                ], 404);
            }

            $user_id = $assign->user_id;

            // Delete assignment
            $assign->delete();

            // Recalculate expo count
            $totalUserCount = ExpoAssignToUser::where('user_id', $user_id)->count();

            // Update expo master count
            User::where('id', $assign->user_id)
                ->update(['expo_count' => $totalUserCount]);

            return response()->json([
                'success' => true,
                'message' => 'Expo Assign To User Deleted Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
