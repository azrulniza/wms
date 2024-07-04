<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->employee_name,
            'employee_staff_id' => $this->employee_staff_id,
            'employee_ic_no' => $this->employee_ic_no,
            'employee_email' => $this->employee_email,
            'employee_phone_no' => $this->employee_phone_no,
            'employee_agency' => $this->employee_agency,
            'employee_position' => $this->employee_position,
            'employee_yow' => $this->employee_yow,
            'active' => $this->active

        ];
    }
}
