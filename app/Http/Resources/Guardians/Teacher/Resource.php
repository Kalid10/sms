<?php

namespace App\Http\Resources\Guardians\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //        return parent::toArray($request);
        return [
            'teacher_id' => $this->id,
            'name' => $this->user->name,
            'profile_image' => $this->user->profile_image,
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
            'gender' => $this->user->gender,
        ];
    }
}
