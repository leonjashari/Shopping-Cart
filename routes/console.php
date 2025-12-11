<?php

use App\Jobs\SendDailySalesReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SendDailySalesReport(Carbon::today()))
    ->dailyAt('22:00');
