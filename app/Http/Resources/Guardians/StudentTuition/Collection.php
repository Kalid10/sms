<?php

namespace App\Http\Resources\Guardians\StudentTuition;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
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
                'total' => $this->collection->count(),
                'pending' => $this->collection->filter(function ($studentTuition) {
                    return $studentTuition->status === 'unpaid';
                })->count(),
                'overdue' => $this->collection->filter(function ($studentTuition) {
                    return $studentTuition->status === 'unpaid' &&
                        Carbon::createFromDate($studentTuition->fee->due_date)->isPast();
                })->count(),
                'paid' => $this->collection->filter(function ($studentTuition) {
                    return $studentTuition->status === 'paid';
                })->count(),
            ],
        ];
    }
}
