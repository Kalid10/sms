<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\AssessmentResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index(): JsonResponse|Application|ResponseFactory|Response
    {
        $children = Auth::user()
            ->load('guardian.children.user', 'guardian.children.assessments')
            ->guardian->children;

        return response(AssessmentResource::collection($children));
    }

    public function childAssessment($id): JsonResponse|Application|ResponseFactory|Response
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

        return response(new AssessmentResource($child));
    }
}
