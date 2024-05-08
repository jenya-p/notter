<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backfeed;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BackfeedController extends Controller
{

    public function index(Request $request){
        $query = Backfeed::query();
        $filter = trim($request->filter);

        if(!empty($filter)){
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereRaw('name like ? or email like ? or subject like ?', [$lcQuery,$lcQuery,$lcQuery]);
        }

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }

        $query->orderBy('id', 'desc');
        $items = $query->paginate(50);
        $items->append('attachment_count');


        return Inertia::render('Admin/Backfeed/Index', [
            'items' => $items
        ]);

    }

    public function edit(Backfeed $backfeed){
        $backfeed->load('attachments');

        return Inertia::render('Admin/Backfeed/Edit', [
            'item' => $backfeed
        ]);
    }


    public function update(Request $request, Backfeed $backfeed){
        $data = $request->validate([
            'comment' => 'nullable|string',
            'status' => ['required', Rule::in(array_keys(Backfeed::STATUSES))]
        ]);
        $backfeed->update($data);
        $backfeed->load('attachments');
        return \Redirect::route('admin.backfeed.index');

    }



}
