<?php

namespace App\Http\Middleware;

use Closure;

class AcceptOnlyJson
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->expectsJson()) {
            return response()->json([
                'message' => 'Backend accept only json communication.',
            ], 405);
        }

        return $next($request);
    }
}
