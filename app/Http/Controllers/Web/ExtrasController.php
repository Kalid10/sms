<?php

namespace App\Http\Controllers\Web;

use App\Models\InventoryCheckInOut;
use App\Models\InventoryItem;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use App\Models\StaffAbsentee;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExtrasController extends Controller
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

            $pending_count = InventoryCheckInOut::where('status', InventoryCheckInOut::STATUS_PENDING)
                ->where('recipient_user_id', auth()->user()->id)
                ->count();
            $transactions = InventoryCheckInOut::where('recipient_user_id', auth()->user()->id)
                ->orWhere('provider_user_id', auth()->user()->id)
                ->whereNOTIn('status', [InventoryCheckInOut::STATUS_PENDING, InventoryCheckInOut::STATUS_FILLED])
                ->with(['item', 'recipient', 'provider'])
                ->paginate(15);

            $inventoryItems = InventoryItem::whereIn('visibility', ['all', $loggedInUserType])->paginate(10);

        } else {
            $pending_inventory_check_outs = InventoryCheckInOut::where('status', InventoryCheckInOut::STATUS_PENDING)
                ->with(['item', 'recipient', 'provider'])
                ->paginate(5);

            $transactions = InventoryCheckInOut::with(['item', 'recipient', 'provider'])->paginate(15);
            $inventoryItems = InventoryItem::paginate(10);
            $pending_count = InventoryCheckInOut::where('status', InventoryCheckInOut::STATUS_PENDING)
                ->count();
        }

        $teacher = auth()->user()->teacher;

        $batchSchedules = $teacher->batchSchedules()
            ->with('batchSubject.subject', 'schoolPeriod', 'batch.level')
            ->get();

        // Get school period count for the current school year for a single level category
        $schoolPeriodCount = SchoolPeriod::where([
            'school_year_id' => SchoolYear::getActiveSchoolYear()->id,
        ])->distinct('start_time')->count();

        $page = match ($loggedInUserType) {
            User::TYPE_TEACHER => 'Teacher/Extras/Index',
            User::TYPE_ADMIN => 'Admin/Inventory/Index',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page, [
            'inventory_items' => $inventoryItems,
            'users' => Inertia::lazy(fn () => $users),
            'pending_inventory_check_outs' => $pending_inventory_check_outs,
            'transactions' => $transactions ?? null,
            'can_manage_inventory' => auth()->user()->hasRole('manage-inventory'),
            'pending_count' => $pending_count ?? null,
            'inventory_count' => InventoryItem::count(),
            'absentee_list' => $this->getAbsentees($request->input('date')),
            'batch_schedules' => $batchSchedules,
            'school_period_count' => $schoolPeriodCount,
        ]);
    }

    private function getAbsentees($date)
    {
        return StaffAbsentee::where('user_id', auth()->user()->id)->with('batchSession.batchSchedule.batchSubject.subject', 'batchSession.batchSchedule.batch.level')->paginate(10);
    }
}
