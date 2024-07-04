<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class EarningController extends Controller
{
    /**
     * Get the latest created earning by employee ID.
     *
     * @param  int  $employeeId
     * @return \Illuminate\Http\Response
     */
    public function getLatestEarningByEmployeeId($employeeId)
    {
        $latestEarning = DB::select('CALL get_latest_earning_by_employee_id(?)', [$employeeId]);

        if (!empty($latestEarning)) {
            return response()->json($latestEarning[0]);
        } else {
            return response()->json(['message' => 'No earnings found for this employee'], 404);
        }
    }

    public function insertEarning(Request $request)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Extract parameters from the request
            $employee_id = $request->input('employee_id');
            $bank_name = $request->input('bank_name');
            $bank_acc = $request->input('bank_acc');
            $basic_salary = $request->input('basic_salary');
            $created_by = $request->input('created_by');

            // Call the stored procedure
            $result = DB::select('CALL insert_earning(?, ?, ?, ?, ?)', [
                $employee_id,
                $bank_name,
                $bank_acc,
                $basic_salary,
                $created_by
            ]);

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json(['message' => 'Earning record inserted successfully', 'data' => $result], 200);
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error
            Log::error('Failed to insert earning record: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to insert earning record'], 500);
        }
    }
}
