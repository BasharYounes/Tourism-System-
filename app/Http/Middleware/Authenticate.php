<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function unauthenticated($request, array $guards){
        if ($request->expectsJson()) {
            return response()->json([
                "status" => false,
                "message" => "Unauthorized. Please log in."
            ], 401);
        }
        // parent::unauthenticated($request,$guards);
    }
}
