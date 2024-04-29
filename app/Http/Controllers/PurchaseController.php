<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Block;
use App\Models\Test\Test;
use Illuminate\Http\Request;

class PurchaseController extends Controller {

    public function create(Request $request) {

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:quiz_blocks,id',
            ]);

        $blocks = Block::active()->find($request->ids);

        /** @var Block $block */
        foreach ($blocks as $block){
            $block->createTest([
                'user_id' => \Auth::id(),
                'title' => $block->title,
                'amount' => $block->price,
                'payment_id' => null,
                'available_till' => now()->addMonth()
            ]);
        }

        return redirect()->route('profile-test.index');

//        return Inertia::render('Public/Purchase', [
//           'items' => Block::active()->find($request->ids)
//        ]);

    }

    public function store(){



    }

}
