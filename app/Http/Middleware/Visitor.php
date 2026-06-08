<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Str;

class Visitor
{
    public function handle($request, Closure $next)
    {
        // لو الكوكيز مش موجودة، نولّد UUID ونحطها في كوكي مش سهل التلاعب به
        if (!$request->cookie('visitor_id')) {
            $visitorId = (string) Str::uuid(); // UUID v4
            // مدة بالـ دقائق: سنة تقريبًا
            $minutes = 60 * 24 * 365;

            // queue cookie مع HttpOnly و Secure و SameSite
            cookie()->queue(
                cookie(
                    'visitor_id',
                    $visitorId,
                    $minutes,
                    null,   // path
                    null,   // domain
                    true,   // secure (HTTPS only) — ضع true في بيئة production
                    true,   // httpOnly
                    false,  // raw
                    'Lax'   // sameSite
                )
            );
        }

        $response = $next($request);

        // Force the browser to always revalidate HTML pages. This prevents a
        // stale HTML cache from serving an old script.js?v=... reference even
        // after a new deployment.
        $contentType = $response->headers->get('Content-Type', '');
        if (str_contains($contentType, 'text/html')) {
            $response->headers->set('Cache-Control', 'no-store, must-revalidate, max-age=0');
        }

        return $response;
    }
}

