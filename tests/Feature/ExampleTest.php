<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Quiz\Block;
use App\Models\Quiz\Question;
use App\Models\Test\Test;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test(): void
    {
        \Auth::login(User::find(2));




    }


    public function test2(){
        $u = User::find(1);
        $u->password = \Hash::make(123456);
        $u->save();
    }
}
