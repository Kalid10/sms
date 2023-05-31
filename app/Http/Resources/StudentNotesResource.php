<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentNotesResource extends JsonResource
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
            'student_id' => $this->student_id,
            'student_name' => $this->student->user->name,
            'author' => $this->author->name,
            'author_email' => $this->author->email,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->created_at->format('d-m-Y'),
        ];
    }
}
