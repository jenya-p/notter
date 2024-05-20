<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PaymentController extends Controller {

    public function index(Request $request) {
        $query = Payment::select('payments.*');
        $filter = trim($request->filter);

        if (!empty($filter)) {

            $date = null;
            try {
                if (preg_match('/^\d\d?\.\d\d?\.\d\d?$/m', $filter)) {
                    $date = Carbon::createFromFormat('d.m.y', trim($filter));
                } else if (preg_match('/^\d\d?\.\d\d?\.\d\d\d\d$/m', $filter)) {
                    $date = Carbon::createFromFormat('d.m.Y', trim($filter));
                }
            } catch (InvalidFormatException $e) {}

            if ($date) {
                $query->whereRaw('DATE(payments.created_at) = :date', ['date' => $date->format('Y-m-d')]);
            } else if (is_numeric($filter)) {
                $query->where('payments.id', '=', $filter);
            } else {
                $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
                $query->whereIn('user_id',
                    User::whereRaw('(email like ? or name like ?)', [$lcQuery, $lcQuery])->select('id')
                );
            }
        }

        if (!empty($request->sort)) {
            list($name, $dir) = explode(':', $request->sort);
            if ($name == 'user') {
                $query->leftJoin('users', 'users.id', '=', 'payments.user_id');
                $query->orderBy('users.email', $dir);
            } else {
                $query->orderBy($name, $dir);
            }
        }

        $query->orderBy('payments.id', 'desc');

        $items = $query->paginate(50);
        $items
            ->load('user:id,name,email')
            ->append([
                'description',
                'status_name'
            ]);

        return Inertia::render('Admin/Payment/Index', [
            'items' => $items
        ]);

    }

    public function show(Payment $payment) {
        $payment->load('user:id,name,email')->append('status_name');
        return Inertia::render('Admin/Payment/Show', [
            'item' => $payment
        ]);
    }


}
