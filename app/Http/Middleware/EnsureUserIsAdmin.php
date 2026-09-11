<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, \Closure $next)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin() && ! $user->isBlocked(), 403);

        return $next($request);
    }
}
