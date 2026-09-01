<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckUserIsBlocked
{
    protected array $exceptRouteNames = [
        '/',
        'courses.index',
        'courses.show',
        'about',
    ];
    protected array $exceptUrlPaths = [
        '/',
        '/courses',
        '/courses/*',
        '/about',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is($this->exceptUrlPaths) || $request->routeIs($this->exceptRouteNames)) {
            return $next($request);
        }
        if (Auth::check() && $request->user()->is_blocked) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::route('home')->with('toast', [
                'type' => 'error',
                'message' => 'Your account has been suspended. Please contact support.',
            ]);
        }

        return $next($request);
    }
}
