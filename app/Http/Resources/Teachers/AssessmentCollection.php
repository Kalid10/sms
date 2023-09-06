<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AssessmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => parent::toArray($request),
            'stats' => [
                'marking_count' => $this->pending(),
                'scheduled_count' => $this->scheduled(),
                'total_count' => $this->total(),
            ],
        ];
    }

    private function pending(): int
    {
        return $this->collection->filter(function ($assessment) {
            return in_array($assessment->status, ['published', 'marking']);
        })->count();
    }

    private function scheduled(): int
    {
        return $this->collection->filter(function ($assessment) {
            return $assessment->status === 'scheduled';
        })->count();
    }

    private function total(): int
    {
        return $this->collection->count();
    }
}
