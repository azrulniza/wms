<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'position_sts' => $this->position_sts,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on,
            'changed_by' => $this->changed_by,
            'changed_on' => $this->changed_on,
        ];
    }
}
