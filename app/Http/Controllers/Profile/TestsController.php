<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Test\Test;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TestsController extends Controller
{

    public function index(Request $request): Response
    {
        return Inertia::render('Profile/Tests/Index', [
            'items' => Test::my()->get()
        ]);
    }


}
