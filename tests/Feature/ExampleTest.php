<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Quiz\Block;
use App\Models\Quiz\Question;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test(): void
    {

        for ($i = 16; $i < 36; $i++){
            Question::withoutEvents(function() use ($i){
                Question::find($i)->update(['order' => rand(1, 40)]);
            });
        }
        // die;
        for ($i = 16; $i < 36; $i++){
  //          Question::withoutEvents(function() use ($i){
                Question::find($i)->update(['order' => $i - 15]);
  //          });
        }

//
//        $q = Question::find(1227);
//        $q->delete();
//        $q->fixOrder();

        // $q->fixOrder();

    }


    public function test2(){
        $b = Block::find(1);
        echo $b->ticket_count;
        die;
    }
}
