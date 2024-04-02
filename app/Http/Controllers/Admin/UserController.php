<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/User/Index', ['items' => User::all()])->rootView('admin');
    }

    public function create() {
        return Inertia::render('Admin/User/Edit', [

        ]);
    }

    public function edit(User $user) {
        return Inertia::render('Admin/User/Edit', [
            'item' => $user,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:256|min:4',
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'min:6'],
        ]);

        $data['password'] = \Hash::make($data['password']);
        $user = User::create($data);

        return \Redirect::route('admin.user.index');
    }

    public function update(User $user, Request $request) {

        $data = $request->validate([
            'name' => 'required|string|max:256|min:4',
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ]
        ]);

        $user->update($data);
        return \Redirect::route('admin.user.index');

    }


    public function password(Request $request) {

        $request->validate([
            'old_password' => ['required', 'min:6', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    return $fail('Неверный пароль');
                }}],
            'password' => ['required', 'min:6', 'confirmed', 'different:old_password'],
            'password_confirmation' => ['required', 'min:6'],
        ]);

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return \Redirect::route('admin.user.edit', ['user' => \Auth::id()]);

    }
}
