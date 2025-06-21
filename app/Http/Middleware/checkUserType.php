<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next ,string $name): Response
    {
        $user = $request->user();
        if($user && ((auth()->guard('admin')->check() && $name == 'admin') || (auth()->guard('user')->check() && $name == 'user'))){
        return $next($request);
        }
        return response()->json([
            "status" => false,
            "message" => "Unauthorized Access"
        ],401);
    }
}
