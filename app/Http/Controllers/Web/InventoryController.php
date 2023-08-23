<?php

namespace App\Http\Controllers\Web;

use App\Models\InventoryItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Inventory/Index');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'visibility' => 'required|string|in:teachers,admins,all',
            'is_returnable' => 'required|boolean',
            'quantity' => 'required|integer',
        ]);

        InventoryItem::create([
            'added_by_user_id' => auth()->user()->id,
            'name' => $request->name,
            'description' => $request->desscription,
            'visibility' => $request->visibility,
            'is_returnable' => $request->is_returnable,
            'quantity' => $request->quantity,
            'date' => Carbon::now(),
            'status' => 'available',
        ]);

        return redirect()->back()->with('success', 'You have successfully added '.$request->name.' to the inventory list.');
    }
}
