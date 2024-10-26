<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{

    public function handle(Request $request, Closure $next): Response
    {
        
        if ($request->header('Authorization') !== 'Bearer '. env('TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
