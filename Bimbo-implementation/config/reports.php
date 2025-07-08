<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Report Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the reporting system.
    |
    */

    'defaults' => [
        'daily_report_time' => '08:00',
        'weekly_report_time' => '09:00',
        'weekly_report_day' => 1, // Monday
        'inventory_alert_threshold' => 10,
        'critical_threshold' => 5,
        'business_hours' => [
            'start' => '06:00',
            'end' => '22:00',
        ],
    ],

    'schedules' => [
        'daily' => [
            'enabled' => true,
            'time' => env('DAILY_REPORT_TIME', '08:00'),
            'stakeholders' => ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'customer'],
        ],
        'weekly' => [
            'enabled' => true,
            'day' => env('WEEKLY_REPORT_DAY', 1), // Monday
            'time' => env('WEEKLY_REPORT_TIME', '09:00'),
            'stakeholders' => ['admin', 'supplier', 'bakery_manager', 'distributor', 'retail_manager', 'customer'],
        ],
        'inventory_alerts' => [
            'enabled' => true,
            'frequency' => 'everyFourHours',
            'threshold' => env('INVENTORY_ALERT_THRESHOLD', 10),
            'critical_threshold' => env('CRITICAL_THRESHOLD', 5),
            'stakeholders' => ['admin', 'supplier', 'retail_manager'],
        ],
    ],

    'email' => [
        'from_address' => env('REPORT_FROM_ADDRESS', 'reports@bimbo.com'),
        'from_name' => env('REPORT_FROM_NAME', 'Bimbo Reports'),
        'admin_email' => env('ADMIN_EMAIL', 'admin@bimbo.com'),
    ],

    'pdf' => [
        'enabled' => true,
        'storage_path' => 'reports',
        'retention_days' => env('REPORT_RETENTION_DAYS', 30),
    ],

    'stakeholders' => [
        'admin' => [
            'name' => 'Administrator',
            'reports' => ['daily', 'weekly'],
            'alerts' => ['inventory'],
        ],
        'supplier' => [
            'name' => 'Supplier',
            'reports' => ['daily', 'weekly'],
            'alerts' => ['inventory'],
        ],
        'bakery_manager' => [
            'name' => 'Bakery Manager',
            'reports' => ['daily', 'weekly'],
            'alerts' => [],
        ],
        'distributor' => [
            'name' => 'Distributor',
            'reports' => ['daily', 'weekly'],
            'alerts' => [],
        ],
        'retail_manager' => [
            'name' => 'Retail Manager',
            'reports' => ['daily', 'weekly'],
            'alerts' => ['inventory'],
        ],
        'customer' => [
            'name' => 'Customer',
            'reports' => ['daily', 'weekly'],
            'alerts' => [],
        ],
    ],

    'content' => [
        'daily_summary_sections' => [
            'orders' => true,
            'production' => true,
            'inventory' => true,
            'financial' => true,
            'recent_activity' => true,
        ],
        'weekly_summary_sections' => [
            'trends' => true,
            'growth_analysis' => true,
            'top_performers' => true,
            'comparison' => true,
        ],
    ],
]; 