<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QueryLogger
{
    public function handle(Request $request, Closure $next)
    {
        if (! config('app.debug')) {
            return $next($request);
        }

        DB::enableQueryLog();
        $response = $next($request);

        $queries = DB::getQueryLog();
        $count = count($queries);

        // Build a map of identical SQL statements to detect duplicates (classic N+1 symptom)
        $duplicates = [];
        foreach ($queries as $q) {
            $sql = $q['query'] ?? '';
            if ($sql === '') {
                continue;
            }
            $duplicates[$sql] = ($duplicates[$sql] ?? 0) + 1;
        }

        // Keep only those executed more than once
        $duplicates = array_filter($duplicates, fn ($n) => $n > 1);

        // Log summary only if noisy enough
        if ($count > 100 || ! empty($duplicates)) {
            $top = collect($duplicates)
                ->sortDesc()
                ->take(5)
                ->map(fn ($n, $sql) => [
                    'count' => $n,
                    'sql' => Str::limit($sql, 200),
                ])->values()->all();

            logger()->debug('Query summary', [
                'path' => $request->path(),
                'method' => $request->method(),
                'total' => $count,
                'duplicates_total_kinds' => count($duplicates),
                'top_duplicates' => $top,
            ]);
        }

        return $response;
    }
}
