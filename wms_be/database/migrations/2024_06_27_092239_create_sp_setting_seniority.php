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
        // Stored procedure to get all setting seniority
        DB::unprepared('
            CREATE PROCEDURE get_all_setting_seniority()
            BEGIN
                SELECT * FROM tbl_setting_seniority ORDER BY seniority ASC;
            END
        ');

        // Stored procedure to insert a new seniority
        DB::unprepared('
            CREATE PROCEDURE insert_seniority(
                IN p_seniority VARCHAR(255),
                IN p_created_by VARCHAR(255)
            )
            BEGIN
                INSERT INTO tbl_setting_seniority (seniority, active, created_by, created_on)
                VALUES (p_seniority, 1, p_created_by, NOW());
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE update_seniority(
                IN p_id BIGINT,
                IN p_seniority VARCHAR(255),
                IN p_active TINYINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_seniority
                SET 
                    seniority = p_seniority,
                    active = p_active,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE soft_delete_seniority(
                IN p_id BIGINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_seniority
                SET 
                    active = 0,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE get_seniority(
                IN p_id BIGINT
            )
            BEGIN
                SELECT * FROM tbl_setting_seniority
                WHERE id = p_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_setting_seniority');
    }
};
