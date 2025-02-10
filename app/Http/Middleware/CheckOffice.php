<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Psr7\Header;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOffice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->offices->count() == 0) {
            if ($request->route()->getName() !== 'dashboard') {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
