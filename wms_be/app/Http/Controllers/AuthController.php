<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAccessResource;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            //'user_name' => 'required',
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
                return response()->json(['error' => 'Employee does not exist']);
            }

            $user = new User;
            //$user->user_name = request()->user_name;
            $user->user_role = request()->user_role;
            $user->user_email = request()->user_email;
            $user->user_password = bcrypt(request()->user_password);
            // Set the employee ID if an employee exists
            $user->employee_id = $employee[0]->id;
            $user->save();
        } catch (Exception $e) {
            DB::rollBack();

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
    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string|min:6',
        ]);

        // Get credentials from the request
        $credentials = $request->only('user_email', 'user_password');

        // Ensure the user is active
        $credentials['active'] = 1;

        // Rename the keys to 'email' and 'password' for the attempt method
        $credentialsForAuthAttempt = [
            'user_email' => $credentials['user_email'],
            'password' => $credentials['user_password'],
            'active' => $credentials['active'],
        ];

        if (!$token = auth()->attempt($credentialsForAuthAttempt)) {
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
        $employee = $user->employee; // Assuming the relationship is correctly set up

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'user_role' => $user->user_role,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'employee_name' => $employee->employee_name,
                'employee_phone_no' => $employee->employee_phone_no,
            ]
        ]);
    }

    public function getAllUserAccess()
    {
        $users = DB::select('CALL get_user_access()');

        return UserAccessResource::collection($users);
    }

    public function getUserDetail($id)
    {
        try {
            $userDetails = DB::select('CALL get_user_details(?)', [$id]);

            if (empty($userDetails)) {
                return response()->json(['error' => 'User not found']);
            }

            return response()->json($userDetails[0], 200);
        } catch (Exception $e) {
            Log::error('Failed to get user details: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get user details'], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'user_email' => 'required|email',
            'user_role' => 'required|integer',
            'user_password' => 'nullable|string|min:8',
        ]);

        $userEmail = $request->input('user_email');
        $userRole = $request->input('user_role');
        $userPassword = bcrypt($request->input('user_password'));

        $employee = DB::select('CALL get_employee_by_email(?)', [request()->user_email]);
        DB::commit();


        if (empty($employee)) {
            return response()->json(['error' => 'Employee does not exist']);
        }
        // Set the employee ID if an employee exists
        $employee_id = $employee[0]->id;

        try {
            DB::statement('CALL update_user(?, ?, ?, ?, ?)', [
                $id,
                $userEmail,
                $userRole,
                $userPassword,
                $employee_id
            ]);

            return response()->json([
                'id' => $id,
                'message' => 'User updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update user',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sofDeleteUserAccess(Request $request)
    {
        $request->validate([
            'active' => 'required|boolean'
        ]);

        $active = $request->input('active');
        $id = $request->input('id');
        //$changedBy = Auth::id();  // Assuming you are using Laravel's built-in Auth system
        $changedBy = 1;  // Assuming you are using Laravel's built-in Auth system

        try {
            DB::statement('CALL soft_delete_user_access(?, ?, ?)', [
                $id,
                $active,
                $changedBy
            ]);

            return response()->json([
                'message' => $active ? 'User activated successfully' : 'User deactivated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update user status',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendForgotPasswordEmail(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
        ]);

        $userEmail = $request->input('user_email');

        try {
            // Start a transaction
            DB::beginTransaction();

            // Get the active user by email
            $user = DB::select('CALL get_active_user_by_email(?)', [$userEmail]);

            if (empty($user)) {
                // Rollback the transaction if user does not exist
                DB::rollBack();
                return response()->json(['error' => 'User does not exist']);
            }

            // Generate a unique token (if you don't have one, you should generate it)
            $token = bin2hex(random_bytes(32));

            // Update forgot_password_expired_on field and get the updated value
            $result = DB::select('CALL update_forgot_password_details(?,?)', [$user[0]->id, $token]);
            $expiredOn = $result[0]->forgot_password_expired_on;
            $forgot_password_token = $result[0]->forgot_password_token;

            // Commit the transaction
            DB::commit();

            $appUrl = env('APP_URL');
            $details = [
                'title' => 'Reset Your Password',
                'body' => 'You are receiving this email because we received a password reset request for your account.',
                'reset_url' => $appUrl . '/auth/resetpassword?token=' . $forgot_password_token . '&email=' . $user[0]->user_email
            ];


            // Send email
            Mail::to($user[0]->user_email)->send(new ForgotPasswordMail($details));
            return response()->json(['message' => 'Password reset email sent successfully', 'success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to send password reset email', 'error' => $th->getMessage()]);
        }
    }

    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $email = $request->email;
        $token = $request->token;
        $currentTime = Carbon::now();

        $result = DB::select('CALL validate_reset_token(?, ?, ?)', [$email, $token, $currentTime]);

        if ($result && $result[0]->valid) {
            return response()->json(['valid' => true]);
        } else {
            return response()->json(['valid' => false]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = DB::table('tbl_user_access')
            ->where('user_email', $request->email)
            ->where('forgot_password_token', $request->token)
            //->where('forgot_password_expired_on', '>=', Carbon::now())
            ->first();

        if ($user) {
            DB::table('tbl_user_access')
                ->where('user_email', $request->email)
                ->where('id', $user->id)
                ->update([
                    'user_password' => bcrypt($request->password),
                ]);

            return response()->json(['message' => 'Password updated successfully', 'success' => true]);
        } else {
            return response()->json(['error' => 'Invalid token or email']);
        }
    }
}
