<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if the user is logged in
        if (! auth()->check()) {
            return redirect()->to('/admin/login');
        }

        // Check if the user is an admin
        if (! auth()->user()->canAccessPanel('admin')) {
            abort(403);
        }

        return $next($request);
    }
}
