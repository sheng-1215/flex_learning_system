<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'lecturer'])) {
            return redirect()->route('login')->with('error', 'Access denied. Admin or Lecturer privileges required.');
        }

        return $next($request);
    }
}
