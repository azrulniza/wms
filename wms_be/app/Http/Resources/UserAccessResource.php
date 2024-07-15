<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAccessResource extends JsonResource
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
            'employee_id' => $this->employee_id,
            'user_role' => $this->user_role,
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'email_verified_at' => $this->email_verified_at,
            'user_password' => $this->user_password,
            'remember_token' => $this->remember_token,
            'active' => $this->active,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on,
            'changed_by' => $this->changed_by,
            'changed_on' => $this->changed_on,
            'user_role_name' => $this->user_role_name,
            'user_role_id' => $this->user_role_id
        ];
    }
}
