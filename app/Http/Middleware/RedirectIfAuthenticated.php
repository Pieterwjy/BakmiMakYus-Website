<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // return redirect(RouteServiceProvider::HOME);

                if (Auth::user()->role === 'owner') {
                    return redirect()->route('owner.dashboard');
                } elseif (Auth::user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif (Auth::user()->role === 'cook') {
                    return redirect()->route('cook.dashboard');
                }
            }
        }

        if (Auth::check()) {
            // Check the user's role and redirect accordingly
                    if (Auth::user()->role === 'owner') {
                        return redirect()->route('owner.dashboard');
                    } elseif (Auth::user()->role === 'admin') {
                        return redirect()->route('admin.dashboard');
                    } elseif (Auth::user()->role === 'cook') {
                        return redirect()->route('cook.dashboard');
                    }
                }
        return $next($request);
    }
}
