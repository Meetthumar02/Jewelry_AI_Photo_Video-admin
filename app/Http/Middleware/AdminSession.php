<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('ADMIN_LOGIN')) {
            return redirect('/login')->with('error', 'Please login first');
        }

        return $next($request);
    }
}
