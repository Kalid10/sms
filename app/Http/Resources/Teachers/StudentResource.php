<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'guardian_id' => $this->guardian_id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
            'username' => $this->user->username,
            'profile_image' => $this->user->profile_image,
            'gender' => $this->user->gender,
            'date_of_birth' => $this->user->date_of_birth,
        ];
    }
}
