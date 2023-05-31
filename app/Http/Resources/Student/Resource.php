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
        $homeRoomTeacher = $student->currentBatch ? $student->currentBatch->first()->homeRoomTeacher->teacher->user->name : null;

        return [
            'name' => $this->user->name,
            'username' => $this->user->username,
            'email' => $this->user->email,
            'gender' => $this->user->gender,
            'date_of_birth' => $this->user->date_of_birth,
            'level' => $activeBatch ? $activeBatch->level->name : null,
            'section' => $section,
            'student_id' => $this->id,
            'user_id' => $this->user_id,
            'guardian_id' => $this->guardian_id,
            'home_room_teacher' => $homeRoomTeacher,
        ];
    }
}
