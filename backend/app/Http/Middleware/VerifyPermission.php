<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        if($request->route('user')?->id != Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if($request->route('company')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if($request->route('article')?->status === "Published" || $request->status === "Published") {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
