<?php

namespace App\Http\Controllers;

use App\Helpers\SMSHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SMSController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/SMS');
    }

    public function send(Request $request): RedirectResponse
    {
        // Validate request data
        $validated = $request->validate([
            'phone' => 'required',
            'message' => 'required',
        ]);

        // Send SMS
        $smsHelper = new SMSHelper();
        $response = $smsHelper->send($validated['phone'], $validated['message']);

        // Handle response
        if ($response['status'] === 200) {
            return redirect()->back()->with('success', 'SMS sent successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to send SMS!');
        }
    }
}
