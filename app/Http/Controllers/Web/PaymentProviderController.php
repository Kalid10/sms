<?php

namespace App\Http\Controllers\Web;

use App\Models\PaymentProvider;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentProviderController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:payment_providers,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_enabled' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $logo = $request->file('logo');
            $name = $request->name;

            if ($logo) {
                $logoUrl = $this->uploadLogo($logo, $name);
            }

            PaymentProvider::create([
                'name' => $name,
                'logo' => $logoUrl ?? null,
                'is_enabled' => $request->is_enabled,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Payment provider created successfully.');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', 'Error creating payment provider.');
        }
    }

    private function uploadLogo($logo, $name): string
    {

        // Resize the image before uploading to Spaces
        $img = ImageService::resize($logo->getRealPath(), 300, 200);

        // Generate a new filename for the resized image
        $filename = $name.'.'.$logo->getClientOriginalExtension();

        $directory = 'payment-providers/';
        // Upload the resized image to Spaces
        ImageService::upload($img, $filename, 'public', $directory);

        return Storage::disk('spaces')->url('rigel/'.$directory.$filename);
    }
}
