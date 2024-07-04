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
            CREATE PROCEDURE get_position_statuses()
            BEGIN
                SELECT * FROM tbl_setting_position_sts;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE add_position_status(
                IN p_position_sts VARCHAR(255),
                IN p_created_by VARCHAR(255)
            )
            BEGIN
                INSERT INTO tbl_setting_position_sts (position_sts, active, created_by, created_on)
                VALUES (p_position_sts, 1, p_created_by, NOW());
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE update_position_status(
                IN p_id BIGINT,
                IN p_position_sts VARCHAR(255),
                IN p_active TINYINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_position_sts
                SET 
                    position_sts = p_position_sts,
                    active = p_active,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE get_position_status(
                IN p_id BIGINT
            )
            BEGIN
                SELECT * FROM tbl_setting_position_sts WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE soft_delete_position_status(
                IN p_id BIGINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_position_sts
                SET 
                    active = 0,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS get_position_statuses');
        DB::unprepared('DROP PROCEDURE IF EXISTS add_position_status');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_position_status');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_position_status');
        DB::unprepared('DROP PROCEDURE IF EXISTS soft_delete_position_status');
    }
};
