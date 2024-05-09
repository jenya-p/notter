<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Quiz\Block;
use App\Models\Test\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TestController extends Controller {

    public function index(Request $request) {
        $query = Test::query();
        $filter = trim($request->filter);

        if(!empty($filter)){

            // $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            // $query->whereRaw('name like ? or email like ? or subject like ?', [$lcQuery,$lcQuery,$lcQuery]);
        }

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            if($name == 'user'){
                $query->leftJoin('users', 'users.id', '=', 'tests.user_id')
                    ->orderBy('users.email', $dir);
            } else {
                $query->orderBy($name, $dir);
            }

        }
        $query->orderBy('tests.id', 'desc');
        $items = $query->paginate(50);
        $items->load('user:id,name,email');
        $items->append('status_name');
        return Inertia::render('Admin/Test/Index', [
            'items' => $items
        ]);
    }

    public function create(Request $request){
        $test = new Test();

        return Inertia::render('Admin/Test/Create', [
            'item' => $test,
            'block_options' => Block::all('id', 'title')->toArray()
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'available_till' => 'nullable|date_format:Y-m-d',
            'user_id' => 'required|integer|exists:users,id',
            'block_id' => 'required|integer|exists:quiz_blocks,id',
        ]);

        /** @var Block $block */
        $block = Block::find($request->block_id);
        $block->createTest([
                'user_id' => $request->user_id,
                'title' => $block->title,
                'amount' => 0,
                'payment_id' => null,
                'available_till' => $request->available_till,
            ]);

        return \Redirect::route('admin.test.index');
    }

    public function edit(Test $test){
        $test->load('user:id,name,email', 'block:id,title')->append('question_skipped_count');

        return Inertia::render('Admin/Test/Edit', [
            'item' => $test
        ]);
    }



    public function update(Request $request, Test $test){
        $data = $request->validate([
            'available_till' => 'nullable|date_format:Y-m-d',
        ]);
        $test->update($data);
        return \Redirect::route('admin.test.index');
    }





    public function destroy(Test $test) {
        $test->delete();
        return ['result' => 'ok'];
    }
}
