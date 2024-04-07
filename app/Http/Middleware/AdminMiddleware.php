<?php

namespace App\Http\Middleware;

use App\Helpers\AuthRoleHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = $request->user();
            if (AuthRoleHelper::isAdmin())
                return $next($request);
        } catch (\Throwable $th) {
            Log::debug($th);
            abort(403, 'Unauthorized');
        }
    }
}
