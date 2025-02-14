<?php

namespace App\Http\Resources\Guardians\Guardian;

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
        return [
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'phone_number' => $this->phone_number,
            'date_of_birth' => $this->date_of_birth,
            'guardian_id' => $this->guardian->id,
            'city' => $this->address?->city,
            'sub_city' => $this->address?->sub_city,
            'woreda' => $this->address?->woreda,
            'house_number' => $this->address?->house_number,
        ];
    }
}
