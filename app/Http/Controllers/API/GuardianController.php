<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Guardian\Resource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GuardianController extends Controller
{
    public function index(): Resource
    {
        return new Resource(Auth::user()->load('guardian'));
    }
}
