<?php

namespace App\Http\Controllers\Web;

use App\Models\LevelCategory;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class LevelCategoryController extends Controller
{
    public function index(): Response
    {
        $levelCategories = LevelCategory::all();

        return Inertia::render('Admin/Levels/LevelCategory', [
            'level_categories' => $levelCategories,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create level category
        LevelCategory::create($validated);

        return redirect()->back()->with('success', 'Level category created successfully');
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'string|max:255',
            ]);

            // Get the level category
            $levelCategory = LevelCategory::find($request->id);

            // Update the level category
            $levelCategory->update($validated);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->back()->with('success', 'Level category updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        LevelCategory::find($id)->delete();

        return redirect()->back()->with('success', 'Level Category deleted successfully');
    }
}
