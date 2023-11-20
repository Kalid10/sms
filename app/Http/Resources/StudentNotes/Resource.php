<?php

namespace App\Http\Resources\StudentNotes;

use App\Http\Resources\Teachers\StudentResource;
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
            'id' => $this->id,
            'student' => new StudentResource($this->student),
            'title' => $this->title,
            'description' => $this->description,
            'author_id' => $this->author_id,
            'author' => new \App\Http\Resources\Teachers\Resource($this->author),
        ];
    }
}
