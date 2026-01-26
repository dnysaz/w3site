<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    // --- PRODUK ---
    public function staticSite()
    {
        return view('pages.products.static-site');
    }

    public function aiBuilder()
    {
        return view('pages.products.ai-builder');
    }

    public function customDomain()
    {
        return view('pages.products.custom-domain');
    }

    public function cloudDatabase()
    {
        return view('pages.products.cloud-database');
    }

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