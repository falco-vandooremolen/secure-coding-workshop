<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AlertOnRepeatedFailures
{
    private const THRESHOLD = 5;
    private const WINDOW_SECONDS = 600;

    public function handle(Failed $event): void
    {
        $ip = Request::ip();
        $key = "failed-logins:{$ip}";

        // add() only sets the key (with its 10 minute TTL) when it does not exist yet.
        Cache::add($key, 0, self::WINDOW_SECONDS);
        $count = Cache::increment($key);

        if ($count >= self::THRESHOLD) {
            Log::channel('security')->critical('login.bruteforce_suspected', [
                'ip' => $ip,
                'failures' => $count,
                'window_seconds' => self::WINDOW_SECONDS,
            ]);
        }
    }
}
