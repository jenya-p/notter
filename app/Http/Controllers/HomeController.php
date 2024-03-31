<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{

    public function home(){

        return Inertia::render('Public/Home', [
            'content' => \App\Models\Content::find('home')
        ]);

    }


    public function content($slug){
        return Inertia::render('Public/Content', [
            'content' => \App\Models\Content::find($slug)
        ]);
    }




    public function prices(){

        return Inertia::render('Public/Prices', []);

    }



    public function tests(){
        return Inertia::render('Public/Tests', [
        ]);

    }


    public function test(){

        return Inertia::render('Public/Test', []);

    }

}
