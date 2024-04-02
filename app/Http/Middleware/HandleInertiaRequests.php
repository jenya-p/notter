<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware {

    // TODO https://stackoverflow.com/questions/66679747/using-2-different-blade-root-template-for-vue-with-laravel-8-and-inertia

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';


    public function version(Request $request) {
        return $this->rootView . parent::version($request);
    }

    public function handle(Request $request, \Closure $next) {

        if($request->routeIs('admin.*')){
            $this->rootView = 'admin';
        } else {
            $this->rootView = 'app';
        }
        
        return parent::handle($request, $next);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
        ];
    }
}