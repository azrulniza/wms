<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Http\Resources\ConfigMasterDetailsResource;

class ConfigMasterDetailsController extends Controller
{
    public function getFloor()
    {
        try {
            DB::beginTransaction();

            // Call the stored procedure and fetch the results directly
            $floor = DB::select('CALL get_floor()');

            DB::commit();

            // Transform and return the users as a JSON response
            return ConfigMasterDetailsResource::collection($floor);
        } catch (Exception $e) {
            DB::rollBack();

            // Log the error for debugging purposes
            Log::error('Failed to get floor: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to get floor'], 500);
        }
    }
}
