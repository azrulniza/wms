<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAccessResource;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Validator;


class AuthController extends Controller
{

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'user_name' => 'required',
            'user_role' => 'required',
            'user_email' => 'required|email|unique:tbl_user_access',
            'user_password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            // Check if the employee exists by email
            $employee = DB::select('CALL get_employee_by_email(?)', [request()->user_email]);
            DB::commit();


            if (empty($employee)) {
                return response()->json(['error' => 'Employee does not exist'], 400);
            }

            $user = new User;
            $user->user_name = request()->user_name;
            $user->user_role = request()->user_role;
            $user->user_email = request()->user_email;
            $user->user_password = bcrypt(request()->user_password);
            // Set the employee ID if an employee exists
            $user->employee_id = $employee[0]->id;
            $user->save();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);

            // Log the error for debugging purposes
            Log::error('Failed to get user: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to get user'], 500);
        }
        return response()->json($user, 201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = [
            'user_email' => request()->user_email,
            'password' => request()->user_password,
            'active' => 1, // Ensure the user is active
        ];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        //return response()->json(auth()->user());
        return new UserAccessResource(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth()->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'user_role' => $user->user_role,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
            ]
        ]);
    }

    public function getAllUserAccess()
    {
        $users = DB::select('CALL get_user_access()');

        return UserAccessResource::collection($users);
    }
}
