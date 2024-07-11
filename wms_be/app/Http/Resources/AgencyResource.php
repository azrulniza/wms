<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyResource extends JsonResource
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
            'agency_name' => $this->agency_name,
            'agency_address' => $this->agency_address,
            'agency_phone_no' => $this->agency_phone_no,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on,
            'changed_by' => $this->changed_by,
            'changed_on' => $this->changed_on,
        ];
    }
}
