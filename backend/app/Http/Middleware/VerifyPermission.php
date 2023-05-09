<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if ($user->type === "Editor") {
            return $next($request);
        }

        $current_route = Route::currentRouteName();
        switch (true) {
            case str_contains($current_route, 'users'):
                if($request->route('user')?->id != Auth::user()->id) {
                    // Return 'Unauthorized' if the authenticated user is not the owner of the current route's user ID.
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            case str_contains($current_route, 'companies'):
                if($request->isMethod('get')) {
                    return $next($request);
                }

                return response()->json(['message' => 'Unauthorized'], 401);
            case str_contains($current_route, 'articles'):
                if($request->isMethod('get')) {
                    return $next($request);
                }

                if($request->route('article')?->status === "Published" || $request->status === "Published") {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            
            default:
                return $next($request);
        }

        return $next($request);
    }
}
