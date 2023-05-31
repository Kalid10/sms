<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\AssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Models\Student;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AssessmentController extends Controller
{
    public function index(AssessmentRequest $request, ?Student $student): AssessmentResource|Application|ResponseFactory|\Illuminate\Foundation\Application|Response
    {
        return $student->exists ?
            new AssessmentResource($student->load('user', 'assessments')) :
            response(AssessmentResource::collection(
                request()->user()
                    ->load('guardian.children.user', 'guardian.children.assessments')
                    ->guardian->children
            ));
    }
}
