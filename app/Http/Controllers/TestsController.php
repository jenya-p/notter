<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Test;
use App\Models\Variant;
use Inertia\Inertia;

class TestsController extends Controller {
    public function test(Test $test, $ticketIndex = null, $questionIndex = null) {

        if(!$test->isAvailable()){
            return abort(403, 'Not Available');
        }

        if($test->block->is_plain_text){
            $test->load(
                'block:id,title',
                'block.tickets:id,block_id,order',
                'block.tickets.questions:id,ticket_id,text,description',
            );

            return Inertia::render('Public/PlainTextTest', [
                'test' => $test,
            ]);
        }

        if(!empty($test->completed_at)){
            return redirect()->route('test.summary', ['test' => $test]);
        }

        if ($ticketIndex == null) {
            return redirect()->route('test', [
                'test' => $test->id,
                'ticketIndex' => 1,
                'questionIndex' => 1,
            ]);
        } else if ($questionIndex == null) {
            return redirect()->route('test', [
                'test' => $test->id,
                'ticketIndex' => $ticketIndex,
                'questionIndex' => 1,
            ]);
        }

        $test->load(
            'block:id,title',
            'block.tickets:id,block_id,order',
            'block.tickets.questions:id,ticket_id,text',
            'block.tickets.questions.variants:id,question_id,text',
        );

        $answers = $test->answers;
        $passed = [];
        foreach ($answers as $answer) {
            $passed[$answer->variant->question_id] = $answer->variant_id;
        }
        foreach ($test->block->tickets as $ticket) {
            $ticket->passed = true;

            foreach ($ticket->questions as $question) {
                if (array_key_exists($question->id, $passed)) {
                    $question->passed = true;
                    $question->answer = $passed[$question->id];
                } else {
                    $ticket->passed = false;
                    $question->answer = null;
                }
            }
        }

        if ($ticketIndex <= 0 || $ticketIndex > count($test->block->tickets)) {
            return abort(404, 'Ticket not found');
        }

        if ($questionIndex <= 0 || $questionIndex > count($test->block->tickets[$ticketIndex - 1]->questions)) {
            return abort(404, 'Question not found');
        }

        return Inertia::render('Public/Test', [
            'ticketIndex' => intval($ticketIndex),
            'questionIndex' => intval($questionIndex),
            'test' => $test,
        ]);

    }

    public function answer(Test $test, Variant $variant) {

        if(!$test->isAvailable()){
            return abort(403, 'Not Available');
        }
        if(!empty($test->completed_at)){
            return abort(403, 'Not Available');
        }

        if ($variant->is_right) {
            $rightVariantText = $variant->text;
        } else {
            /** @var Variant $rightVariant */
            $rightVariant = $variant->question->variants()->where('is_right', '=', '1')->first();
            if (!empty($rightVariant)) {
                $rightVariantText = $rightVariant->text;
            } else {
                $rightVariantText = '';
            }
        }


        $answer = Answer::updateOrCreate([
            'test_id' => $test->id,
            'question_id' => $variant->question_id
        ],[
            'variant_id' => $variant->id,
            'question_index' => $variant->question->order,
            'ticket_index' => $variant->question->ticket->order,
            'question_text' => $variant->question->text,
            'answer_text' => $variant->text,
            'is_right' =>   $variant->is_right,
            'right_answer_text' => $rightVariantText,
            'question_description' => $variant->question->description,
        ]);

        $answer->test->updateCounters();

        return ['result' => 'ok', 'answer' => $variant->id];
    }


    public function answerDelete(Test $test, Answer $question) {

        if(!$test->isAvailable()){
            return abort(403, 'Not Available');
        }
        if(!empty($test->completed_at)){
            return abort(403, 'Not Available');
        }

        $answer = $test->answers()->where('question_id', '=', $question->id)->first();
        if($answer){
            $answer->delete();
        }

        $test->updateCounters();

        return ['result' => 'ok'];
    }

    public function summary(Test $test){
        if($test->user_id !== \Auth::id()){
            return abort(403, 'Not Available');
        }

        if(empty($test->completed_at)){
            $test->completed_at = now();
            $test->save();
        }

        $test->load('answers');

        return Inertia::render('Public/Summary', ['test' => $test]);

    }

}
