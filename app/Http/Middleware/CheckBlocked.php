<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBlocked
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_blocked) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Ton compte a été suspendu.']);
        }

        return $next($request);
    }
}