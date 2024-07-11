<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\FloorResource;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Redis;

class FloorController extends Controller
{
    /**
     * Get list floor using stored procedure.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFloor()
    {
        try {
            // Execute the stored procedure
            $floor = DB::select('CALL get_floor()');

            DB::commit();

            return FloorResource::collection($floor);
            //return new FloorResource($floor);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An unexpected error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getFloorDetails($id)
    {
        try {
            // Execute the stored procedure
            $floor = DB::select('CALL get_floor_details(?)', [$id]);

            if (empty($floor)) {
                return response()->json(['message' => 'Floor not found'], 404);
            }

            $floor = $floor[0]; // Since it's an array of stdClass objects, we take the first one

            //return new FloorResource($floor);
            return response()->json($floor);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An unexpected error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    public function insertFloor(Request $request)
    {

        try {
            $floor = $request->input('floor');
            $created_by = $request->input('created_by');

            // Execute the stored procedure
            DB::statement('CALL insert_floor(?, ?)', [$floor, $created_by]);

            return response()->json(['message' => 'Floor inserted successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to insert floor.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateFloor(Request $request)
    {
        try {
            $id = $request->input('id');
            $floor = $request->input('floor');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL update_floor(?, ?, ?)', [$id, $floor, $changed_by]);

            return response()->json(['message' => 'Floor updated successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to update floor.', 'error' => $e->getMessage()], 500);
        }
    }

    public function softDeleteFloor(Request $request)
    {
        try {
            $id = $request->input('id');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            DB::statement('CALL soft_delete_floor(?, ?)', [$id, $changed_by]);

            return response()->json(['message' => 'Floor soft deleted successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Failed to soft delete floor.', 'error' => $e->getMessage()], 500);
        }
    }
}
