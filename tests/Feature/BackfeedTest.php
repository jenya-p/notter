<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BackfeedTest extends TestCase
{

    public function testCreate(){
        $f = Factory::create('ru');

        $response = $this->postJson('backfeed', [
            'name' => $f->text(10),
            'email' => $f->email(),
            'subject' => $f->text(150),
            'body' => $f->text(600),
            'files' => [
//                new \Illuminate\Http\UploadedFile('D:\Documents\Pictures\_4Tests\1.jpg', '1.jpg', 'image/jpg', filesize('D:\Documents\Pictures\_4Tests\1.jpg'), true),
//                new \Illuminate\Http\UploadedFile('D:\Documents\Pictures\_4Tests\2.jpg', '2.jpg', 'image/jpg', filesize('D:\Documents\Pictures\_4Tests\2.jpg'), true),
            ]
        ]);


        dd($response->json());

    }



    public function testYoomney(){
        //echo Carbon::createFromFormat('d.m.y', '19.05.2024')->format('Y-m-d') . "\n";
        echo Carbon::createFromFormat('d.m.y', '2.5.1981')->format('Y-m-d') . "\n";
//        echo Carbon::createFromFormat('d.m.Y', '02.5.1981')->format('Y-m-d') . "\n";
//        echo Carbon::createFromFormat('d.m.y', '02.05.1981')->format('Y-m-d') . "\n";

    }

}
