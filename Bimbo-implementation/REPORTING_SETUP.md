# Scheduled Reporting System Setup Guide

## Overview
This system provides automated scheduled reports for different stakeholders in the Bimbo implementation. Reports are generated as PDFs and sent via email to relevant users based on their roles.

## Features
- **Daily Reports**: Sent every morning at 8:00 AM
- **Weekly Reports**: Sent every Monday at 9:00 AM  
- **Inventory Alerts**: Sent every 4 hours during business hours
- **Critical Alerts**: Sent every hour for items with ≤5 units
- **PDF Generation**: Professional PDF reports with stakeholder-specific data
- **Email Delivery**: Automated email notifications with PDF attachments

## Prerequisites

### 1. Install Required Packages
```bash
# Install PDF generation package
composer require barryvdh/laravel-dompdf

# Install queue driver (if not already installed)
composer require predis/predis
```

### 2. Configure Environment Variables
Add these to your `.env` file:
```env
# Report Scheduling
DAILY_REPORT_TIME=08:00
WEEKLY_REPORT_TIME=09:00
WEEKLY_REPORT_DAY=1
INVENTORY_ALERT_THRESHOLD=10
CRITICAL_THRESHOLD=5

# Email Configuration
REPORT_FROM_ADDRESS=reports@bimbo.com
REPORT_FROM_NAME="Bimbo Reports"
ADMIN_EMAIL=admin@bimbo.com

# Report Storage
REPORT_RETENTION_DAYS=30

# Queue Configuration (for background processing)
QUEUE_CONNECTION=redis
```

### 3. Configure Email Settings
Ensure your email configuration is set up in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=reports@bimbo.com
MAIL_FROM_NAME="Bimbo Reports"
```

### 4. Set Up Queue Worker
```bash
# Start queue worker for background processing
php artisan queue:work

# Or run as a daemon (recommended for production)
php artisan queue:work --daemon
```

### 5. Set Up Cron Job
Add this to your server's crontab:
```bash
# Laravel Scheduler (runs every minute)
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1

# Queue Worker (optional - if not running as daemon)
* * * * * cd /path/to/your/project && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

## Usage

### Manual Report Generation
```bash
# Generate daily reports for all stakeholders
php artisan reports:daily

# Generate daily reports for specific stakeholder
php artisan reports:daily --stakeholder=admin

# Generate weekly reports for all stakeholders
php artisan reports:weekly

# Generate weekly reports for specific stakeholder
php artisan reports:weekly --stakeholder=supplier

# Generate inventory alerts
php artisan reports:inventory-alert

# Generate inventory alerts with custom threshold
php artisan reports:inventory-alert --threshold=15

# Clean up old report files
php artisan reports:cleanup

# Clean up files older than 60 days
php artisan reports:cleanup --days=60
```

### Testing the System
```bash
# Test daily report generation
php artisan reports:daily --stakeholder=admin

# Test weekly report generation
php artisan reports:weekly --stakeholder=retail_manager

# Test inventory alerts
php artisan reports:inventory-alert --threshold=5
```

## Report Types by Stakeholder

### Administrator
- **Daily**: Total orders, revenue, active vendors, pending orders, production status, inventory levels, financial summary
- **Weekly**: Growth trends, performance metrics, system overview

### Supplier
- **Daily**: Orders received, inventory status, items needing restock, recent orders
- **Weekly**: Orders fulfilled, inventory turnover, supplier performance

### Bakery Manager
- **Daily**: Production batches, ingredient status, upcoming schedules
- **Weekly**: Production efficiency, ingredient consumption, batch completion rates

### Distributor
- **Daily**: Orders to deliver, delivery status, route optimization
- **Weekly**: Delivery efficiency, route optimization metrics

### Retail Manager
- **Daily**: Sales analysis, inventory status, top products, order trends
- **Weekly**: Sales trends, growth analysis, top performing products

### Customer
- **Daily**: Order status, recent purchases, account summary
- **Weekly**: Purchase history, spending patterns

## File Structure
```
app/
├── Console/
│   ├── Commands/
│   │   ├── GenerateDailyReport.php
│   │   ├── GenerateWeeklyReport.php
│   │   ├── GenerateInventoryAlert.php
│   │   └── CleanupReports.php
│   └── Kernel.php
├── Services/
│   └── ReportService.php
├── Notifications/
│   ├── DailyReportNotification.php
│   ├── WeeklyReportNotification.php
│   └── InventoryAlertNotification.php
└── Models/

resources/
└── views/
    └── reports/
        ├── daily.blade.php
        ├── weekly.blade.php
        └── inventory-alert.blade.php

config/
└── reports.php
```

## Configuration

### Customizing Report Content
Edit `config/reports.php` to customize:
- Report schedules and times
- Stakeholder configurations
- Email settings
- PDF storage settings

### Adding New Stakeholders
1. Add stakeholder configuration in `config/reports.php`
2. Create report generation method in `ReportService.php`
3. Add notification logic if needed

### Customizing PDF Templates
Edit the Blade templates in `resources/views/reports/` to customize:
- Report layout and styling
- Content sections
- Branding and colors

## Troubleshooting

### Common Issues

1. **Reports not being sent**
   - Check queue worker is running: `php artisan queue:work`
   - Check email configuration in `.env`
   - Verify cron job is set up correctly

2. **PDF generation fails**
   - Ensure `barryvdh/laravel-dompdf` is installed
   - Check storage permissions for PDF files
   - Verify Blade templates exist

3. **Scheduled tasks not running**
   - Check Laravel scheduler is running: `php artisan schedule:run`
   - Verify cron job is configured correctly
   - Check server timezone settings

### Logs
Check these log files for errors:
- `storage/logs/laravel.log` - General Laravel errors
- `storage/logs/queue.log` - Queue processing errors

### Testing Commands
```bash
# Test scheduler
php artisan schedule:list

# Test queue
php artisan queue:work --once

# Test email
php artisan tinker
Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test'); });
```

## Security Considerations

1. **Email Security**
   - Use secure SMTP connections
   - Implement rate limiting for report generation
   - Validate email addresses

2. **File Security**
   - Store PDFs in secure location
   - Implement file access controls
   - Regular cleanup of old files

3. **Data Privacy**
   - Ensure reports only contain authorized data
   - Implement role-based access controls
   - Audit report access

## Performance Optimization

1. **Queue Processing**
   - Use Redis for queue backend
   - Run multiple queue workers
   - Monitor queue performance

2. **Database Optimization**
   - Add indexes for report queries
   - Use database caching where appropriate
   - Optimize report queries

3. **File Storage**
   - Use cloud storage for PDFs (optional)
   - Implement file compression
   - Regular cleanup of old files

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review Laravel documentation
3. Check the logs for specific error messages
4. Test individual components manually 