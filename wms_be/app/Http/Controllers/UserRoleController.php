<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserRoleResource;

class UserRoleController extends Controller
{
    //
    public function getAllUserRoles()
    {
        // Call the stored procedure and get the results
        $userRoles = DB::select('CALL get_user_roles()');

        DB::commit();

        // Return the results (e.g., as JSON)
        return UserRoleResource::collection(collect($userRoles));
    }

    public function insertUserRole(Request $request)
    {
        // Extract validated data
        $role = $request->input('role');
        $createdBy = $request->input('created_by');
        $changedBy = $request->input('changed_by');

        // Call the stored procedure to insert the user role
        DB::select('CALL insert_user_role(?, ?, ?)', [$role, $createdBy, $changedBy]);

        DB::commit();
        // Return a success response
        return response()->json(['message' => 'User role inserted successfully'], 201);
    }

    public function updateUserRole(Request $request)
    {
        try {
            $id = $request->input('id');
            $role = $request->input('role');
            $changed_by = $request->input('changed_by');
            // Call the stored procedure to update the user role
            DB::statement('CALL update_user_role(?, ?, ?)', [
                $id,
                $role,
                $changed_by

            ]);
            DB::commit();

            return response()->json(['message' => 'User role updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user role: ' . $e->getMessage()], 500);
        }
    }

    public function softDeleteUserRole(Request $request)
    {
        try {
            $id = $request->input('id');
            $active = $request->input('active');
            $changed_by = $request->input('changed_by');
            // Call the stored procedure to set the active status of the user role
            DB::statement('CALL soft_delete_user_role(?, ?, ?)', [
                $id,
                $active,
                $changed_by
            ]);

            return response()->json(['message' => 'User role active status updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user role active status: ' . $e->getMessage()], 500);
        }
    }

    public function getUserRoleDetails($id)
    {
        try {
            // Call the stored procedure to get the user role details
            $userRoleDetails = DB::select('CALL get_user_role_details(?)', [$id]);

            if (empty($userRoleDetails)) {
                return response()->json(['message' => 'User role not found'], 404);
            }

            return response()->json(['data' => $userRoleDetails[0]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get user role details: ' . $e->getMessage()], 500);
        }
    }
}
