<?php


use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BlockPublishTest extends TestCase
{

   public function test1(){
        $block = \App\Models\Quiz\Block::find(4);

        foreach ($block->tests()->active()->get() as $test){
            echo $test->id . PHP_EOL;
        }

        $test = \App\Models\Test\Test::find(2613);
        $block->publish($test);
    }

}
