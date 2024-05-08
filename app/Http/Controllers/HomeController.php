<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Block;
use Inertia\Inertia;

class HomeController extends Controller {

    public function home() {

        return Inertia::render('Public/Home', [
            'content' => \App\Models\Content::find('home')
        ]);

    }


    public function content($slug) {
        return Inertia::render('Public/Content', [
            'content' => \App\Models\Content::find($slug)
        ]);
    }


    public function prices() {
        return Inertia::render('Public/Prices', [
            'blocks' => Block::active()->with('myActiveTests')->get()->append('available_till')
        ]);

    }


    public function tests() {
        return Inertia::render('Public/Tests', []);
    }





}
