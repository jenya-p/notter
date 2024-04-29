<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Variant;
use App\Models\Test\Question;
use App\Models\Test\Test;
use App\Models\Test\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TestsController extends Controller {
    public function test(Test $test, $ticketIndex = null, $questionIndex = null) {

        if ($test->user_id !== \Auth::id()) {
            return abort(403, 'Not Available');
        }

        if ($test->block->is_plain_text) {
            $test->load(
                'block:id,title',
                'block.questions:id,ticket,order,text,description',
            );

            return Inertia::render('Public/PlainTextTest', [
                'test' => $test,
            ]);
        }

        $redirectToUnresolvedQuestion = function ($ticket = null) use ($test) {
            if ($ticket === null) {
                $ticket = $test->tickets[0];
                foreach ($test->tickets as $tkt) {
                    if (empty($tkt->completed_at)) {
                        $ticket = $tkt;
                        break;
                    }
                }
            }

            foreach ($ticket->questions as $question) {
                if ($question->answer === null) {
                    return redirect()->route('test', [
                        'test' => $test->id,
                        'ticketIndex' => $ticket->order,
                        'questionIndex' => $question->order,
                    ]);
                }
            }
            return redirect()->route('test', [
                'test' => $test->id,
                'ticketIndex' => $ticket->order,
                'questionIndex' => 1,
            ]);
        };
        $redirectToSummary = function ($ticket = null) use ($test) {
            return redirect()->route('test', [
                'test' => $test->id,
                'ticketIndex' => empty($ticket) ? 1 : $ticket->order,
                'questionIndex' => 'summary',
            ]);
        };

        if ($ticketIndex == null) {
            if ($test->status !== Test::STATUS_ACTIVE) {
                return $redirectToSummary();
            } else {
                return $redirectToUnresolvedQuestion();
            }
        } else {
            if ($ticketIndex <= 0 || $ticketIndex > count($test->tickets)) {
                return abort(404, 'Ticket not found');
            }
            $ticket = $test->tickets[$ticketIndex - 1];
            if ($questionIndex == null) {
                if ($test->status === Test::STATUS_ACTIVE && $ticket->completed_at == null) {
                    return $redirectToUnresolvedQuestion($ticket);
                } else {
                    return $redirectToSummary($ticket);
                }
            } else {
                if ($questionIndex !== 'summary' && ($questionIndex <= 0 || $questionIndex > count($test->tickets[$ticketIndex - 1]->questions))) {
                    return abort(404, 'Question not found');
                }

                if ($questionIndex === 'summary' && $ticket->completed_at === null && $test->status === Test::STATUS_ACTIVE) {
                    return $redirectToUnresolvedQuestion($ticket);
                } else if (is_numeric($questionIndex) && $ticket->completed_at !== null) {
                    return $redirectToSummary($ticket);
                }
            }
        }

        $test->load(
            'tickets:id,test_id,order,completed_at'
        );

        foreach ($test->tickets as $ticket) {
            $ticket->loadInfo($test->status == Test::STATUS_ACTIVE);
        }

        return Inertia::render('Public/Test', [
            'ticketIndex' => $ticketIndex,
            'questionIndex' => $questionIndex,
            'test' => $test,
        ]);

    }

    public function answer(Question $question, Request $request) {

        if (!$question->isAvailable()) {
            return abort(403, 'Not Available');
        }

        $request->validate([
            'answer' => 'required|integer|min:0|max:' . count($question->options)
        ]);

        $question->update([
            'answer' => intval($request->answer),
            'solvede_at' => now()
        ]);

        $question->ticket->test->updateCounters();

        return ['result' => 'ok'];
    }

    public function skip(Question $question) {

        if (!$question->isAvailable()) {
            return abort(403, 'Not Available');
        }

        $question->update(['answer' => null,
            'solved_at' => null]);

        if (empty($question->ticket->started_at)) {
            $question->ticket->started_at = now();
        }

        $question->ticket->test->updateCounters();

        return ['result' => 'ok'];
    }

    public function complete(Ticket $ticket = null) {
        if (!$ticket->isAvailable()) {
            return abort(403, 'Not Available');
        }
        $ticket->update(['completed_at' => now()]);
        $ticket->loadInfo();

        if($ticket->test->tickets()->whereNull('completed_at')->count() == 0){
            $ticket->test->update(['completed_at' => now()]);
        }

        return [
            'result' => 'ok',
            'ticket' => $ticket
        ];

    }


}
