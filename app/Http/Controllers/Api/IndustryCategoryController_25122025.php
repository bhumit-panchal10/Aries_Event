<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndustryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndustryCategoryController extends Controller
{
    public function index()
    {
        $data = IndustryCategory::where('isDelete', 0)->latest()->get();

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
            'industry_id'             => 'required|integer',
            'industry_category_name'  => 'required|string|max:255',
            'entry_by'                => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = IndustryCategory::create([
            'industry_id'            => $request->industry_id,
            'industry_category_name' => $request->industry_category_name,
            'entry_by'               => $request->entry_by,
            'isDelete'               => 0,
            'iStatus'                => 1
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Industry category created',
            'data'    => $data
        ], 201);
    }

    /**
     * Show single
     */
    public function show($id)
    {
        $data = IndustryCategory::where('id', $id)
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
    public function update(Request $request, $id)
    {
        $data = IndustryCategory::find($id);

        if (!$data || $data->isDelete == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'industry_id'            => 'required|integer',
            'industry_category_name' => 'required|string|max:255',
            'iStatus'                => 'nullable|integer'
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
            'iStatus'                => $request->iStatus ?? $data->iStatus
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
    public function destroy($id)
    {
        $data = IndustryCategory::find($id);

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
