<?php

namespace App\Http\Resources\Student;

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
        $student = $this->resource;
        $activeBatch = $student->activeBatch();
        $section = $student->currentBatch ? $student->currentBatch->first()->section : null;
        $homeRoomTeacher = $student->currentBatch ? $student->currentBatch->first()->homeRoomTeacher?->teacher : null;

        return [
            'id' => $this->user->id,
            'student_id' => $this->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'profile_image' => $this->user->profile_image,
            'email' => $this->user->email,
            'gender' => $this->user->gender,
            'date_of_birth' => $this->user->date_of_birth,
            'city' => $this->user->address?->city,
            'sub_city' => $this->user->address?->sub_city,
            'woreda' => $this->user->address?->woreda,
            'house_number' => $this->user->address?->house_number,
            'level' => $activeBatch ? $activeBatch->level->name : null,
            'section' => $section,
            'user_id' => $this->user_id,
            'guardian_id' => $this->guardian_id,
            'home_room_teacher' => $homeRoomTeacher?->user?->name,
            'home_room_teacher_id' => $homeRoomTeacher?->id,
        ];
    }
}
