<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SeniorityResource;

class SeniorityController extends Controller
{
    /**
     * Get all seniority settings using stored procedure.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllSettingSeniority()
    {
        try {
            // Execute the stored procedure
            $senioritySettings = DB::select('CALL get_all_setting_seniority()');

            // Transform the result using SeniorityResource
            $resource = SeniorityResource::collection($senioritySettings);

            return response()->json($resource);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to retrieve seniority settings.', 'error' => $e->getMessage()], 500);
        }
    }

    public function addSeniority(Request $request)
    {
        try {
            $seniority = $request->input('seniority');
            $created_by = $request->input('created_by');

            // Execute the stored procedure
            DB::statement('CALL insert_seniority(?, ?)', [$seniority, $created_by]);

            return response()->json(['message' => 'Seniority added successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to add seniority.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateSeniority(Request $request)
    {

        try {
            $id = $request->input('id');
            $seniority = $request->input('seniority');
            $active = $request->input('active');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL update_seniority(?, ?, ?, ?)', [$id, $seniority, $active, $changed_by]);

            return response()->json(['message' => 'Seniority updated successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to update seniority.', 'error' => $e->getMessage()], 500);
        }
    }

    public function softDeleteSeniority(Request $request)
    {
        try {
            $id = $request->input('id');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL soft_delete_seniority(?, ?)', [$id, $changed_by]);

            return response()->json(['message' => 'Seniority soft deleted successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to soft delete seniority.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getSeniority($id)
    {
        try {
            // Execute the stored procedure
            $seniority = DB::select('CALL get_seniority(?)', [$id]);

            if (empty($seniority)) {
                return response()->json(['message' => 'Seniority not found'], 404);
            }

            return response()->json(['seniority' => $seniority[0]]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to get seniority.', 'error' => $e->getMessage()], 500);
        }
    }
}
