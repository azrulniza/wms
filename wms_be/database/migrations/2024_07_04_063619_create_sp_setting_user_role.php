<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE get_user_roles()
            BEGIN
                SELECT * FROM tbl_setting_user_role;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE InsertUserRole(
                IN p_role VARCHAR(50),
                IN p_created_by VARCHAR(255),
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                INSERT INTO tbl_setting_user_role (role, created_by, created_on, changed_by, changed_on)
                VALUES (p_role, p_created_by, CURRENT_TIMESTAMP, p_changed_by, NULL);
            END
        ');

        DB::unprepared("
            CREATE PROCEDURE UpdateUserRole(
                IN p_id INT,
                IN p_role VARCHAR(50),
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_user_role
                SET 
                    role = p_role,
                    changed_by = p_changed_by,
                    changed_on = CURRENT_TIMESTAMP
                WHERE id = p_id;
            END 
        ");

        DB::unprepared("
            CREATE PROCEDURE soft_delete_user_role(
                IN p_id INT,
                IN p_active TINYINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_user_role
                SET 
                    active = p_active,
                    changed_by = p_changed_by,
                    changed_on = CURRENT_TIMESTAMP
                WHERE id = p_id;
            END 
        ");


        DB::unprepared(
            "
            CREATE PROCEDURE get_user_role_details(
                IN p_id INT
            )
            BEGIN
                SELECT * FROM  tbl_setting_user_role
                WHERE id = p_id;
            END"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_setting_user_role');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_user_roles');
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertUserRole');
        DB::unprepared('DROP PROCEDURE IF EXISTS UpdateUserRole');
        DB::unprepared('DROP PROCEDURE IF EXISTS soft_delete_user_role');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_user_role_details');
    }
};
