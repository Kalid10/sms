<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\GuardianResource;
use App\Http\Resources\StudentCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GuardianController extends Controller
{
    public function index(): GuardianResource
    {
        return new GuardianResource(Auth::user()->load('guardian'));
    }

    public function children(): StudentCollection
    {
        return new StudentCollection(Auth::user()->load('guardian.children.user')->guardian->children);
    }
}
