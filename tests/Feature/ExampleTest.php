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

        $order = 1;
        for ($id = 16; $id < 36; $id++){
            Question::withoutEvents(function() use ($id, &$order){
                $item = Question::find($id);
                if($item){
                    $item->update(['order' => $order++]);
                }
            });
        }

        /** @var Question $q */
        $q = Question::withTrashed()->find(22);
        $q->forceDelete();

    }


    public function test2(){
        $b = Block::find(1);
        echo $b->ticket_count;
        die;
    }
}
