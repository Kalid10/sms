<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_id' => $this->id,
            'student_name' => $this->user->name,
            'student_notes' => $this->studentNotes->map(function ($studentNote) {
                return [
                    'id' => $studentNote->id,
                    'title' => $studentNote->title,
                    'description' => $studentNote->description,
                    'date' => $studentNote->created_at,
                    'author' => $studentNote->author->name,
                    'author_type' => $studentNote->author->type,
                ];
            }),
        ];
    }
}
