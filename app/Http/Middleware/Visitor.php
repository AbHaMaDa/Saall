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

        return $next($request);
    }
}

