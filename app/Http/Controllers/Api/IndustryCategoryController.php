<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndustryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;

class IndustryCategoryController extends Controller
{
    public function index()
    {
        $data = IndustryCategory::with('industry')
            ->where('isDelete', 0)
            ->where('iStatus', 1)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'industry_id' => $item->industry_id,
                    'industry_category_name' => $item->industry_category_name,
                    'isDelete' => $item->isDelete,
                    'iStatus' => $item->iStatus,
                    'entry_by' => $item->entry_by,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'industry_name' => optional($item->industry)->name
                ];
            });
        
        return response()->json([
            'status' => true,
            'data'   => $data
        ], 200);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'industry_id'             => 'required|integer|exists:Industry,id',
            'industry_category_name'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('industry_categories')->where(function ($query) use ($request) {
                    return $query->where('industry_id', $request->industry_id)
                                 ->where('isDelete', 0);
                })
            ],
        ], [
            'industry_id.exists' => 'Selected industry does not exist',
            'industry_category_name.unique' => 'Category name already exists for this industry'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Get authenticated user ID
        $userId = Auth::id();
        
        $data = IndustryCategory::create([
            'industry_id'            => $request->industry_id,
            'industry_category_name' => $request->industry_category_name,
            'entry_by'               => $userId ?? 0, // Use authenticated user's ID
        ]);
        
        return response()->json([
            'status'  => true,
            'message' => 'Industry category created',
            'data'    => $data
        ], 201);
    }

    /**
     * Show single - FIXED: Use route parameter instead of Request parameter
     */
    public function show(Request $request) // Changed from show(Request $request)
    {   
        
        $data = IndustryCategory::where('id', $request->id) // Changed from $request->id
                ->where('isDelete', 0)
                ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $data
        ], 200);
    }

    /**
     * Update
     */
    public function update(Request $request)
    {
        $data = IndustryCategory::find($request->id);

        if (!$data || $data->isDelete == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:industry_categories,id',
            'industry_id' => 'required|integer|exists:Industry,id',
            'industry_category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('industry_categories')->where(function ($query) use ($request) {
                    return $query->where('industry_id', $request->industry_id)
                                 ->where('isDelete', 0)
                                 ->where('id', '!=', $request->id); // Exclude current record
                })
            ],
        ], [
            'id.exists' => 'Category not found',
            'industry_id.exists' => 'Selected industry does not exist',
            'industry_category_name.unique' => 'Category name already exists for this industry'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data->update([
            'industry_id'            => $request->industry_id,
            'industry_category_name' => $request->industry_category_name,
            //'iStatus'                => $request->iStatus ?? $data->iStatus
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Updated successfully',
            'data'    => $data
        ], 200);
    }

    /**
     * Soft Delete (isDelete = 1)
     */
    public function destroy(Request $request)
    {
        $data = IndustryCategory::find($request->id);

        if (!$data || $data->isDelete == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $data->update([
            'isDelete' => 1
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Deleted successfully'
        ], 200);
    }

}