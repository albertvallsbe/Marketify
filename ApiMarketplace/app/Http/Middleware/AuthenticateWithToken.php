<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AuthenticateWithToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        
        if ($token !== "9#3h2FyzR8NQn?+W%aBw@7!5%Gd%pUvT") {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    
        return $next($request);
    }
}
