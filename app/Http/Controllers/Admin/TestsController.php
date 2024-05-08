<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backfeed;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestsController extends Controller {

    public function index(Request $request) {
        $query = Backfeed::query();
        $filter = trim($request->filter);

        if(!empty($filter)){

            // TODO Список тестов с результатами

            // $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            // $query->whereRaw('name like ? or email like ? or subject like ?', [$lcQuery,$lcQuery,$lcQuery]);
        }

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }
        $query->orderBy('id', 'desc');
        $items = $query->paginate(50);





        return Inertia::render('Admin/Tests/Index', [
            'items' => $items
        ]);
    }


    public function show(string $id) {
    }

    public function edit(string $id) {

    }


    public function update(Request $request, string $id) {

    }

    public function destroy(string $id) {

    }
}
