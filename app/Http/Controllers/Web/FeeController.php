<?php

namespace App\Http\Controllers\Web;

use App\Models\Fee;
use App\Models\PaymentProvider;
use Inertia\Inertia;
use Inertia\Response;

class FeeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Fees/Index', [
            'payment_providers' => PaymentProvider::all(),
            'fees' => Fee::all(),
        ]);
    }
}
