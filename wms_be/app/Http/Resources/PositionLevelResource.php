<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionLevelResource extends JsonResource
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
            'position_lvl' => $this->position_lvl,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on,
            'changed_by' => $this->changed_by,
            'changed_on' => $this->changed_on,
        ];
    }
}
