<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TestsController extends Controller
{

    public function index(Request $request): Response
    {
        return Inertia::render('Profile/Tests/Index', [

        ]);
    }


}
