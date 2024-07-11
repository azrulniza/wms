<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class EmployeeController extends Controller
{
    public function getEmployee()
    {
        try {
            DB::beginTransaction();

            // Call the stored procedure and fetch the results directly
            $employee = DB::select('CALL get_employee()');

            DB::commit();

            // Transform and return the users as a JSON response
            return EmployeeResource::collection($employee);
        } catch (Exception $e) {
            DB::rollBack();

            // Log the error for debugging purposes
            Log::error('Failed to get employee: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to get employee'], 500);
        }
    }

    public function insertEmployee(Request $request)
    {
        try {
            // Retrieve the validated input data
            $employee_staff_id = $request->input('employee_staff_id');
            $employee_ic_no = $request->input('employee_ic_no');
            $employee_name = $request->input('employee_name');
            $employee_email = $request->input('employee_email');
            $employee_phone_no = $request->input('employee_phone_no');
            $created_by = $request->input('created_by');
            // Call the stored procedure
            $result = DB::select('CALL insert_employee(?, ?, ?, ?, ?, ?)', [
                $employee_staff_id,
                $employee_ic_no,
                $employee_name,
                $employee_email,
                $employee_phone_no,
                $created_by

            ]);

            // Return a success response
            return response()->json([
                'message' => 'Employee inserted successfully.', 'data' => $result[0]
            ], 200);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error inserting employee: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => 'Failed to insert employee.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateEmployment(Request $request)
    {
        try {
            $id = $request->input('id');
            $employee_floor = $request->input('employee_floor');
            $employee_seniority = $request->input('employee_seniority');
            $employee_agency = $request->input('employee_agency');
            $employee_position = $request->input('employee_position');
            $employee_position_level = $request->input('employee_position_level');
            $employee_position_status = $request->input('employee_position_status');
            $employee_start_join_dt = $request->input('employee_start_join_dt');
            $employee_end_join_dt = $request->input('employee_end_join_dt');
            $active = $request->input('active') ?: 1;
            $pdpa = $request->input('pdpa');
            $employee_remarks = $request->input('employee_remarks');
            $employee_conversion_dt = $request->input('employee_conversion_dt');
            $employee_appraisal_dt = $request->input('employee_appraisal_dt');

            // Calculate employee_appraisal_dt if position status is 2
            if ($employee_position_status == 2 && $employee_end_join_dt) {
                $employee_end_join_dt_carbon = \Carbon\Carbon::parse($employee_end_join_dt);
                $employee_appraisal_dt = $employee_end_join_dt_carbon->subMonth()->subDays(7)->toDateTimeString();
            }

            // Example: Get current authenticated user's name (if using authentication)
            $changed_by = 1; //auth()->user()->name;
            $changed_on = now(); // Example: Current datetime

            // Execute the stored procedure
            $result = DB::select('CALL update_employment(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)', [
                $id,
                $employee_floor,
                $employee_seniority,
                $employee_agency,
                $employee_position,
                $employee_position_level,
                $employee_position_status,
                $employee_start_join_dt,
                $employee_end_join_dt,
                $active,
                $pdpa,
                $employee_remarks,
                $employee_conversion_dt,
                $employee_appraisal_dt,
                $changed_by,
                $changed_on,
            ]);

            return response()->json(['message' => 'Employment details updated successfully', 'data' => $result]);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An unexpected error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateEmployeeProfile(Request $request)
    {
        try {
            $id = $request->input('id');
            $employee_name = $request->input('employee_name');
            $employee_email = $request->input('employee_email');
            $employee_phone_no = $request->input('employee_phone_no');
            $employee_ic_no = $request->input('employee_ic_no');
            $employee_staff_id = $request->input('employee_staff_id');
            $changed_by = $request->input('changed_by');

            // Execute the stored procedure
            $result = DB::select('CALL update_employee_profile(?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $employee_staff_id,
                $employee_ic_no,
                $employee_name,
                $employee_email,
                $employee_phone_no,
                $changed_by

            ]);

            return response()->json(['message' => 'Employee details updated successfully', 'data' => $result]);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An unexpected error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    public function softDeleteEmployee(Request $request)
    {
        try {
            $id = $request->input('id');
            $active = $request->input('active', 0); // Default to 0 (inactive) if not provided
            $changed_by = 1; //auth()->user()->name; // Example: Get current authenticated user's name

            // Execute the stored procedure
            $result = DB::select('CALL soft_delete_employee(?, ?, ?)', [$id, $active, $changed_by]);

            if ($active == 0) {
                return response()->json(['message' => 'Employee deactivated successfully', 'data' => $result]);
            } else {
                return response()->json(['message' => 'Employee activated successfully', 'data' => $result]);
            }
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An unexpected error occurred.', 'error' => $e->getMessage()], 500);
        }
    }


    public function getEmployeeDetails($id)
    {
        try {
            // Execute the stored procedure
            $employee = DB::select('CALL get_employee_details(?)', [$id]);

            if (empty($employee)) {
                return response()->json(['message' => 'Employee not found']);
            } else {
                $employee = $employee[0]; // Since it's an array of stdClass objects, we take the first one

                return response()->json([
                    'id' => $employee->id,
                    'profile' => [
                        'employee_name' => $employee->employee_name,
                        'employee_email' => $employee->employee_email,
                        'employee_phone_no' => $employee->employee_phone_no,
                        'employee_staff_id' => $employee->employee_staff_id,
                        'employee_ic_no' => $employee->employee_ic_no,
                    ],
                    'employment' => [
                        'employee_floor' => $employee->employee_floor,
                        'employee_seniority' => $employee->employee_seniority,
                        'employee_agency' => $employee->employee_agency,
                        'employee_position' => $employee->employee_position,
                        'employee_position_level' => $employee->employee_position_level,
                        'employee_position_status' => $employee->employee_position_status,
                        'employee_start_join_dt' => $employee->employee_start_join_dt,
                        'employee_end_join_dt' => $employee->employee_end_join_dt,
                        'employee_conversion_dt' => $employee->employee_conversion_dt,
                        'employee_appraisal_dt' => $employee->employee_appraisal_dt,
                        'active' => $employee->active,
                        'pdpa' => $employee->pdpa,
                        'employee_remarks' => $employee->employee_remarks,
                        'created_by' => $employee->created_by,
                        'created_on' => $employee->created_on,
                        'changed_by' => $employee->changed_by,
                        'changed_on' => $employee->changed_on,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An unexpected error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}
