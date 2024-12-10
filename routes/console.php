<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Console\Commands\CalculateLinks;

Schedule::command(CalculateLinks::class)->dailyAt("01:00");
