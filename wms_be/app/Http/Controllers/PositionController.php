<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PositionResource;

class PositionController extends Controller
{
    /**
     * Get all positions using stored procedure.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPositions()
    {
        try {
            // Execute the stored procedure
            $positions = DB::select('CALL get_positions()');

            // Use PositionResource to format the response
            return response()->json(PositionResource::collection($positions));
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to get positions.', 'error' => $e->getMessage()], 500);
        }
    }

    public function addPosition(Request $request)
    {

        try {
            $position = $request->input('position');
            $created_by = $request->input('created_by');

            // Execute the stored procedure
            DB::statement('CALL insert_position(?, ?)', [$position, $created_by]);

            return response()->json(['message' => 'Position added successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to add position.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updatePosition(Request $request)
    {
        try {
            $id = $request->input('id');
            $position = $request->input('position');
            $active = $request->input('active');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL update_position(?, ?, ?, ?)', [$id, $position, $active, $changed_by]);

            return response()->json(['message' => 'Position updated successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to update position.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getPositionDetail($id)
    {
        try {
            // Execute the stored procedure
            $position = DB::select('CALL get_position_detail(?)', [$id]);

            if (empty($position)) {
                return response()->json(['message' => 'Position not found'], 404);
            }

            return response()->json(['position' => $position[0]]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to fetch position detail.', 'error' => $e->getMessage()], 500);
        }
    }

    public function softDeletePosition(Request $request)
    {

        try {
            $id = $request->input('id');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL soft_delete_position(?, ?)', [$id, $changed_by]);

            return response()->json(['message' => 'Position soft deleted successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to soft delete position.', 'error' => $e->getMessage()], 500);
        }
    }
}
