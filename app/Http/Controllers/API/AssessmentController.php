<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\AssessmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $children = Auth::user()
            ->load('guardian.children.user', 'guardian.children.assessments')
            ->guardian->children;

        return AssessmentResource::collection($children);
    }

    public function childAssessment($id): JsonResponse
    {
        // Find the authenticated user (guardian)
        $guardian = Auth::user();
        if (! $guardian) {
            return response()->json([
                'message' => 'Guardian not found.',
            ], 404);
        }

        // Find the child under this guardian
        $child = $guardian->guardian->children()->with('user', 'assessments')->find($id);

        if (! $child) {
            return response()->json([
                'message' => 'Child not found.',
            ], 404);
        }

        return response()->json(new AssessmentResource($child));
    }
}
