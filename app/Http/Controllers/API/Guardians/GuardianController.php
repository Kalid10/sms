<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Resources\Guardians\Guardian\Resource;
use Illuminate\Support\Facades\Auth;

class GuardianController extends Controller
{
    public function index(): Resource
    {
        return new Resource(Auth::user()->load('guardian'));
    }
}
