<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * L'inscription publique est fermée sauf si ALLOW_REGISTRATION=true.
 * Un compte inscrit n'a de toute façon pas accès au back-office sans is_admin.
 */
class EnsureRegistrationIsOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('auth.allow_registration')) {
            abort(404);
        }

        return $next($request);
    }
}
