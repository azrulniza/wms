<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Http\Resources\AgencyResource;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function getAgency()
    {
        try {
            DB::beginTransaction();

            // Call the stored procedure and fetch the results directly
            $agency = DB::select('CALL get_agency()');

            DB::commit();

            // Transform and return the users as a JSON response
            return AgencyResource::collection($agency);
        } catch (Exception $e) {
            DB::rollBack();

            // Log the error for debugging purposes
            Log::error('Failed to get agency: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to get agency'], 500);
        }
    }

    public function insertAgency(Request $request)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Extract parameters from the request
            $agency_name = $request->input('agency_name');
            $agency_address = $request->input('agency_address');
            $agency_phone_no = $request->input('agency_phone_no');

            // Call the stored procedure
            $result = DB::select('CALL insert_agency(?, ?, ?)', array($agency_name, $agency_address, $agency_phone_no));

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json(['message' => 'Agency inserted successfully', 'data' => $result], 200);
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error
            Log::error('Failed to insert agency: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to insert agency'], 500);
        }
    }

    public function updateAgency(Request $request, $id)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Extract parameters from the request
            $agency_name = $request->input('agency_name');
            $agency_address = $request->input('agency_address');
            $agency_phone_no = $request->input('agency_phone_no');


            // Call the stored procedure to update agency
            $result = DB::select('CALL update_agency(?, ?, ?, ?)', array($id, $agency_name, $agency_address, $agency_phone_no));

            // Commit the transaction
            DB::commit();


            // Return a success response
            return response()->json(['message' => 'Agency updated successfully', 'data' => $result], 200);
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error
            Log::error('Failed to update agency: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to update agency'], 500);
        }
    }

    public function softDeleteAgency($id)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Call the stored procedure to soft delete the agency
            $result = DB::select('CALL soft_delete_agency(?)', [$id]);

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json(['message' => 'Agency soft deleted successfully', 'data' => $result], 200);
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error
            Log::error('Failed to soft delete agency: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to soft delete agency'], 500);
        }
    }
}
