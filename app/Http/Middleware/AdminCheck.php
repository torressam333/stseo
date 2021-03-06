<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($request->path() === 'app/admin_login') {
            return $next($request);
        }

        if (!Auth::check()) {
            return response()->json([
                'msg' => 'You are not authorized to access this page',
            ], 403);
        }

        if ($user->role->isAdmin === 0) {
            return response()->json([
                'msg' => 'You are not authorized to access this page',
            ], 403);
        }

        return $next($request);
    }
}
