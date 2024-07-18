<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'tbl_master_details_employee';

    // Define the fields that can be mass assigned
    protected $fillable = [
        'employee_staff_id',
        'employee_ic_no',
        'employee_name',
        'employee_email',
        'employee_phone_no',
        'employee_floor',
        'employee_seniority',
        'employee_agency',
        'employee_position',
        'employee_position_level',
        'employee_position_status',
        'employee_start_join_dt',
        'employee_end_join_dt',
        'employee_conversion_dt',
        'employee_appraisal_dt',
        'active',
        'pdpa',
        'employee_remarks',
        'created_by',
        'created_on',
        'changed_by',
        'changed_on'
    ];
}
