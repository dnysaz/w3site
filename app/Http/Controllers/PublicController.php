<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function handleSlug($slug)
    {
        // Shortlink and Linktree features have been removed.
        // If a slug is accessed, we directly return 404.
        
        abort(404);
    }
}