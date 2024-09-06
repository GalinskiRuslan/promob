<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check()) {
            $userRole = Auth::user()->role;

            if ($userRole === $role) {
                return $next($request);
            }
        }

        // Если проверка не пройдена, перенаправляем пользователя (например, на страницу входа)
        return redirect()->route('login')->with('error', 'You do not have access to this page.');
    }
}
