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
            CREATE PROCEDURE get_position_levels()
            BEGIN
                SELECT * FROM tbl_setting_position_lvl;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE insert_position_level(
                IN p_position_lvl VARCHAR(255),
                IN p_created_by VARCHAR(255)
            )
            BEGIN
                INSERT INTO tbl_setting_position_lvl (position_lvl, active, created_by, created_on)
                VALUES (p_position_lvl, 1, p_created_by, NOW());
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE update_position_level(
                IN p_id BIGINT,
                IN p_position_lvl VARCHAR(255),
                IN p_active TINYINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_position_lvl
                SET 
                    position_lvl = p_position_lvl,
                    active = p_active,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE get_position_level_detail(
                IN p_id BIGINT
            )
            BEGIN
                SELECT * FROM tbl_setting_position_lvl WHERE id = p_id;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE soft_delete_position_level(
                IN p_id BIGINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_position_lvl
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
        Schema::dropIfExists('sp_position_level');
        DB::unprepared('DROP PROCEDURE IF EXISTS soft_delete_position_level');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_position_level');
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_position_level');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_position_level_detail');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_position_levels');
    }
};
