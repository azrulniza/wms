<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE get_user_access()
            BEGIN
                SELECT 
                    u.id, 
                    u.employee_id, 
                    u.user_role, 
                    r.id as user_role_id,
                    r.role as user_role_name,
                    u.user_name, 
                    u.user_email, 
                    u.email_verified_at, 
                    u.user_password, 
                    u.remember_token, 
                    u.active, 
                    u.created_by, 
                    u.created_on, 
                    u.changed_by, 
                    u.changed_on 
                FROM tbl_user_access u
                LEFT JOIN tbl_setting_user_role r ON u.user_role = r.id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS get_user_access');
    }
};
