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
        // Create stored procedure to get all positions
        DB::unprepared('
            CREATE PROCEDURE get_positions()
            BEGIN
                SELECT * FROM tbl_setting_position ORDER BY position ASC;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE insert_position(
                IN p_position VARCHAR(255),
                IN p_created_by VARCHAR(255)
            )
            BEGIN
                INSERT INTO tbl_setting_position (position, active, created_by, created_on)
                VALUES (p_position, 1, p_created_by, NOW());
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE update_position(
                IN p_id BIGINT,
                IN p_position VARCHAR(255),
                IN p_active TINYINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_position
                SET 
                    position = p_position,
                    active = p_active,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE get_position_detail(
                IN p_id BIGINT
            )
            BEGIN
                SELECT * FROM tbl_setting_position WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE soft_delete_position(
                IN p_id BIGINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_position
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
        Schema::dropIfExists('sp_positions');
    }
};
