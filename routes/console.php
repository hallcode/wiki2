<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Console\Commands\CalculateLinks;
use App\Console\Commands\DeleteOrphanCategories;

Schedule::command(CalculateLinks::class)->dailyAt("01:00");
Schedule::command(DeleteOrphanCategories::class)->weekly();
