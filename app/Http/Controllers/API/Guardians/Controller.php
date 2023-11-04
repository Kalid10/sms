<?php

namespace App\Http\Controllers\API\Guardians;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends \App\Http\Controllers\API\Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
