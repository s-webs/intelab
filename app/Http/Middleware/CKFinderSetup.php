<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CKFinderSetup
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            config(['ckfinder.authentication' => function () use ($user) {
                return true;
            }]);
            $userId = $user->id;

            config(['ckfinder.backends.default.baseUrl' => "/userfiles/user_$userId/"]);
            config(['ckfinder.backends.default.root' => public_path("/userfiles/user_$userId/")]);
        }

        return $next($request);
    }
}
