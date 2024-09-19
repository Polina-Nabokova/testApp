<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('delete:recent-tokens', function () {
    DB::table('personal_access_tokens')->where('expires_at', '<=', now())->delete();
})->purpose('Delete recent personal_access_tokens')->hourly();

 
Schedule::command('sanctum:prune-expired --hours=4')->daily();