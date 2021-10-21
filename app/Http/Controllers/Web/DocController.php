<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocController extends Controller
{
    public function mobigps($version)
    {
        return view('docs.mobigps', compact('version'));
    }
}
