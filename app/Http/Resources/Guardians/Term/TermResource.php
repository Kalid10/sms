<?php

namespace App\Http\Resources\Guardians\Term;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return match ($request->input('type')) {
            'quarter' => $this->quarter($this),
            'semester' => $this->semester($this),
            'school_year' => $this->schoolYear($this)
        };
    }

    private function quarter($quarter): array
    {
        return [
            'id' => $quarter->id,
            'name' => $quarter->name,
            'start_date' => $quarter->start_date,
            'end_date' => $quarter->end_date,
            'semester' => $this->semester($quarter->semester),
        ];
    }

    private function semester($semester): array
    {
        return [
            'id' => $semester->id,
            'name' => $semester->name,
            'start_date' => $semester->start_date,
            'end_date' => $semester->end_date,
            'school_year' => $this->schoolYear($semester->schoolYear),
        ];
    }

    private function schoolYear($schoolYear): array
    {
        return [
            'id' => $schoolYear->id,
            'name' => $schoolYear->name,
            'start_date' => $schoolYear->start_date,
            'end_date' => $schoolYear->end_date,
        ];
    }
}
