<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            abort(403, 'Unauthorized.');
        }

        $allRoles = [];
        foreach ($roles as $role) {
            $allRoles = array_merge($allRoles, explode(',', $role));
        }

        if (!$request->user()->hasAnyRole($allRoles)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
