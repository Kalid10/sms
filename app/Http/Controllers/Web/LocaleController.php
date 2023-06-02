<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function setLocale($locale): RedirectResponse
    {
        // ToDo: Add more validation here to ensure the locale is one you support.
        App::setLocale($locale);
        Session::put('locale', $locale);

        return redirect()->back()->with('success', 'Locale set successfully');
    }
}
