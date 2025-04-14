# Laravel Custom Background Job Runner

## Overview
Run PHP jobs in the background without Laravel's native queue system.

## Usage
```php
runBackgroundJob(App\Jobs\ExampleJob::class, 'run', ['param1', 'param2']);
```

## Configuration
Edit `config/background-jobs.php` to allow jobs and set retries/delays.

## Logs
- Job Statuses: `storage/logs/background_jobs.log`
- Errors: `storage/logs/background_jobs_errors.log`
