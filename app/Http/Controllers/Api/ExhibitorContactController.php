<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExhibitorPrimaryContact;
use App\Models\ExhibitorCompanyInformation;
use App\Models\ExhibitorOtherContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ExpoMaster;

class ExhibitorContactController extends Controller
{
    /**
     * Store/Update exhibitor contacts
     * Handles both create and update
     */
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:User,id',
            'expo_slug' => 'required|string|exists:expo-master,slugname', // Changed from expo_id to expo_slug
            'primary_contact_name' => 'required|string',
            'primary_contact_mobile' => 'required',
            'primary_contact_email' => 'nullable|email',
            
            'company_name' => 'required|string',
            'industry_id' => 'required|integer',
            'store_size_sq_meter' => 'nullable|numeric|min:0', // New field
            
            'other_contacts' => 'nullable|array',
            'other_contacts.*.other_contact_name' => 'required_with:other_contacts',
            'other_contacts.*.other_contact_mobile' => 'required_with:other_contacts',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            // Get expo_id from slug
            $expo = DB::table('expo-master')->where('slugname', $request->expo_slug)->first();
            if (!$expo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expo not found'
                ], 404);
            }
            $expoId = $expo->id;
            
            // Check if primary contact mobile already exists for this expo
            $primaryContact = ExhibitorPrimaryContact::where('primary_contact_mobile', $request->primary_contact_mobile)
                ->where('expo_id', $expoId)
                ->where('iStatus', 1)
                ->where('iSDelete', 0)
                ->first();
            
            $isUpdate = false;
            $message = 'Exhibitor contact saved successfully';
            $statusCode = 201;
            $company = null;
            $primary = null;
            
            // If primary contact exists for this expo, check if it was created today
            if ($primaryContact) {
                $isUpdate = true;
                
                // 1) Check if record was created today (same-day edit allowed)
                $createdDate = $primaryContact->created_at;
                $today = now()->startOfDay();
                
                if (!$createdDate || $createdDate->lt($today)) {
                    // Record was created before today
                    return response()->json([
                        'success' => false,
                        'message' => 'Editing is only allowed on the same day. This record cannot be edited.',
                        'created_date' => $createdDate ? $createdDate->format('Y-m-d H:i:s') : null,
                        'current_date' => now()->format('Y-m-d H:i:s')
                    ], 403);
                }
                
                // Get company info for this primary contact
                $company = ExhibitorCompanyInformation::where('exhibitor_primary_contact_id', $primaryContact->id)
                    ->where('expo_id', $expoId)
                    ->where('iStatus', 1)
                    ->where('iSDelete', 0)
                    ->first();
                
                if (!$company) {
                    // Create new company info for existing primary contact
                    $company = ExhibitorCompanyInformation::create([
                        'exhibitor_primary_contact_id' => $primaryContact->id,
                        'expo_id' => $expoId,
                        'company_name' => $request->company_name,
                        'gst' => $request->gst,
                        'state_id' => $request->state_id ?? 0,
                        'city_id' => $request->city_id ?? 0,
                        'address' => $request->address,
                        'industry_id' => $request->industry_id,
                        'category_id' => $request->category_id ?? 0,
                        'subcategory_id' => $request->subcategory_id ?? 0,
                        'store_size_sq_meter' => $request->store_size_sq_meter ?? 0, // New field
                        'enter_by' => $request->user_id ?? 0,
                        'iStatus' => 1,
                        'iSDelete' => 0,
                    ]);
                } else {
                    // Update existing company info
                    $company->update([
                        'company_name' => $request->company_name,
                        'gst' => $request->gst,
                        'state_id' => $request->state_id ?? 0,
                        'city_id' => $request->city_id ?? 0,
                        'address' => $request->address,
                        'industry_id' => $request->industry_id,
                        'category_id' => $request->category_id ?? 0,
                        'subcategory_id' => $request->subcategory_id ?? 0,
                        'store_size_sq_meter' => $request->store_size_sq_meter ?? $company->store_size_sq_meter, // New field
                    ]);
                }
                
                // Update Primary Contact
                $primaryContact->update([
                    'primary_contact_name' => $request->primary_contact_name,
                    'primary_contact_designation' => $request->primary_contact_designation,
                    'primary_contact_email' => $request->primary_contact_email,
                ]);
                
                // Soft delete existing other contacts (only today's contacts)
                ExhibitorOtherContact::where('exhibitor_company_information_id', $company->id)
                    ->whereDate('created_at', '>=', $today)
                    ->update(['iSDelete' => 1]);
                
                $primary = $primaryContact;
                $message = 'Exhibitor updated successfully';
                $statusCode = 200;
                
            } else {
                // CREATE: New exhibitor (mobile doesn't exist for this expo)
                
                /** Primary Contact */
                $primary = ExhibitorPrimaryContact::create([
                    'expo_id' => $expoId,
                    'primary_contact_name' => $request->primary_contact_name,
                    'primary_contact_mobile' => $request->primary_contact_mobile,
                    'primary_contact_designation' => $request->primary_contact_designation,
                    'primary_contact_email' => $request->primary_contact_email,
                    'enter_by' => $request->user_id ?? 0,
                    'iStatus' => 1,
                    'iSDelete' => 0,
                ]);
                
                /** Company Info */
                $company = ExhibitorCompanyInformation::create([
                    'exhibitor_primary_contact_id' => $primary->id,
                    'expo_id' => $expoId,
                    'company_name' => $request->company_name,
                    'gst' => $request->gst,
                    'state_id' => $request->state_id ?? 0,
                    'city_id' => $request->city_id ?? 0,
                    'address' => $request->address,
                    'industry_id' => $request->industry_id,
                    'category_id' => $request->category_id ?? 0,
                    'subcategory_id' => $request->subcategory_id ?? 0,
                    'store_size_sq_meter' => $request->store_size_sq_meter ?? 0, // New field
                    'enter_by' => $request->user_id ?? 0,
                    'iStatus' => 1,
                    'iSDelete' => 0,
                ]);
            }
            
            /** Other Contacts (for both create and update) */
            if ($request->filled('other_contacts')) {
                foreach ($request->other_contacts as $contact) {
                    ExhibitorOtherContact::create([
                        'exhibitor_primary_contact_id' => $primary->id,
                        'exhibitor_company_information_id' => $company->id,
                        'expo_id' => $expoId,
                        'other_contact_name' => $contact['other_contact_name'],
                        'other_contact_mobile' => $contact['other_contact_mobile'],
                        'other_contact_designation' => $contact['other_contact_designation'] ?? null,
                        'other_contact_email' => $contact['other_contact_email'] ?? null,
                        'enter_by' => auth()->id() ?? 0,
                        'iStatus' => 1,
                        'iSDelete' => 0,
                    ]);
                }
            }
            
            DB::commit();
            
            // Load relationships for response
            $company->load(['primaryContact', 'otherContacts']);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $company,
                'is_update' => $isUpdate,
                'id' => $company->id,
                'expo_slug' => $request->expo_slug,
                'store_size_sq_meter' => $company->store_size_sq_meter
            ], $statusCode);
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * List exhibitor contacts
     */
    public function index(Request $request)
    {
        $data = ExhibitorCompanyInformation::with([
                'primaryContact',
                'otherContacts'
            ])
            ->where('iStatus', 1)
            ->where('iSDelete', 0)
            ->when($request->expo_id, fn ($q) => $q->where('expo_id', $request->expo_id))
            ->when($request->industry_id, fn ($q) => $q->where('industry_id', $request->industry_id))
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    
    /**
     * Show single exhibitor
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:ExhibitorCompanyInformations,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $data = ExhibitorCompanyInformation::with([
            'primaryContact',
            'otherContacts'
        ])->where('id', $request->id)
          ->where('iStatus', 1)
          ->where('iSDelete', 0)
          ->first();
          
        $expo = DB::table('expo-master')->where('id', $data->expo_id)->first();
        $expo_name = $expo->name ?? '';
        $expo_slug = $expo->slugname ?? '';
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Exhibitor not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'expo_name' => $expo_name,
            'expo_slug' => $expo_slug
        ]);
    }
    
    public function searchByMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits_between:8,15',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $mobile = $request->mobile;
        
        /**
         * Step 1: Find Primary Contact OR Other Contact
         */
        $primaryContact = ExhibitorPrimaryContact::where('primary_contact_mobile', $mobile)
            ->where('iStatus', 1)
            ->where('iSDelete', 0)
            ->first();
        
        if (!$primaryContact) {
            // Check in other contacts
            $otherContact = ExhibitorOtherContact::where('other_contact_mobile', $mobile)
                ->where('iStatus', 1)
                ->where('iSDelete', 0)
                ->first();
            
            if (!$otherContact) {
                return response()->json([
                    'success' => false,
                    'message' => 'No exhibitor found with this mobile number',
                ], 404);
            }
            
            $primaryContact = ExhibitorPrimaryContact::find(
                $otherContact->exhibitor_primary_contact_id
            );
        }
        
        /**
         * Step 2: Fetch Company Information
         */
        $company = ExhibitorCompanyInformation::with([
                'primaryContact',
                'otherContacts' => function ($q) {
                    $q->where('iStatus', 1)->where('iSDelete', 0);
                }
            ])
            ->where('exhibitor_primary_contact_id', $primaryContact->id)
            ->where('iStatus', 1)
            ->where('iSDelete', 0)
            ->first();
        
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company information not found',
            ], 404);
        }
        
        /**
         * Step 3: Response Formatting
         */
        return response()->json([
            'success' => true,
            
            // FIRST ARRAY
            'primary_and_company' => [
                'primary_contact' => [
                    'id' => $primaryContact->id,
                    'name' => $primaryContact->primary_contact_name,
                    'mobile' => $primaryContact->primary_contact_mobile,
                    'email' => $primaryContact->primary_contact_email,
                    'designation' => $primaryContact->primary_contact_designation,
                ],
                'company' => [
                    'id' => $company->id,
                    'company_name' => $company->company_name,
                    'gst' => $company->gst,
                    'address' => $company->address,
                    'state_id' => $company->state_id,
                    'city_id' => $company->city_id,
                    'industry_id' => $company->industry_id,
                    'category_id' => $company->category_id,
                    'subcategory_id' => $company->subcategory_id,
                    'store_size_sq_meter' => $request->store_size_sq_meter ?? 0, // New field
                    'expo_id' => $company->expo_id,
                ],
            ],
            
            // SECOND ARRAY
            'other_contacts' => $company->otherContacts->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->other_contact_name,
                    'mobile' => $contact->other_contact_mobile,
                    'email' => $contact->other_contact_email,
                    'designation' => $contact->other_contact_designation,
                ];
            }),
            
            // COUNT
            'total_other_contacts_count' => $company->otherContacts->count(),
        ]);
    }
    
    public function ExpowiseCount(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:User,id',
                'expo_slugname' => 'required',
            ]);
            $expomaster = ExpoMaster::where('slugname', $request->expo_slugname)->first();

            $totalExhibitors = ExhibitorCompanyInformation::where('enter_by',$request->user_id)
                ->where('expo_id', $expomaster->id)
                ->count();
            $todayExhibitors = ExhibitorCompanyInformation::where('enter_by',$request->user_id)
                ->where('expo_id', $expomaster->id) 
                ->whereDate('created_at', now()->toDateString())
                ->count();
            

            return response()->json([
                'success' => true,
                'totalExhibitors' => $totalExhibitors,
                'todayExhibitors' => $todayExhibitors,
                'message' => 'Expo-wise exhibitor count fetched successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}