#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

define('JOB_LOG', __DIR__ . '/storage/logs/background_jobs.log');
define('ERROR_LOG', __DIR__ . '/storage/logs/background_jobs_errors.log');

function logJobStatus($message) {
    file_put_contents(JOB_LOG, "[" . date('Y-m-d H:i:s') . "] $message" . PHP_EOL, FILE_APPEND);
}

function logJobError($message) {
    file_put_contents(ERROR_LOG, "[" . date('Y-m-d H:i:s') . "] $message" . PHP_EOL, FILE_APPEND);
}

$config = require __DIR__ . '/config/background-jobs.php';

[$script, $class, $method, $params] = array_pad($argv, 4, '');

$params = $params ? explode(',', $params) : [];

if (!isset($config['allowed_jobs'][$class]) || !in_array($method, $config['allowed_jobs'][$class])) {
    logJobError("Unauthorized attempt to run $class::$method");
    exit(1);
}

$attempts = $config['retries'] ?? 3;
$delay = $config['retry_delay'] ?? 5;

for ($i = 1; $i <= $attempts; $i++) {
    try {
        $instance = new $class();
        call_user_func_array([$instance, $method], $params);
        logJobStatus("Completed job: $class::$method");
        break;
    } catch (Throwable $e) {
        logJobError("Attempt $i failed for $class::$method - " . $e->getMessage());
        if ($i < $attempts) {
            sleep($delay);
        } else {
            logJobStatus("Job failed after $attempts attempts: $class::$method");
            exit(1);
        }
    }
}
