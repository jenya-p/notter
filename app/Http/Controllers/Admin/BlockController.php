<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlockRequest;
use App\Jobs\PublishBlock;
use App\Models\Quiz\Block;
use App\Models\Quiz\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlockController extends Controller {

    public function index() {
        return Inertia::render('Admin/Block/Index', [
            'items' => Block::all()->append(['ticket_count', 'question_count'])
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Block/Edit', ['item' => new Block()]);
    }


    public function store(BlockRequest $request) {
        $block = Block::create($request->validated());
        return \Redirect::route('admin.block.index');
    }

    public function edit(Block $block) {
        $block->load('questions');
        return Inertia::render('Admin/Block/Edit', [
            'item' => $block->append('ticket_count'),
        ]);
    }


    public function update(BlockRequest $request, Block $block) {
        $block->update($request->validated());

        if($block->is_plain_text){
            request()->validate([
               'questions' => 'required|array',
               'questions.*' => 'required|array',
               'questions.*.text' => 'required|string',
               'questions.*.id' => 'nullable|integer|exists:quiz_questions,id',
            ]);

            $ids = [];
            foreach (request('questions') as $index => $item){
                if(!empty($item['id'])){
                    $question = $block->questions()->findOrFail($item['id']);
                    $question->update([
                        'text' => $item['text'],
                        'order' => $index + 1
                    ]);
                } else {
                    $question = $block->questions()->create([
                        'ticket' => null,
                        'text' =>  $item['text'],
                        'order' => $index + 1,
                    ]);
                }
                $ids[] = $question->id;
            }

            $block->questions()->whereNotIn('id', $ids)->delete();

        } else if($request->get('publish') == '1'){
            PublishBlock::dispatchAfterResponse($block->id);
        }

        return \Redirect::route('admin.block.index');
    }


    public function destroy(Block $block) {
        $block->delete();
        return ['result' => 'ok'];
    }



}
