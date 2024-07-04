<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PositionLevelResource;

class PositionLevelController extends Controller
{
    /**
     * Get all position levels.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPositionLevels()
    {
        try {
            // Execute the stored procedure
            $positionLevels = DB::select('CALL get_position_levels()');

            // Return collection of position levels as a resource
            return response()->json(PositionLevelResource::collection($positionLevels));
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to fetch position levels.', 'error' => $e->getMessage()], 500);
        }
    }

    public function addPositionLevel(Request $request)
    {

        try {
            $position_lvl = $request->input('position_lvl');
            $created_by = $request->input('created_by');

            // Execute the stored procedure
            DB::statement('CALL insert_position_level(?, ?)', [$position_lvl, $created_by]);

            return response()->json(['message' => 'Position level added successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to add position level.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updatePositionLevel(Request $request)
    {
        try {
            $id = $request->input('id');
            $position_lvl = $request->input('position_lvl');
            $active = $request->input('active');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL update_position_level(?, ?, ?, ?)', [$id, $position_lvl, $active, $changed_by]);

            return response()->json(['message' => 'Position level updated successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to update position level.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getPositionLevelDetail($id)
    {
        try {
            // Execute the stored procedure
            $positionLevel = DB::select('CALL get_position_level_detail(?)', [$id]);

            if (empty($positionLevel)) {
                return response()->json(['message' => 'Position level not found'], 404);
            }

            return response()->json(['position_level' => $positionLevel]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to fetch position level detail.', 'error' => $e->getMessage()], 500);
        }
    }

    public function softDeletePositionLevel(Request $request)
    {
        try {
            $id = $request->input('id');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL soft_delete_position_level(?, ?)', [$id, $changed_by]);

            return response()->json(['message' => 'Position level soft deleted successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to soft delete position level.', 'error' => $e->getMessage()], 500);
        }
    }
}
