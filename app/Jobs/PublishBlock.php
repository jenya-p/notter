<?php

namespace App\Jobs;

use App\Models\Quiz\Block;
use App\Models\Test\Test;
use App\Models\Test\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishBlock implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    public function __construct(private $id) {

    }

    public function handle(): void {
        $block = Block::findOrFail($this->id);
        if($block->is_plain_text) { return; }
        $tests = $block->tests()->active()->get();
        foreach ($tests as $test){
            $block->publish($test);
            \Log::info('publish ' . $block->id . ' block to ' . $test->id . ' test');
        }
        \Log::info('publishing ' . $block->id . ' block - OK');
    }


}
