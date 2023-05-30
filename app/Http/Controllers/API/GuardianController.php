<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\GuardianResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GuardianController extends Controller
{
    public function index(): GuardianResource
    {
        return new GuardianResource(Auth::user()->load('guardian'));
    }

    // Todo: Resources for all guardians children
}
