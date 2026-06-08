<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        $visitorKey = fn (Request $r) => $r->cookie('visitor_id') ?: $r->ip();

        RateLimiter::for('questions', fn (Request $r) => [
            Limit::perMinute(5)->by($visitorKey($r)),
            Limit::perHour(30)->by($visitorKey($r)),
        ]);

        Carbon::macro('arabicDateTime', function () {
            /** @var Carbon $this */
            $date = $this->format('Y-m-d');
            $time = $this->format('g:i');
            $suffix = strtoupper($this->format('a'));
            // Three non-breaking spaces separate the date and time so HTML
            // doesn't collapse them into one.
            $gap = "\u{00A0}\u{00A0}\u{00A0}";
            return $date . $gap . $time . ' ' . $suffix;
        });
    }
}
