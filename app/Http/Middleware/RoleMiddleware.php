<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== $role) {
            return response('Unauthorized. Your role is: ' . $user->role . ', required: ' . $role, 403);
        }
        
        return $next($request);
    }
}
