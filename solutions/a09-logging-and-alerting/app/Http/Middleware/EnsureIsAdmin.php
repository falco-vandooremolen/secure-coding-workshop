<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->is_admin) {
            Log::channel('security')->warning('access.denied', [
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);

            abort(403);
        }

        return $next($request);
    }
}
