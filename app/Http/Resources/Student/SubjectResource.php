<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'name' => $this->user->name,
            'username' => $this->user->username,
            'subjects' => $this->batches->flatMap(function ($batch) {
                return $batch->batch->subjects->map(function ($subject) {
                    return [
                        'subject_id' => $subject->subject->id,
                        'subject_full_name' => $subject->subject->full_name,
                        'subject_short_name' => $subject->subject->short_name,
                        'subject_category' => $subject->subject->category,
                        'subject_tags' => $subject->subject->tags,
                        'teacher' => [
                            'id' => $subject->teacher->id,
                            'name' => $subject->teacher->user->name,
                            'email' => $subject->teacher->user->email,
                            'gender' => $subject->teacher->user->gender,

                        ],
                    ];
                });
            }),
        ];
    }
}
