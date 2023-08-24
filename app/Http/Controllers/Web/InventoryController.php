<?php

namespace App\Http\Controllers\Web;

use App\Models\InventoryCheckInOut;
use App\Models\InventoryItem;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(Request $request): Response
    {

        $loggedInUserType = auth()->user()->type;

        $searchQuery = $request->input('search_query');

        if ($loggedInUserType === User::TYPE_ADMIN) {
            $users = User::when($searchQuery, function ($query, $searchQuery) {
                return $query->whereNOTIn('type', [User::TYPE_STUDENT, User::TYPE_GUARDIAN])->where('name', 'like', "%{$searchQuery}%");
            })->get()->take(8);
        }

        if (! auth()->user()->hasRole('manage-inventory')) {
            $pending_inventory_check_outs = InventoryCheckInOut::where('status', InventoryCheckInOut::STATUS_PENDING)
                ->where('recipient_user_id', auth()->user()->id)
                ->with(['item', 'recipient', 'provider'])
                ->paginate(5);

            $transactions = InventoryCheckInOut::where('recipient_user_id', auth()->user()->id)
                ->orWhere('provider_user_id', auth()->user()->id)
                ->orWhere('status', '!=', 'pending')
                ->with(['item', 'recipient', 'provider'])
                ->paginate(15);

            $inventoryItems = InventoryItem::whereIn('visibility', ['all', $loggedInUserType])->paginate(10);

        } else {
            $pending_inventory_check_outs = InventoryCheckInOut::where('status', InventoryCheckInOut::STATUS_PENDING)
                ->with(['item', 'recipient', 'provider'])
                ->paginate(5);

            $transactions = InventoryCheckInOut::with(['item', 'recipient', 'provider'])->paginate(15);
            $inventoryItems = InventoryItem::paginate(10);
        }

        $page = match ($loggedInUserType) {
            User::TYPE_TEACHER => 'Teacher/Inventory/Index',
            User::TYPE_ADMIN => 'Admin/Inventory/Index',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page, [
            'inventory_items' => $inventoryItems,
            'users' => Inertia::lazy(fn () => $users),
            'pending_inventory_check_outs' => $pending_inventory_check_outs,
            'transactions' => $transactions ?? null,
            'can_manage_inventory' => auth()->user()->hasRole('manage-inventory'),
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'visibility' => 'required|string|in:teachers,admins,all',
            'is_returnable' => 'required|boolean',
            'quantity' => 'required|integer',
            'low_stock_threshold' => 'required|integer',
        ]);

        if ($request->low_stock_threshold > $request->quantity) {
            return redirect()->back()->withErrors([
                'low_stock_threshold' => 'The low stock threshold cannot be greater than the quantity.',
            ]);
        }

        InventoryItem::create([
            'added_by_user_id' => auth()->user()->id,
            'name' => $request->name,
            'description' => $request->desscription,
            'visibility' => $request->visibility,
            'is_returnable' => $request->is_returnable,
            'quantity' => $request->quantity,
            'date' => Carbon::now(),
            'low_stock_threshold' => $request->low_stock_threshold,
        ]);

        return redirect()->back()->with('success', 'You have successfully added '.$request->name.' to the inventory list.');
    }

    public function allocateItem(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_user_id' => 'required|integer|exists:users,id',
            'item_id' => 'required|integer|exists:inventory_items,id',
            'quantity' => 'required|integer',
        ]);

        $inventoryItem = InventoryItem::findOrFail($request->item_id);

        if ($inventoryItem->quantity < $request->quantity) {
            // return back with validation error on the quantity
            return redirect()->back()->withErrors([
                'quantity' => 'The quantity you are trying to allocate is more than the quantity available.',
            ]);
        }

        $inventoryItem->quantity -= $request->quantity;
        $inventoryItem->save();

        InventoryCheckInOut::create([
            'inventory_item_id' => $request->item_id,
            'recipient_user_id' => $request->recipient_user_id,
            'provider_user_id' => auth()->user()->id,
            'quantity' => $request->quantity,
            'check_out_date' => Carbon::now(),
            'status' => InventoryCheckInOut::STATUS_PENDING,
        ]);

        return redirect()->back()->with('success', 'You have successfully allocated '.$request->quantity.' '.$inventoryItem->name.' to '.User::findOrFail($request->recipient_user_id)->name.'.');
    }

    public function updateInventory(Request $request): RedirectResponse
    {
        $request->validate([
            'inventory_item_id' => 'required|integer|exists:inventory_check_in_outs,id',
            'status' => 'required|string|in:approved,declined',
        ]);

        $inventoryCheckInOut = InventoryCheckInOut::findOrFail($request->inventory_item_id);
        $inventoryCheckInOut->status = $request->status;
        $inventoryCheckInOut->save();

        // Rolling back the quantity if the allocation is declined
        if ($request->status === InventoryCheckInOut::STATUS_DECLINED) {
            $inventoryItem = InventoryItem::findOrFail($inventoryCheckInOut->inventory_item_id);
            $inventoryItem->quantity += $inventoryCheckInOut->quantity;
            $inventoryItem->save();
        }

        return redirect()->back()->with('success', "Successfully {$request->status} the allocation.");
    }
}
