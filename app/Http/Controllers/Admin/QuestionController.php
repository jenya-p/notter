<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Models\Quiz\Block;
use App\Models\Quiz\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller {

    public function create(Request $request) {
        $request->validate([
            'block_id' => 'required|numeric|exists:quiz_blocks,id',
        ]);
        $question = new Question([
            'block_id' => $request->block_id,
            'options' => []
        ]);
        $question->load('block:id,title,is_plain_text');
        return Inertia::render('Admin/Question/Edit', [
            'item' => $question
        ]);
    }

    public function store(QuestionRequest $request) {
        $question = Question::create($request->validated());
        return \Redirect::route('admin.block.edit', ['block' => $question->block_id]);
    }

    public function edit(Question $question) {
        $question->load('block:id,title,is_plain_text');
        return Inertia::render('Admin/Question/Edit', [
            'item' => $question,
        ]);
    }

    public function update(QuestionRequest $request, Question $question) {
        $question->update($request->validated());
        return \Redirect::route('admin.block.edit', ['block' => $question->block_id]);
    }

    public function destroy(Question $question) {
        $question->delete();
        return [
            'result' => 'ok',
            'items' => $question->block->questions
        ];
    }

    public function order(Request $request) {
        $request->validate([
            'block_id' => 'required|integer|exists:quiz_blocks,id',
            'data' => 'required|array',
            'data.*' => 'required|array',
            'data.*.ticket' => 'required|integer|min:1',
            'data.*.order' => 'required|integer|min:1',
        ]);
        try {

            \DB::beginTransaction();
            foreach ($request->get('data') as $id => $data) {
                Question::findOrFail($id)->update($data);
            }
            \DB::commit();
            $block = Block::findOrFail($request->block_id);
            return response([
                'result' => 'ok',
                'items' => $block->questions
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response(['result' => 'error'])->status(500);
        }

    }

}

