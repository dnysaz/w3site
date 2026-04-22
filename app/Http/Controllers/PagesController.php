<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{


    // --- BANTUAN & DOKUMENTASI ---
    public function documentation()
    {
        return view('pages.docs.index');
    }

    public function deploymentGuide()
    {
        return view('pages.docs.deployment');
    }

    // --- INFORMASI ---
    public function about()
    {
        return view('pages.about');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}