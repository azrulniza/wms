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
            CREATE PROCEDURE get_latest_earning_by_employee_id(IN emp_id TINYINT)
            BEGIN
                SELECT * 
                FROM tbl_master_detail_earning
                WHERE employee_id = emp_id
                ORDER BY created_on DESC
                LIMIT 1;
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE insert_earning(
                IN p_employee_id TINYINT,
                IN p_bank_name VARCHAR(100),
                IN p_bank_acc VARCHAR(100),
                IN p_basic_salary DOUBLE,
                IN p_created_by VARCHAR(255)
            )
            BEGIN
                INSERT INTO tbl_master_detail_earning (
                    employee_id,
                    bank_name,
                    bank_acc,
                    basic_salary,
                    created_by,
                    created_on
                ) VALUES (
                    p_employee_id,
                    p_bank_name,
                    p_bank_acc,
                    p_basic_salary,
                    p_created_by,
                    CURRENT_TIMESTAMP
                );
            END //
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS get_latest_earning_by_employee_id');
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_earning');
    }
};
