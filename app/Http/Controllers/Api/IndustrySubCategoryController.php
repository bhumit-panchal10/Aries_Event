<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndustrySubCategory;
use App\Models\Industry;
use App\Models\IndustryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndustrySubCategoryController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = IndustrySubCategory::with([
                'industry' => function($q) {
                    $q->select('id', 'name');
                },
                'industryCategory' => function($q) {
                    $q->select('id', 'industry_category_name');
                }
            ])->where('isDelete', 0);
            
            // Filter by industry_id if provided
            if ($request->has('industry_id') && $request->industry_id != "") {
                $query->where('industry_id', $request->industry_id);
            }
            
            // Filter by industry_category_id if provided
            if ($request->has('industry_category_id') && $request->industry_category_id != "") {
                $query->where('industry_category_id', $request->industry_category_id);
            }
            
            // Filter by status if provided
            // if ($request->has('iStatus')) {
            //     $query->where('iStatus', $request->iStatus);
            // }
            
            // Search by name if provided
            if ($request->has('search') && $request->has('search') != "") {
                $search = $request->search;
                $query->where('industry_subcategory_name', 'like', "%{$search}%");
            }
            
            $data = $query->latest()->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'industry_id' => $item->industry_id,
                    'industry_category_id' => $item->industry_category_id,
                    'industry_subcategory_name' => $item->industry_subcategory_name,
                    'iStatus' => $item->iStatus,
                    'entry_by' => $item->entry_by,
                    'isDelete' => $item->isDelete,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'industry_name' => optional($item->industry)->name,
                    'industry_category_name' => optional($item->industryCategory)->industry_category_name,
                    // 'entered_by_name' => optional($item->enteredBy)->name ?? 'N/A'
                ];
            });
            
            return response()->json([
                'status' => true,
                'message' => 'Industry subcategories retrieved successfully',
                'data' => $data
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve industry subcategories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'industry_id' => 'required|integer|exists:Industry,id',
                'industry_category_id' => 'required|integer|exists:industry_categories,id',
                'industry_subcategory_name' => 'required|string|max:255|unique:IndustrySubCategory,industry_subcategory_name,NULL,id,isDelete,0',
            ], [
                'industry_id.required' => 'Industry is required',
                'industry_id.exists' => 'Selected industry does not exist',
                'industry_category_id.required' => 'Industry category is required',
                'industry_category_id.exists' => 'Selected industry category does not exist',
                'industry_subcategory_name.required' => 'Subcategory name is required',
                'industry_subcategory_name.unique' => 'Subcategory name already exists'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Check if industry category belongs to the selected industry
            $category = IndustryCategory::find($request->industry_category_id);
            if ($category && $category->industry_id != $request->industry_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected category does not belong to the selected industry'
                ], 422);
            }
            
            // Get authenticated user ID
            $userId = Auth::id();
            
            // Create subcategory
            $subCategory = IndustrySubCategory::create([
                'industry_id' => $request->industry_id,
                'industry_category_id' => $request->industry_category_id,
                'industry_subcategory_name' => $request->industry_subcategory_name,
                'iStatus' => $request->iStatus ?? 1,
                'entry_by' => $userId ?? 0,
                'isDelete' => 0
            ]);
            
            // Load relationships
            $subCategory->load(['industry', 'industryCategory']);
            
            return response()->json([
                'status' => true,
                'message' => 'Industry subcategory created successfully',
                'data' => [
                    'id' => $subCategory->id,
                    'industry_id' => $subCategory->industry_id,
                    'industry_category_id' => $subCategory->industry_category_id,
                    'industry_subcategory_name' => $subCategory->industry_subcategory_name,
                    'iStatus' => $subCategory->iStatus,
                    'industry_name' => optional($subCategory->industry)->name,
                    'industry_category_name' => optional($subCategory->industryCategory)->industry_category_name
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create industry subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $subCategory = IndustrySubCategory::with([
                'industry' => function($q) {
                    $q->select('id', 'name');
                },
                'industryCategory' => function($q) {
                    $q->select('id', 'industry_category_name');
                }
            ])->where('id', $request->id)
              ->where('isDelete', 0)
              ->first();
            
            if (!$subCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Industry subcategory not found'
                ], 404);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Industry subcategory retrieved successfully',
                'data' => [
                    'id' => $subCategory->id,
                    'industry_id' => $subCategory->industry_id,
                    'industry_category_id' => $subCategory->industry_category_id,
                    'industry_subcategory_name' => $subCategory->industry_subcategory_name,
                    'iStatus' => $subCategory->iStatus,
                    'entry_by' => $subCategory->entry_by,
                    'created_at' => $subCategory->created_at,
                    'updated_at' => $subCategory->updated_at,
                    'industry_name' => optional($subCategory->industry)->name,
                    'industry_category_name' => optional($subCategory->industryCategory)->industry_category_name
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve industry subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $subCategory = IndustrySubCategory::where('id', $request->id)
                ->where('isDelete', 0)
                ->first();
            
            if (!$subCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Industry subcategory not found'
                ], 404);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'industry_id' => 'sometimes|required|integer|exists:Industry,id',
                'industry_category_id' => 'sometimes|required|integer|exists:industry_categories,id',
                'industry_subcategory_name' => 'sometimes|required|string|max:255|unique:IndustrySubCategory,industry_subcategory_name,' . $request->id . ',id,isDelete,0',
                //'iStatus' => 'nullable|integer|in:0,1'
            ], [
                'industry_id.exists' => 'Selected industry does not exist',
                'industry_category_id.exists' => 'Selected industry category does not exist',
                'industry_subcategory_name.unique' => 'Subcategory name already exists'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // If industry_id or category_id is being updated, validate relationship
            if ($request->has('industry_category_id') || $request->has('industry_id')) {
                $industryId = $request->industry_id ?? $subCategory->industry_id;
                $categoryId = $request->industry_category_id ?? $subCategory->industry_category_id;
                
                $category = IndustryCategory::find($categoryId);
                if ($category && $category->industry_id != $industryId) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Selected category does not belong to the selected industry'
                    ], 422);
                }
            }
            
            // Update subcategory
            $subCategory->update($request->only([
                'industry_id',
                'industry_category_id',
                'industry_subcategory_name',
                'iStatus'
            ]));
            
            // Refresh with relationships
            $subCategory->refresh();
            $subCategory->load(['industry', 'industryCategory']);
            
            return response()->json([
                'status' => true,
                'message' => 'Industry subcategory updated successfully',
                'data' => [
                    'id' => $subCategory->id,
                    'industry_id' => $subCategory->industry_id,
                    'industry_category_id' => $subCategory->industry_category_id,
                    'industry_subcategory_name' => $subCategory->industry_subcategory_name,
                    'iStatus' => $subCategory->iStatus,
                    'updated_at' => $subCategory->updated_at,
                    'industry_name' => optional($subCategory->industry)->name,
                    'industry_category_name' => optional($subCategory->industryCategory)->industry_category_name
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update industry subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Request $request)
    {
        try {
            $subCategory = IndustrySubCategory::where('id', $request->id)
                ->where('isDelete', 0)
                ->first();
            
            if (!$subCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Industry subcategory not found'
                ], 404);
            }
            
            // Soft delete
            $subCategory->update([
                'isDelete' => 1
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Industry subcategory deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete industry subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get categories by industry
     */
    public function getCategoriesByIndustry(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'industry_id' => 'required|integer|exists:Industry,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Get categories for the given industry ID
            $categories = IndustryCategory::with([
                'industry' => function($q) {
                    $q->select('id', 'name');
                }
            ])->where('industry_id', $request->industry_id)
              ->where('isDelete', 0)
              ->where('iStatus', 1)
              ->orderBy('industry_category_name')
              ->get()
              ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'industry_category_name' => $item->industry_category_name,
                    'industry_id' => $item->industry_id,
                    'industry_name' => optional($item->industry)->name,
                    'iStatus' => $item->iStatus,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at
                ];
            });
            
            return response()->json([
                'status' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get subcategories by industry category
     */
    public function getByCategory(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'industry_category_id' => 'required|integer|exists:industry_categories,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Get subcategories by category ID
            $subCategories = IndustrySubCategory::with([
                    'industry' => function($q) {
                        $q->select('id', 'name');
                    }
                ])->where('industry_category_id', $request->industry_category_id)
                  ->where('isDelete', 0)
                  ->where('iStatus', 1)
                  ->orderBy('industry_subcategory_name')
                  ->get()
                  ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'industry_subcategory_name' => $item->industry_subcategory_name,
                        'industry_id' => $item->industry_id,
                        'industry_category_id' => $item->industry_category_id,
                        'industry_name' => optional($item->industry)->name
                    ];
                });
            
            return response()->json([
                'status' => true,
                'message' => 'Subcategories retrieved successfully',
                'data' => $subCategories
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve subcategories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk status update
     */
    public function bulkStatusUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:IndustrySubCategory,id',
                'iStatus' => 'required|integer|in:0,1'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $updatedCount = IndustrySubCategory::whereIn('id', $request->ids)
                ->where('isDelete', 0)
                ->update(['iStatus' => $request->iStatus]);
            
            return response()->json([
                'status' => true,
                'message' => "Status updated for {$updatedCount} subcategory(s)",
                'data' => [
                    'updated_count' => $updatedCount
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}