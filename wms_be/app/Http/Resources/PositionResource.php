<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
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
            'position' => $this->position,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on,
            'changed_by' => $this->changed_by,
            'changed_on' => $this->changed_on,
        ];
    }
}
