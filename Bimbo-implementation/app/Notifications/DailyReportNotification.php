<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DailyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reportData;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
        $this->role = $reportData['report_type'] ?? 'general';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleName = $this->getRoleDisplayName($this->role);
        $date = $this->reportData['date'] ?? now()->format('Y-m-d');
        
        // Generate PDF report
        $pdfPath = $this->generatePDFReport($notifiable);
        
        $mailMessage = (new MailMessage)
            ->subject("Daily Report - {$roleName} - {$date}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Here is your daily report for {$date}.")
            ->line($this->getReportSummary())
            ->action('View Full Report', url('/dashboard'))
            ->line('Thank you for using our platform!');

        // Attach PDF if generated successfully
        if ($pdfPath && Storage::exists($pdfPath)) {
            $mailMessage->attach(Storage::path($pdfPath), [
                'as' => "daily_report_{$date}.pdf",
                'mime' => 'application/pdf',
            ]);
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_type' => $this->reportData['report_type'],
            'date' => $this->reportData['date'],
            'summary' => $this->reportData['summary'] ?? [],
        ];
    }

    /**
     * Generate PDF report
     */
    private function generatePDFReport($notifiable): ?string
    {
        try {
            $roleName = $this->getRoleDisplayName($this->role);
            $date = $this->reportData['date'] ?? now()->format('Y-m-d');
            
            $pdf = PDF::loadView('reports.daily', [
                'reportData' => $this->reportData,
                'user' => $notifiable,
                'roleName' => $roleName,
                'date' => $date,
            ]);

            $filename = "daily_report_{$notifiable->id}_{$date}.pdf";
            $path = "sentreports/dailyreports/{$filename}";
            
            Storage::put($path, $pdf->output());
            \Log::info('PDF report saved to: ' . $path);
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF report: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get role display name
     */
    private function getRoleDisplayName(string $role): string
    {
        $roleNames = [
            'admin_daily' => 'Administrator',
            'supplier_daily' => 'Supplier',
            'bakery_manager_daily' => 'Bakery Manager',
            'distributor_daily' => 'Distributor',
            'retail_manager_daily' => 'Retail Manager',
            'customer_daily' => 'Customer',
        ];

        return $roleNames[$role] ?? 'User';
    }

    /**
     * Get report summary for email
     */
    private function getReportSummary(): string
    {
        $summary = $this->reportData['summary'] ?? [];
        
        switch ($this->role) {
            case 'admin_daily':
                return "Today's Summary: {$summary['total_orders']} orders, \${$summary['total_revenue']} revenue, {$summary['active_vendors']} active vendors.";
            
            case 'supplier_daily':
                return "Today's Summary: {$summary['orders_received']} orders received, \${$summary['total_order_value']} total order value.";
            
            case 'bakery_manager_daily':
                return "Today's Summary: {$summary['batches_scheduled']} batches scheduled, {$summary['batches_completed']} batches completed.";
            
            case 'distributor_daily':
                return "Today's Summary: {$summary['orders_to_deliver']} orders to deliver, {$summary['orders_delivered_today']} orders delivered today.";
            
            case 'retail_manager_daily':
                return "Today's Summary: {$summary['orders_received']} orders received, \${$summary['total_sales']} total sales.";
            
            case 'customer_daily':
                return "Today's Summary: {$summary['orders_placed']} orders placed, \${$summary['total_spent']} total spent.";
            
            default:
                return "Daily report summary available in the attached PDF.";
        }
    }
} 