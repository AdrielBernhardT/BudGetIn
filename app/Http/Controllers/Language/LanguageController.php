<?php

namespace App\Http\Controllers\Language;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    /**
     * Switch application locale.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['en', 'id', 'zh'], true)) {
            session(['locale' => $locale]);
        }

        // dd(session('locale'));
        return back();
    }
}