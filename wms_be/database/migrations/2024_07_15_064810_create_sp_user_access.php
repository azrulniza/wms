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

        // Create the stored procedure
        DB::unprepared('
            CREATE PROCEDURE get_user_details(IN userId INT)
            BEGIN
                SELECT u.id, u.user_name, u.user_email, u.user_role, r.role AS user_role_name, u.active
                FROM tbl_user_access u
                JOIN tbl_setting_user_role r ON u.user_role = r.id
                WHERE u.id = userId;
            END
        ');

        DB::unprepared('
            DELIMITER //
            CREATE PROCEDURE update_user(
                IN p_user_id INT,
                IN p_user_email VARCHAR(255),
                IN p_user_role_id INT,
                IN p_user_password VARCHAR(255),
                IN p_employee_id INT
            )
            BEGIN
                -- Declare a variable to store the existing password hash
                DECLARE v_existing_password VARCHAR(255);

                -- Get the current password hash from the database
                SELECT user_password INTO v_existing_password
                FROM tbl_user_access
                WHERE id = p_user_id;

                -- Check if a new password is provided
                IF p_user_password IS NOT NULL AND p_user_password != \'\' THEN
                    -- Update user email, role, and password
                    UPDATE tbl_user_access
                    SET
                        user_email = p_user_email,
                        user_role = p_user_role_id,
                        user_password = p_user_password,
                        employee_id = p_employee_id
                    WHERE id = p_user_id;
                ELSE
                    -- Update user email and role only
                    UPDATE tbl_user_access
                    SET
                        user_email = p_user_email,
                        user_role = p_user_role_id,
                        employee_id = p_employee_id
                    WHERE id = p_user_id;
                END IF;
            END //
            DELIMITER ;
        ');

        DB::unprepared('
            DELIMITER //
            CREATE PROCEDURE soft_delete_user_access(
                IN p_user_id INT,
                IN p_status TINYINT,
                IN p_changed_by INT
            )
            BEGIN
                UPDATE tbl_user_access
                SET active = p_status, changed_by = p_changed_by
                WHERE id = p_user_id;
            END //
            DELIMITER ;
        ');

        DB::unprepared('
            DELIMITER $$

            CREATE PROCEDURE get_active_user_by_email(IN user_email VARCHAR(255))
            BEGIN
                SELECT * FROM tbl_user_access
                WHERE user_email = user_email AND active = 1;
            END $$

            DELIMITER ;
        ');

        DB::unprepared('
            DELIMITER $$

            CREATE PROCEDURE update_forgot_password_details(
                IN user_id INT, 
                IN new_token VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_user_access
                SET 
                    forgot_password_expired_on = DATE_ADD(NOW(), INTERVAL 1 HOUR),
                    forgot_password_token = new_token
                WHERE id = user_id;

                SELECT forgot_password_expired_on, forgot_password_token
                FROM tbl_user_access
                WHERE id = user_id;
            END $$

            DELIMITER ;
        ');

        DB::unprepared('
            DELIMITER //

            CREATE PROCEDURE `validate_reset_token` (
                IN user_email_param VARCHAR(255),
                IN forgot_password_token_param VARCHAR(255),
                IN current_time_param DATETIME
            )
            BEGIN
                DECLARE valid INT DEFAULT 0;

                SELECT COUNT(*)
                INTO valid
                FROM tbl_user_access
                WHERE user_email = user_email_param
                AND forgot_password_token = forgot_password_token_param
                AND forgot_password_expired_on >= current_time_param;

                SELECT valid;
            END //

            DELIMITER ;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS get_user_access');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_user_details');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_user');
        DB::unprepared('DROP PROCEDURE IF EXISTS soft_delete_user_access');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_active_user_by_email');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_forgot_password_details');
    }
};
