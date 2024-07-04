<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PositionStatusResource;

class PositionStatusController extends Controller
{
    public function getPositionStatuses()
    {
        try {
            // Execute the stored procedure
            $positionStatuses = DB::select('CALL get_position_statuses()');

            // Return collection of resources
            return response()->json(PositionStatusResource::collection($positionStatuses));
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to fetch position statuses.', 'error' => $e->getMessage()], 500);
        }
    }

    public function addPositionStatus(Request $request)
    {
        try {
            $position_sts = $request->input('position_sts');
            $created_by = $request->input('created_by');

            // Execute the stored procedure
            DB::statement('CALL add_position_status(?, ?)', [$position_sts, $created_by]);

            return response()->json(['message' => 'Position status added successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to add position status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updatePositionStatus(Request $request)
    {

        try {
            $id = $request->input('id');
            $position_sts = $request->input('position_sts');
            $active = $request->input('active');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL update_position_status(?, ?, ?, ?)', [$id, $position_sts, $active, $changed_by]);

            return response()->json(['message' => 'Position status updated successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to update position status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getPositionStatus($id)
    {
        try {
            // Execute the stored procedure
            $positionStatus = DB::select('CALL get_position_status(?)', [$id]);

            if (empty($positionStatus)) {
                return response()->json(['message' => 'Position status not found'], 404);
            }

            return response()->json(['position_status' => $positionStatus[0]]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to fetch position status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function softDeletePositionStatus(Request $request)
    {
        try {
            $id = $request->input('id');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL soft_delete_position_status(?, ?)', [$id, $changed_by]);

            return response()->json(['message' => 'Position status soft deleted successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to soft delete position status.', 'error' => $e->getMessage()], 500);
        }
    }
}
