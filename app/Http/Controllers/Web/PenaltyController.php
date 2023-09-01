<?php

namespace App\Http\Controllers\Web;

use App\Models\Penalty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:'.Penalty::TYPE_FLAT_RATE.','.Penalty::TYPE_PERCENTAGE.','.Penalty::TYPE_DAILY,
            'amount' => 'required',
        ]);

        Penalty::create([
            'type' => $request->type,
            'amount' => $request->amount,
        ]);

        return redirect()->back()->with('success', 'Penalty created.');
    }
}
