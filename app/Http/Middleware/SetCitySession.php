<?php

namespace App\Http\Middleware;

use App\Models\City;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetCitySession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('city')) {
            Session::put('city', City::where('alias', 'almati')->firstOrFail());
        }

        return $next($request);
    }
}
