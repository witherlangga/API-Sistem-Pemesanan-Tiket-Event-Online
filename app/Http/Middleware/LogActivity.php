<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;

class LogActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        try {
            $user = $request->user();
            if ($user) {
                ActivityLog::record(
                    $user->id,
                    'request:' . $request->method(),
                    null,
                    ['path' => $request->path(), 'query' => $request->query()],
                    $request
                );
            }
        } catch (\Exception $e) {
            // don't break the app when logging fails
        }

        return $response;
    }
}
