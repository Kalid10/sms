<?php

namespace App\Http\Resources\Term;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TermCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $request->input('type'),
            'terms' => parent::toArray($request),
        ];
    }
}
