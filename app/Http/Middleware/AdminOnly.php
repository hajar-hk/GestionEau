<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        if (! Gate::allows('manage-users')) {
            abort(403, 'Accès interdit');
        }
        return $next($request);
    }
}
