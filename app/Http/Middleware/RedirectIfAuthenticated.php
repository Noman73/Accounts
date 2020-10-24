<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // dd(Auth::guard($guard)->check());
        // dd($guard);
        // switch (Auth::guard($guard)->check()) {
        //     case true:
        //     return route('home');
        //         break;
        //     case false:
        //        return redirect()->guest(route('login'));
        //         break;
        //     default:
        //        return redirect()->guest(route('login'));
        // }
        // dd(Auth::guard($guard));
        
        if (Auth::guard($guard)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }
        return $next($request);
    }
}
