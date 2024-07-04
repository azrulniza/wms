<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE `get_floor`()
            BEGIN
                SELECT * FROM tbl_setting_floor order by floor asc;
            END
        ');

        // Stored procedure to get floor details
        DB::unprepared('
            CREATE PROCEDURE get_floor_details(
                IN p_id BIGINT
            )
            BEGIN
                SELECT 
                    id,
                    floor,
                    active,
                    created_by,
                    created_on,
                    changed_by,
                    changed_on
                FROM tbl_setting_floor
                WHERE id = p_id;
            END
        ');

        // Stored procedure to insert a new floor
        DB::unprepared('
            CREATE PROCEDURE insert_floor(
                IN p_floor VARCHAR(255),
                IN p_created_by VARCHAR(255)
            )
            BEGIN
                INSERT INTO tbl_setting_floor (floor, active, created_by, created_on)
                VALUES (p_floor, 1, p_created_by, NOW());
            END
        ');

        // Stored procedure to update an existing floor
        DB::unprepared('
            CREATE PROCEDURE update_floor(
                IN p_id BIGINT,
                IN p_floor VARCHAR(255),
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_floor
                SET 
                    floor = p_floor,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');

        // Stored procedure to soft delete a floor (set active to 0)
        DB::unprepared('
            CREATE PROCEDURE soft_delete_floor(
                IN p_id BIGINT,
                IN p_changed_by VARCHAR(255)
            )
            BEGIN
                UPDATE tbl_setting_floor
                SET 
                    active = 0,
                    changed_by = p_changed_by,
                    changed_on = NOW()
                WHERE id = p_id;
            END
        ');
    }
};
