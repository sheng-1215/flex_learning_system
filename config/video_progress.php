<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Video Progress Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for video progress tracking
    | functionality in the e-learning system.
    |
    */

    // Enable debug mode for video progress tracking
    'debug' => env('VIDEO_PROGRESS_DEBUG', false),

    // Maximum number of retries for failed progress requests
    'max_retries' => env('VIDEO_PROGRESS_MAX_RETRIES', 3),

    // Delay between retries in seconds
    'retry_delay' => env('VIDEO_PROGRESS_RETRY_DELAY', 1),

    // Progress update threshold (percentage change required to trigger update)
    'update_threshold' => env('VIDEO_PROGRESS_UPDATE_THRESHOLD', 1),

    // Enable progress logging
    'enable_logging' => env('VIDEO_PROGRESS_LOGGING', true),

    // Log level for progress tracking
    'log_level' => env('VIDEO_PROGRESS_LOG_LEVEL', 'info'),

    // Enable progress caching
    'enable_caching' => env('VIDEO_PROGRESS_CACHING', false),

    // Cache TTL for progress data (in minutes)
    'cache_ttl' => env('VIDEO_PROGRESS_CACHE_TTL', 60),

    // Enable rate limiting for progress updates
    'enable_rate_limiting' => env('VIDEO_PROGRESS_RATE_LIMITING', true),

    // Maximum progress updates per minute per user
    'max_updates_per_minute' => env('VIDEO_PROGRESS_MAX_UPDATES_PER_MINUTE', 60),

    // Enable progress validation
    'enable_validation' => env('VIDEO_PROGRESS_VALIDATION', true),

    // Minimum progress value
    'min_progress' => env('VIDEO_PROGRESS_MIN', 0),

    // Maximum progress value
    'max_progress' => env('VIDEO_PROGRESS_MAX', 100),

    // Enable progress backup
    'enable_backup' => env('VIDEO_PROGRESS_BACKUP', false),

    // Backup storage driver
    'backup_driver' => env('VIDEO_PROGRESS_BACKUP_DRIVER', 'database'),

    // Enable progress analytics
    'enable_analytics' => env('VIDEO_PROGRESS_ANALYTICS', false),

    // Analytics storage driver
    'analytics_driver' => env('VIDEO_PROGRESS_ANALYTICS_DRIVER', 'database'),
];
