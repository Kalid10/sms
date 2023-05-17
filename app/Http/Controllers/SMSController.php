<?php

namespace App\Http\Controllers;

use App\Helpers\SMSHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SMSController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/SMS');
    }

    public function send(Request $request)
    {
        // validate request data
        $validated = $request->validate([
            'phone' => 'required',
            'message' => 'required',
        ]);

        // send SMS
        $smsHelper = new SMSHelper();
        $response = $smsHelper->send($validated['phone'], $validated['message']);

        // handle response
        if ($response['status'] === 200) {
            return redirect()->back()->with('success', 'SMS sent successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to send SMS!');
        }
    }
}
