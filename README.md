# Laravel Custom Background Job Runner

## Overview
Run PHP jobs in the background without Laravel's native queue system.

Run composer install inside the project folder.

Copy .env.example to .env and run php artisan key:generate.

To test the background job:

php
Copy
Edit
runBackgroundJob(App\Jobs\ExampleJob::class, 'run', ['Hello', 'World']);

## Configuration
Edit `config/background-jobs.php` to allow jobs and set retries/delays.

## Logs
- Job Statuses: `storage/logs/background_jobs.log`
- Errors: `storage/logs/background_jobs_errors.log`
