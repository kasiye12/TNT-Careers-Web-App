<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\CloseExpiredVacancies;
use App\Console\Commands\CleanupOldApplications;
use App\Console\Commands\GenerateSitemap;

// Daily tasks
Schedule::command('vacancies:close-expired')->dailyAt('00:01');
Schedule::command('applications:cleanup --days=180')->dailyAt('02:00');

// Weekly tasks
Schedule::command('sitemap:generate')->weeklyOn(1, '03:00');
Schedule::command('activitylog:clean')->weekly();
